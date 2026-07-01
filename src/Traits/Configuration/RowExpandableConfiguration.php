<?php

namespace Rappasoft\LaravelLivewireTables\Traits\Configuration;

use Closure;

trait RowExpandableConfiguration
{
    public function setRowExpandableStatus(bool $status): self
    {
        $this->rowExpandableStatus = $status;

        return $this;
    }

    public function setRowExpandableEnabled(): self
    {
        $this->setRowExpandableStatus(true);

        return $this;
    }

    public function setRowExpandableDisabled(): self
    {
        $this->setRowExpandableStatus(false);

        return $this;
    }

    public function setRowExpandableView(string $view): self
    {
        $this->rowExpandableView = $view;

        return $this;
    }

    public function setRowExpandableRowClickEnabled(): self
    {
        $this->rowExpandableRowClickEnabled = true;

        return $this;
    }

    public function setRowExpandableRowClickDisabled(): self
    {
        $this->rowExpandableRowClickEnabled = false;

        return $this;
    }

    /**
     * @param  Closure(mixed $row): bool  $callback
     */
    public function setRowExpandableVisibleCallback(Closure $callback): self
    {
        $this->rowExpandableVisibleCallback = $callback;

        return $this;
    }
}
