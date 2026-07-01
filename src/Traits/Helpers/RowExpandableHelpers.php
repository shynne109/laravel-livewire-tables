<?php

namespace Rappasoft\LaravelLivewireTables\Traits\Helpers;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;

trait RowExpandableHelpers
{
    public function getRowExpandableStatus(): bool
    {
        return $this->rowExpandableStatus;
    }

    #[Computed]
    public function rowExpandableIsEnabled(): bool
    {
        return $this->getRowExpandableStatus() === true;
    }

    #[Computed]
    public function rowExpandableIsDisabled(): bool
    {
        return $this->getRowExpandableStatus() === false;
    }

    public function getRowExpandableView(): string
    {
        return $this->rowExpandableView;
    }

    public function hasRowExpandableView(): bool
    {
        return isset($this->rowExpandableView) && $this->rowExpandableView !== null;
    }

    #[Computed]
    public function rowExpandableRowClickIsEnabled(): bool
    {
        return $this->rowExpandableRowClickEnabled;
    }

    public function isRowExpandableVisible(Model $row): bool
    {
        if ($this->rowExpandableVisibleCallback instanceof Closure) {
            return call_user_func($this->rowExpandableVisibleCallback, $row);
        }

        return true;
    }

    public function toggleRowExpandable(string $rowPk): void
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
