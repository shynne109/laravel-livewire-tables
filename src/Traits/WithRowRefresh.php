<?php

namespace Rappasoft\LaravelLivewireTables\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

trait WithRowRefresh
{
    /**
     * Refresh a single row in the table by its primary key value.
     *
     * Instead of re-querying the entire dataset (like refreshDatatable),
     * this fetches only the single row from the database and replaces it
     * in the cached rows collection. Livewire's morphdom diffing ensures
     * only the changed row's DOM is updated.
     *
     * Dispatch from any Livewire component:
     *   $this->dispatch('refreshRow', id: $primaryKeyValue);
     *
     * Dispatch from Alpine.js / JavaScript:
     *   Livewire.dispatch('refreshRow', { id: 123 });
     *
     * Dispatch to a specific table (when multiple tables exist on the page):
     *   $this->dispatch('refreshRow', id: $id)->to(EmployeeTable::class);
     */
    #[On('refreshRow')]
    public function refreshRow(int|string $id): void
    {
        $rows = $this->getRows;

        // If getRows hasn't been computed yet, nothing to refresh in cache
        if ($rows === null) {
            return;
        }

        $primaryKey = $this->getPrimaryKey();

        $items = $this->getRowsCollection($rows);

        $index = $items->search(fn ($item) => $item->{$primaryKey} == $id);

        if ($index === false) {
            return; // Row not on current page — no-op
        }

        $freshRow = $this->fetchFreshRow($id);

        if ($freshRow) {
            $items[$index] = $freshRow;
        } else {
            // Row was deleted — remove it from the collection
            $items->forget($index);

            $this->paginationCurrentCount = $items->count();
            $this->paginationCurrentItems = $items->pluck($primaryKey)->toArray();
        }

        // Dispatch browser event so Alpine can clear per-row loading state
        $this->dispatch('row-refreshed', id: $id, tableName: $this->getTableName());
    }

    /**
     * Refresh multiple rows in the table by their primary key values.
     *
     * More efficient than calling refreshDatatable when you need to update
     * a handful of rows rather than the entire dataset.
     *
     * Dispatch from any Livewire component:
     *   $this->dispatch('refreshRows', ids: [1, 2, 3]);
     */
    #[On('refreshRows')]
    public function refreshRows(array $ids): void
    {
        $rows = $this->getRows;

        if ($rows === null) {
            return;
        }

        $primaryKey = $this->getPrimaryKey();
        $items = $this->getRowsCollection($rows);

        // Fetch all requested rows in a single query
        $freshRows = $this->fetchFreshRows($ids);
        $removedAny = false;

        foreach ($ids as $id) {
            $index = $items->search(fn ($item) => $item->{$primaryKey} == $id);

            if ($index === false) {
                continue;
            }

            $freshRow = $freshRows->firstWhere($primaryKey, $id);

            if ($freshRow) {
                $items[$index] = $freshRow;
            } else {
                $items->forget($index);
                $removedAny = true;
            }
        }

        if ($removedAny) {
            $this->paginationCurrentCount = $items->count();
            $this->paginationCurrentItems = $items->pluck($primaryKey)->toArray();
        }

        // Dispatch browser event to clear per-row loading states
        $this->dispatch('rows-refreshed', ids: $ids, tableName: $this->getTableName());
    }

    /**
     * Extract the underlying Collection from rows (works with paginators and plain collections).
     */
    protected function getRowsCollection(mixed $rows): Collection
    {
        if ($rows instanceof AbstractPaginator || $rows instanceof AbstractCursorPaginator) {
            return $rows->getCollection();
        }

        return $rows;
    }

    /**
     * Fetch a single fresh row from the database.
     *
     * Uses the same base builder (with relationships, extra withs, counts, sums, avgs)
     * so the returned model has the same structure as the original rows.
     *
     * Override this method if your table uses a custom builder() that requires
     * additional scoping for single-row fetches.
     */
    protected function fetchFreshRow(int|string $id): mixed
    {
        return $this->buildFreshRowQuery()->find($id);
    }

    /**
     * Fetch multiple fresh rows from the database in a single query.
     */
    protected function fetchFreshRows(array $ids): Collection
    {
        return $this->buildFreshRowQuery()->whereIn(
            $this->getModel()->getQualifiedKeyName(),
            $ids
        )->get();
    }

    /**
     * Build a query for fetching fresh row(s) with the same eager loads
     * and aggregates as the table's base query.
     */
    protected function buildFreshRowQuery(): Builder
    {
        $query = $this->getModel()::query()
            ->with($this->getRelationships());

        if ($this->hasExtraWiths()) {
            $query->with($this->getExtraWiths());
        }

        if ($this->hasExtraWithCounts()) {
            $query->withCount($this->getExtraWithCounts());
        }

        if ($this->hasExtraWithSums()) {
            foreach ($this->getExtraWithSums() as $extraSum) {
                $query->withSum($extraSum['table'], $extraSum['field']);
            }
        }

        if ($this->hasExtraWithAvgs()) {
            foreach ($this->getExtraWithAvgs() as $extraAvg) {
                $query->withAvg($extraAvg['table'], $extraAvg['field']);
            }
        }

        return $query;
    }
}
