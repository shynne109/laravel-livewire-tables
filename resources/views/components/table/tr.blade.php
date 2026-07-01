@aware([ 'tableName','primaryKey','isTailwind','isBootstrap'])
@props(['row', 'rowIndex'])

@php
    $customAttributes = $this->getTrAttributes($row, $rowIndex);
@endphp

<tr
    rowpk='{{ $row->{$primaryKey} }}'
    @if($this->rowDetailIsEnabled && $this->isRowDetailVisible($row))
        x-data="{ rowDetail: false }"
        x-init="$watch('rowDetail', value => $dispatch('toggle-row-detail', {'tableName': '{{ $tableName }}', 'row': {{ $rowIndex }}}))"
    @endif
    x-on:dragstart.self="currentlyReorderingStatus && dragStart(event)"
    x-on:drop.prevent="currentlyReorderingStatus && dropEvent(event)"
    x-on:dragover.prevent.throttle.500ms="currentlyReorderingStatus && dragOverEvent(event)"
    x-on:dragleave.prevent.throttle.500ms="currentlyReorderingStatus && dragLeaveEvent(event)"
    @if($this->hasDisplayLoadingPlaceholder())
        wire:loading.class.add="hidden d-none"
    @else
        wire:loading.class.delay="opacity-50 dark:bg-gray-900 dark:opacity-60"
    @endif
    id="{{ $tableName }}-row-{{ $row->{$primaryKey} }}"
    :draggable="currentlyReorderingStatus"
    wire:key="{{ $tableName }}-tablerow-tr-{{ $row->{$primaryKey} }}"
    loopType="{{ ($rowIndex % 2 === 0) ? 'even' : 'odd' }}"
    @if($this->rowDetailIsEnabled && $this->rowDetailTriggerIsRow && $this->isRowDetailVisible($row))
        x-on:click="rowDetail = !rowDetail"
    @endif
    {{
        $attributes->merge($customAttributes)
                ->class([
                    'bg-white dark:bg-gray-700 dark:text-white rappasoft-striped-row' => ($isTailwind && ($customAttributes['default'] ?? true) && $rowIndex % 2 === 0),
                    'bg-gray-50 dark:bg-gray-800 dark:text-white rappasoft-striped-row' => ($isTailwind && ($customAttributes['default'] ?? true) && $rowIndex % 2 !== 0),
                    'cursor-pointer' => ($isTailwind && ($this->hasTableRowUrl() || ($this->rowDetailIsEnabled && $this->rowDetailTriggerIsRow)) && ($customAttributes['default'] ?? true)),
                    'bg-light rappasoft-striped-row' => ($isBootstrap && $rowIndex % 2 === 0 && ($customAttributes['default'] ?? true)),
                    'bg-white rappasoft-striped-row' => ($isBootstrap && $rowIndex % 2 !== 0 && ($customAttributes['default'] ?? true)),
                ])
                ->except(['default','default-styling','default-colors'])
    }}

>
    {{ $slot }}
</tr>
