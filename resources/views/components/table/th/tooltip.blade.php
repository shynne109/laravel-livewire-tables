@aware(['isTailwind','isBootstrap'])
@props(['toolTipIcon' => '', 'toolTipTitle' => '', 'toolTipAttributes' => null])

@if($toolTipIcon && $toolTipTitle)
    <span title="{{ $toolTipTitle }}" {{
        $toolTipAttributes
            ->class([
                'ml-1 inline-flex items-center cursor-help' => $isTailwind && (($toolTipAttributes['default-styling'] ?? true) || ($toolTipAttributes['default'] ?? true)),
                'ms-1 d-inline-flex align-items-center' => $isBootstrap && (($toolTipAttributes['default-styling'] ?? true) || ($toolTipAttributes['default'] ?? true)),
            ])
            ->except(['default', 'default-styling'])
    }}>
        @svg($toolTipIcon, 'w-4 h-4 text-gray-400')
    </span>
@endif
