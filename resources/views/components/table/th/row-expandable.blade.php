@aware(['isTailwind', 'isBootstrap'])
@if ($this->rowExpandableIsEnabled)
    <th scope="col" wire:key="{{ $this->getTableName }}-th-row-expandable" {{
        $attributes->merge()
            ->class([
                'w-12 dark:bg-gray-800' => $isTailwind,
                '' => $isBootstrap,
            ])
    }}></th>
@endif
