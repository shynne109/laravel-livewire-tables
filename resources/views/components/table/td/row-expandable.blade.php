@aware(['tableName', 'primaryKey', 'isTailwind', 'isBootstrap'])
@props(['row', 'rowIndex'])

@if ($this->rowExpandableIsEnabled && $this->isRowExpandableVisible($row))
    <td wire:key="{{ $tableName }}-row-expandable-toggle-{{ $row->{$primaryKey} }}"
        {{
            $attributes
                ->merge()
                ->class([
                    'p-3 text-center' => $isTailwind,
                    'text-center' => $isBootstrap,
                ])
        }}
    >
        <button
            x-on:click.prevent="rowExpandable = !rowExpandable"
            type="button"
            @class([
                'border-0 bg-transparent p-0' => $isBootstrap,
            ])
        >
            <x-heroicon-o-chevron-down x-cloak x-show="!rowExpandable" {{
                $attributes->merge($this->getRowExpandableButtonExpandAttributes)
                    ->class([
                        'h-5 w-5' => $isTailwind && ($this->getRowExpandableButtonExpandAttributes['default-styling'] ?? true),
                        'text-gray-500 dark:text-gray-400' => $isTailwind && ($this->getRowExpandableButtonExpandAttributes['default-colors'] ?? true),
                        'laravel-livewire-tables-btn-sm text-secondary' => $isBootstrap && ($this->getRowExpandableButtonExpandAttributes['default-colors'] ?? true),
                    ])
                    ->except(['default', 'default-styling', 'default-colors'])
            }} />
            <x-heroicon-o-chevron-up x-cloak x-show="rowExpandable" {{
                $attributes->merge($this->getRowExpandableButtonCollapseAttributes)
                    ->class([
                        'h-5 w-5' => $isTailwind && ($this->getRowExpandableButtonCollapseAttributes['default-styling'] ?? true),
                        'text-gray-500 dark:text-gray-400' => $isTailwind && ($this->getRowExpandableButtonCollapseAttributes['default-colors'] ?? true),
                        'laravel-livewire-tables-btn-sm text-secondary' => $isBootstrap && ($this->getRowExpandableButtonCollapseAttributes['default-colors'] ?? true),
                    ])
                    ->except(['default', 'default-styling', 'default-colors'])
            }} />
        </button>
    </td>
@endif
