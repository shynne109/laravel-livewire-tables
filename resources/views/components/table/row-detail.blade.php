@aware(['tableName', 'primaryKey', 'isTailwind', 'isBootstrap'])
@props(['row', 'rowIndex'])

@if ($this->rowDetailIsEnabled && $this->hasRowDetailView() && $this->isRowDetailVisible($row))
    @php($customAttributes = $this->getTrAttributes($row, $rowIndex))
    <tr x-data
        x-on:toggle-row-detail.window="($event.detail.tableName === '{{ $tableName }}' && $event.detail.row === {{ $rowIndex }}) ? $el.classList.toggle('{{ $isBootstrap ? 'd-none' : 'hidden' }}') : null"
        {{
            $attributes->merge([
                    'wire:key' => $tableName.'-row-detail-'.$row->{$primaryKey},
                ])
                ->merge($customAttributes)
                ->class([
                    'hidden bg-white dark:bg-gray-700 dark:text-white' => $isTailwind && ($customAttributes['default'] ?? true) && $rowIndex % 2 === 0,
                    'hidden bg-gray-50 dark:bg-gray-800 dark:text-white' => $isTailwind && ($customAttributes['default'] ?? true) && $rowIndex % 2 !== 0,
                    'd-none bg-light' => $isBootstrap && $rowIndex % 2 === 0 && ($customAttributes['default'] ?? true),
                    'd-none bg-white' => $isBootstrap && $rowIndex % 2 !== 0 && ($customAttributes['default'] ?? true),
                ])
                ->except(['default', 'default-styling', 'default-colors'])
        }}
    >
        <td colspan="{{ $this->getColspanCount }}" @class([
            'px-6 py-4' => $isTailwind,
            'p-3' => $isBootstrap,
        ])>
            @include($this->getRowDetailView(), ['row' => $row, 'rowIndex' => $rowIndex])
        </td>
    </tr>
@endif
