<?php

namespace Rappasoft\LaravelLivewireTables\Traits;

use Closure;
use Rappasoft\LaravelLivewireTables\Traits\Configuration\RowExpandableConfiguration;
use Rappasoft\LaravelLivewireTables\Traits\Helpers\RowExpandableHelpers;
use Rappasoft\LaravelLivewireTables\Traits\Styling\HasRowExpandableStyling;

trait WithRowExpandable
{
    use RowExpandableConfiguration,
        RowExpandableHelpers,
        HasRowExpandableStyling;

    protected bool $rowExpandableStatus = false;

    protected ?string $rowExpandableView = null;

    protected bool $rowExpandableRowClickEnabled = false;

    protected ?Closure $rowExpandableVisibleCallback = null;

    /** @var array<string> */
    public array $expandedRows = [];
}
