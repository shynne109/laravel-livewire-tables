<?php

namespace Rappasoft\LaravelLivewireTables\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;

trait WithRowRefresh
{
    /**
     * Refresh a single row in the table by its primary key value.
     *
     * Uses #[Renderless] to skip the full table re-render — avoiding both
     * the expensive getRows() query and the heavy morphdom diff. Cell
     * contents are rendered server-side and pushed to the client via JS
     * for a targeted DOM update.
     *
     * Dispatch from any Livewire component:
     *   $this->dispatch('refreshing-row', id: $id, tableName: 'my-table');
     *   $this->dispatch('refreshRow', id: $id)->to(MyTable::class);
     *
     * Dispatch from Alpine.js / JavaScript:
     *   $dispatch('refreshing-row', { id: 123, tableName: 'my-table' });
     *   Livewire.dispatch('refreshRow', { id: 123 });
     */
    #[Renderless]
    #[On('refreshRow')]
    public function refreshRow(int|string $id): void
    {
        $freshRow = $this->fetchFreshRow($id);
        $tableName = $this->getTableName();

        if (! $freshRow) {
            $this->dispatch('row-refreshed', id: $id, tableName: $tableName);

            return;
        }

        // Render each cell's content server-side using the column definitions
        $cells = [];

        foreach ($this->getColumns() as $colIndex => $column) {
            if ($column->isLabel()) {
                continue;
            }

            $slug = $column->getSlug();
            $content = (string) $column->setIndexes(0, $colIndex)->renderContents($freshRow);
            $isHtml = $column->isHtml();

            $cells[] = [
                'key' => $tableName.'-table-td-'.$id.'-'.$slug,
                'content' => $isHtml ? $content : e($content),
            ];
        }

        // Push rendered cells to client for targeted DOM update
        $this->js(
            '
            const cells = '.json_encode($cells).";
            cells.forEach(cell => {
                const td = document.querySelector('[wire\\\\:key=\"' + cell.key + '\"]');
                if (td) td.innerHTML = cell.content;
            });
            "
        );

        $this->dispatch('row-refreshed', id: $id, tableName: $tableName);
    }

    /**
     * Refresh multiple rows in the table by their primary key values.
     *
     * More efficient than calling refreshDatatable when you need to update
     * a handful of rows rather than the entire dataset.
     *
     * Dispatch from any Livewire component:
     *   $this->dispatch('refreshRows', ids: [1, 2, 3])->to(MyTable::class);
     */
    #[Renderless]
    #[On('refreshRows')]
    public function refreshRows(array $ids): void
    {
        $tableName = $this->getTableName();
        $freshRows = $this->fetchFreshRows($ids);

        foreach ($ids as $id) {
            $freshRow = $freshRows->firstWhere($this->getPrimaryKey(), $id);

            if (! $freshRow) {
                continue;
            }

            $cells = [];

            foreach ($this->getColumns() as $colIndex => $column) {
                if ($column->isLabel()) {
                    continue;
                }

                $slug = $column->getSlug();
                $content = (string) $column->setIndexes(0, $colIndex)->renderContents($freshRow);
                $isHtml = $column->isHtml();

                $cells[] = [
                    'key' => $tableName.'-table-td-'.$id.'-'.$slug,
                    'content' => $isHtml ? $content : e($content),
                ];
            }

            $this->js(
                '
                const cells = '.json_encode($cells).";
                cells.forEach(cell => {
                    const td = document.querySelector('[wire\\\\:key=\"' + cell.key + '\"]');
                    if (td) td.innerHTML = cell.content;
                });
                "
            );
        }

        $this->dispatch('rows-refreshed', ids: $ids, tableName: $tableName);
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
