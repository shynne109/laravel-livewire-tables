@aware(['tableName', 'primaryKey', 'isTailwind', 'isBootstrap'])
@props(['row', 'rowIndex'])

@if ($this->rowDetailIsEnabled && $this->rowDetailTriggerIsButton && $this->isRowDetailVisible($row))
    <td wire:key="{{ $tableName }}-row-detail-toggle-{{ $row->{$primaryKey} }}"
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
            x-on:click.prevent="rowDetail = !rowDetail"
            type="button"
            @class([
                'border-0 bg-transparent p-0' => $isBootstrap,
            ])
        >
            <x-heroicon-o-chevron-down x-cloak x-show="!rowDetail" {{
                $attributes->merge($this->getRowDetailButtonExpandAttributes)
                    ->class([
                        'h-5 w-5' => $isTailwind && ($this->getRowDetailButtonExpandAttributes['default-styling'] ?? true),
                        'text-gray-500 dark:text-gray-400' => $isTailwind && ($this->getRowDetailButtonExpandAttributes['default-colors'] ?? true),
                        'laravel-livewire-tables-btn-sm text-secondary' => $isBootstrap && ($this->getRowDetailButtonExpandAttributes['default-colors'] ?? true),
                    ])
                    ->except(['default', 'default-styling', 'default-colors'])
            }} />
            <x-heroicon-o-chevron-up x-cloak x-show="rowDetail" {{
                $attributes->merge($this->getRowDetailButtonCollapseAttributes)
                    ->class([
                        'h-5 w-5' => $isTailwind && ($this->getRowDetailButtonCollapseAttributes['default-styling'] ?? true),
                        'text-gray-500 dark:text-gray-400' => $isTailwind && ($this->getRowDetailButtonCollapseAttributes['default-colors'] ?? true),
                        'laravel-livewire-tables-btn-sm text-secondary' => $isBootstrap && ($this->getRowDetailButtonCollapseAttributes['default-colors'] ?? true),
                    ])
                    ->except(['default', 'default-styling', 'default-colors'])
            }} />
        </button>
    </td>
@endif
