<?php

namespace Rappasoft\LaravelLivewireTables\Traits;

use Closure;
use Rappasoft\LaravelLivewireTables\Traits\Configuration\RowDetailConfiguration;
use Rappasoft\LaravelLivewireTables\Traits\Helpers\RowDetailHelpers;
use Rappasoft\LaravelLivewireTables\Traits\Styling\HasRowDetailStyling;

trait WithRowDetail
{
    use RowDetailConfiguration,
        RowDetailHelpers,
        HasRowDetailStyling;

    protected bool $rowDetailStatus = false;

    protected ?string $rowDetailView = null;

    protected string $rowDetailTrigger = 'button';

    protected ?Closure $rowDetailVisibleCallback = null;

    /** @var array<string> */
    public array $expandedRows = [];
}
