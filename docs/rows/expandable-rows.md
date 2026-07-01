---
title: Expandable Rows
weight: 2
---

Expandable rows allow you to show detailed information inline below each row. Clicking a row or a button reveals a detail panel that pushes other rows down. You can render any Blade view, component, or Livewire component inside the expandable area.

## Basic Setup

Enable expandable rows in your `configure()` method and point to a Blade view:

```php
public function configure(): void
{
    $this->setPrimaryKey('id');
    $this->setRowExpandableEnabled();
    $this->setRowExpandableView('partials.user-detail');
}
```

The view receives `$row` (the Eloquent model) and `$rowIndex`:

```blade
{{-- resources/views/partials/user-detail.blade.php --}}
<div class="grid grid-cols-2 gap-4 text-sm">
    <div>
        <span class="font-semibold text-gray-500">Created</span>
        <p>{{ $row->created_at?->format('M d, Y') ?? 'N/A' }}</p>
    </div>
    <div>
        <span class="font-semibold text-gray-500">Email</span>
        <p>{{ $row->email }}</p>
    </div>
</div>
```

By default, a chevron toggle button column is added to the table. The toggle is powered by Alpine.js — no server round-trip required.

## Row Click Trigger

To also make the entire row clickable (in addition to the chevron button):

```php
$this->setRowExpandableEnabled();
$this->setRowExpandableView('partials.user-detail');
$this->setRowExpandableRowClickEnabled();
```

This adds `cursor-pointer` styling and an Alpine click handler to each row. You can disable it with `setRowExpandableRowClickDisabled()`.

## Custom Trigger Button

You can use your own button in a column to toggle the expandable area. The Alpine variable `rowExpandable` is available on every `<tr>` when the feature is enabled:

```php
public function columns(): array
{
    return [
        Column::make('Name', 'name')->sortable(),
        Column::make('Email', 'email')->sortable(),
        Column::make('Details', 'id')
            ->format(function ($value, $row, Column $column) {
                return '<button x-on:click="rowExpandable = !rowExpandable"
                    class="text-blue-600 hover:text-blue-800">
                    Show Details
                </button>';
            })
            ->html(),
    ];
}
```

## Rendering Components

You can render anything inside the expandable view, including Livewire components:

```blade
{{-- resources/views/partials/user-detail.blade.php --}}
<div class="p-4">
    <livewire:user-activity :user="$row" />
</div>
```

Or Blade components:

```blade
<div class="p-4">
    <x-user-profile-card :user="$row" />
</div>
```

## Conditional Visibility

To control which rows can be expanded, use a visibility callback:

```php
$this->setRowExpandableVisibleCallback(fn ($row) => $row->has_details);
```

Rows that don't pass the callback will not have the expand button or click handler.

## Styling the Toggle Button

Customize the expand/collapse chevron button attributes:

```php
$this->setRowExpandableButtonExpandAttributes([
    'class' => 'h-6 w-6 text-green-500',
]);

$this->setRowExpandableButtonCollapseAttributes([
    'class' => 'h-6 w-6 text-red-500',
]);
```

## Available Methods

| Method | Description |
|--------|-------------|
| `setRowExpandableEnabled()` | Enable expandable rows |
| `setRowExpandableDisabled()` | Disable expandable rows |
| `setRowExpandableView(string $view)` | Set the Blade view for the detail panel |
| `setRowExpandableRowClickEnabled()` | Make the entire row clickable to toggle |
| `setRowExpandableRowClickDisabled()` | Disable row click toggling |
| `setRowExpandableVisibleCallback(Closure $callback)` | Control which rows can be expanded |
| `setRowExpandableButtonExpandAttributes(array $attributes)` | Customize expand button styling |
| `setRowExpandableButtonCollapseAttributes(array $attributes)` | Customize collapse button styling |
