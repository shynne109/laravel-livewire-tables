@aware(['isTailwind', 'isBootstrap'])
@if ($this->rowDetailIsEnabled && $this->rowDetailTriggerIsButton)
    <th scope="col" wire:key="{{ $this->getTableName }}-th-row-detail" {{
        $attributes->merge()
            ->class([
                'w-12 dark:bg-gray-800' => $isTailwind,
                '' => $isBootstrap,
            ])
    }}></th>
@endif
