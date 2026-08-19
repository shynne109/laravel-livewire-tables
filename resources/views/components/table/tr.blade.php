@aware([ 'tableName','primaryKey','isTailwind','isBootstrap'])
@props(['row', 'rowIndex'])

@php
    $customAttributes = $this->getTrAttributes($row, $rowIndex);
    $hasExpandable = $this->rowExpandableIsEnabled && $this->isRowExpandableVisible($row);
    $rowPk = $row->{$primaryKey};
@endphp

<tr
    rowpk='{{ $rowPk }}'
    x-data="{ rowRefreshing: false{{ $hasExpandable ? ', rowExpandable: false' : '' }} }"
    @if($hasExpandable)
        x-init="$watch('rowExpandable', value => $dispatch('toggle-row-expandable', {'tableName': '{{ $tableName }}', 'row': {{ $rowIndex }}}))"
    @endif
    x-on:refreshing-row.window="if ($event.detail.id == '{{ $rowPk }}' && (!$event.detail.tableName || $event.detail.tableName === '{{ $tableName }}')) rowRefreshing = true"
    x-on:row-refreshed.window="if ($event.detail.id == '{{ $rowPk }}' && (!$event.detail.tableName || $event.detail.tableName === '{{ $tableName }}')) rowRefreshing = false"
    x-on:rows-refreshed.window="if ($event.detail.ids && $event.detail.ids.some(id => id == '{{ $rowPk }}') && (!$event.detail.tableName || $event.detail.tableName === '{{ $tableName }}')) rowRefreshing = false"
    x-on:dragstart.self="currentlyReorderingStatus && dragStart(event)"
    x-on:drop.prevent="currentlyReorderingStatus && dropEvent(event)"
    x-on:dragover.prevent.throttle.500ms="currentlyReorderingStatus && dragOverEvent(event)"
    x-on:dragleave.prevent.throttle.500ms="currentlyReorderingStatus && dragLeaveEvent(event)"
    @if($this->hasDisplayLoadingPlaceholder())
        wire:loading.class.add="hidden d-none"
    @else
        wire:loading.class.delay="opacity-50 dark:bg-gray-900 dark:opacity-60"
    @endif
    id="{{ $tableName }}-row-{{ $rowPk }}"
    :draggable="currentlyReorderingStatus"
    wire:key="{{ $tableName }}-tablerow-tr-{{ $rowPk }}"
    loopType="{{ ($rowIndex % 2 === 0) ? 'even' : 'odd' }}"
    @if($this->rowExpandableIsEnabled && $this->rowExpandableRowClickIsEnabled && $this->isRowExpandableVisible($row))
        x-on:click="rowExpandable = !rowExpandable"
    @endif
    :class="{ 'opacity-50 pointer-events-none': rowRefreshing }"
    {{
        $attributes->merge($customAttributes)
                ->class([
                    'transition-opacity duration-150' => $isTailwind,
                    'bg-white dark:bg-gray-700 dark:text-white rappasoft-striped-row' => ($isTailwind && ($customAttributes['default'] ?? true) && $rowIndex % 2 === 0),
                    'bg-gray-50 dark:bg-gray-800 dark:text-white rappasoft-striped-row' => ($isTailwind && ($customAttributes['default'] ?? true) && $rowIndex % 2 !== 0),
                    'cursor-pointer' => ($isTailwind && ($this->hasTableRowUrl() || ($this->rowExpandableIsEnabled && $this->rowExpandableRowClickIsEnabled)) && ($customAttributes['default'] ?? true)),
                    'bg-light rappasoft-striped-row' => ($isBootstrap && $rowIndex % 2 === 0 && ($customAttributes['default'] ?? true)),
                    'bg-white rappasoft-striped-row' => ($isBootstrap && $rowIndex % 2 !== 0 && ($customAttributes['default'] ?? true)),
                ])
                ->except(['default','default-styling','default-colors'])
    }}

>
    {{ $slot }}
</tr>
