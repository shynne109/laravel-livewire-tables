# AGENT.md

Guidance for AI agents working with **`rappasoft/laravel-livewire-tables`** — a dynamic data-table component for Laravel + Livewire.

This file describes how to *use* the package when building tables in a Laravel application. For contributing to the package itself, see `CONTRIBUTING.md`.

## What this package is

A Livewire component (`DataTableComponent`) that renders sortable, searchable, filterable, paginated tables backed by an Eloquent model. Supports Tailwind (default), Bootstrap 4, and Bootstrap 5 themes. Requires PHP ^8.1, Livewire ^3|^4, and Alpine.js 3+.

## Installation

```bash
composer require rappasoft/laravel-livewire-tables
```

Alpine.js 3+ must be available globally (Livewire 3+ bundles it). Theme is set in `config/livewire-tables.php` (`tailwind` | `bootstrap-4` | `bootstrap-5`); publish config with:

```bash
php artisan vendor:publish --provider="Rappasoft\LaravelLivewireTables\LaravelLivewireTablesServiceProvider" --tag=livewire-tables-config
```

**Do not** publish views unless strictly necessary — views change often and publishing them opts you out of upstream fixes.

## Creating a table component

Always scaffold with the Artisan command rather than hand-writing the file:

```bash
# UsersTable in App\Livewire backed by App\Models\User
php artisan make:datatable UsersTable User

# Custom model path
php artisan make:datatable TestTable Example app/Domains/Test/Models/

# Interactive (prompts for missing args)
php artisan make:datatable
```

A component extends `DataTableComponent` and must implement at minimum `configure()` and `columns()`:

```php
<?php

namespace App\Livewire;

use App\Models\User;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UsersTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Name')->sortable()->searchable(),
        ];
    }
}
```

Render it in a Blade view with `<livewire:users-table />`.

## The query

You must supply the data source via **one** of:

- `protected $model = User::class;` — simple tables, package builds the query.
- `public function builder(): Builder` — full control (joins, selects, eager loads, scopes). Return an Eloquent `Builder`.

```php
public function builder(): Builder
{
    return User::query()->with('roles')->where('active', true);
}
```

Missing both throws an exception. Only Eloquent is supported (use `calebporzio/sushi` for arrays).

## configure()

Chain setter methods on `$this`. The single required call is `setPrimaryKey('id')`. Common options:

- `setPrimaryKey('id')` — **required**.
- `setSingleSortingDisabled()` — allow multi-column sort.
- `setSearchDebounce(500)` / `setSearchDisabled()`.
- `setPerPageAccepted([10, 25, 50])` / `setPerPage(25)` / `setPaginationDisabled()`.
- `setFilterLayoutSlideDown()` / `setFilterLayoutPopover()`.
- `setReorderEnabled()` — drag-and-drop row ordering.
- `setColumnSelectDisabled()` / `setRememberColumnSelectionDisabled()`.
- Full-page component: `setLayout('layouts.app')`, `setSlot('slot')`, `setSection('section')`.

See `docs/datatable/available-methods.md` for the full list — prefer searching the docs over guessing method names.

## Columns

`Column::make('Title', 'field')` — second arg is the DB field/relation path; omit it to derive from the title. Relations use dot notation: `Column::make('City', 'address.city.name')`.

Common modifiers: `->sortable()`, `->searchable()`, `->html()`, `->label(fn ($row, Column $column) => ...)`, `->collapseOnMobile()`, `->collapseOnTablet()`, `->view('path.to.blade')`, `->format(fn ($value, $row, Column $column) => ...)`, `->deselected()`, `->excludeFromColumnSelect()`.

### Column types (`Rappasoft\LaravelLivewireTables\Views\Columns\*`)

`BooleanColumn`, `ButtonGroupColumn`, `LinkColumn`, `WireLinkColumn`, `ImageColumn`, `IconColumn`, `ColorColumn`, `DateColumn`, `ArrayColumn`, `ComponentColumn`, `ViewComponentColumn`, `LivewireComponentColumn`, plus aggregates `CountColumn`, `SumColumn`, `AvgColumn`, `AggregateColumn`, `IncrementColumn`.

Each has type-specific builder methods (e.g. `ImageColumn::make('Avatar')->location(fn ($row) => ...)`, `LinkColumn::make('Action')->title(fn ($row) => 'Edit')->location(fn ($row) => route(...))`). Check `docs/column-types/` and `src/Views/Columns/` for the exact API before using one.

## Filters

Implement `filters(): array`. Filter classes live in `Rappasoft\LaravelLivewireTables\Views\Filters\*`:

`TextFilter`, `NumberFilter`, `NumberRangeFilter`, `SelectFilter`, `MultiSelectFilter`, `MultiSelectDropdownFilter`, `BooleanFilter`, `DateFilter`, `DateTimeFilter`, `DateRangeFilter`, `LivewireComponentFilter`, `LivewireComponentArrayFilter`.

```php
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

public function filters(): array
{
    return [
        SelectFilter::make('Active', 'active')
            ->options(['' => 'All', '1' => 'Yes', '0' => 'No'])
            ->filter(function (Builder $builder, string $value) {
                $builder->where('active', $value === '1');
            }),
    ];
}
```

## Conventions for this codebase

- Use the `make:datatable` command to scaffold; match existing sibling components in `App\Livewire`.
- Explicit return types and typed params (`Builder`, `Column`, `string`) on every method — this package and the host app enforce it.
- Prefer named routes (`route()`) inside `LinkColumn`/`label` closures.
- Eager-load relations to avoid N+1: `eagerLoadRelations()` on a column or `setEagerLoadAllRelationsEnabled()` in `configure()`.
- **Always search the docs** (`docs/`) for the precise, version-correct method before inventing an API. The fluent surface is large and easy to misremember.

## Testing

Tables are standard Livewire components — test with Livewire's testing helpers:

```php
use function Pest\Livewire\livewire;

livewire(UsersTable::class)
    ->assertSee('Name')
    ->call('setSort', 'name')
    ->assertSet('sortDirection', 'asc');
```

Run the host app's suite with `php artisan test --compact --filter=UsersTable`.

## Key references

- `docs/` — authoritative, versioned documentation (start, columns, filters, datatable, examples).
- `src/DataTableComponent.php` and `src/Traits/` — the configurable method surface.
- `README.md` — quick start; official docs: https://rappasoft.com/docs/laravel-livewire-tables
