<?php

namespace Rappasoft\LaravelLivewireTables\Traits\Helpers;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;

trait RowDetailHelpers
{
    public function getRowDetailStatus(): bool
    {
        return $this->rowDetailStatus;
    }

    #[Computed]
    public function rowDetailIsEnabled(): bool
    {
        return $this->getRowDetailStatus() === true;
    }

    #[Computed]
    public function rowDetailIsDisabled(): bool
    {
        return $this->getRowDetailStatus() === false;
    }

    public function getRowDetailView(): string
    {
        return $this->rowDetailView;
    }

    public function hasRowDetailView(): bool
    {
        return isset($this->rowDetailView) && $this->rowDetailView !== null;
    }

    public function getRowDetailTrigger(): string
    {
        return $this->rowDetailTrigger;
    }

    #[Computed]
    public function rowDetailTriggerIsButton(): bool
    {
        return $this->getRowDetailTrigger() === 'button';
    }

    #[Computed]
    public function rowDetailTriggerIsRow(): bool
    {
        return $this->getRowDetailTrigger() === 'row';
    }

    #[Computed]
    public function rowDetailTriggerIsNone(): bool
    {
        return $this->getRowDetailTrigger() === 'none';
    }

    public function isRowDetailVisible(Model $row): bool
    {
        if ($this->rowDetailVisibleCallback instanceof Closure) {
            return call_user_func($this->rowDetailVisibleCallback, $row);
        }

        return true;
    }

    public function toggleRowDetail(string $rowPk): void
    {
        if (in_array($rowPk, $this->expandedRows, true)) {
            $this->expandedRows = array_values(array_diff($this->expandedRows, [$rowPk]));
        } else {
            $this->expandedRows[] = $rowPk;
        }
    }

    public function isRowExpanded(string $rowPk): bool
    {
        return in_array($rowPk, $this->expandedRows, true);
    }
}
