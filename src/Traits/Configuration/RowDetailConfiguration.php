<?php

namespace Rappasoft\LaravelLivewireTables\Traits\Configuration;

use Closure;

trait RowDetailConfiguration
{
    public function setRowDetailStatus(bool $status): self
    {
        $this->rowDetailStatus = $status;

        return $this;
    }

    public function setRowDetailEnabled(): self
    {
        $this->setRowDetailStatus(true);

        return $this;
    }

    public function setRowDetailDisabled(): self
    {
        $this->setRowDetailStatus(false);

        return $this;
    }

    public function setRowDetailView(string $view): self
    {
        $this->rowDetailView = $view;

        return $this;
    }

    public function setRowDetailTrigger(string $trigger): self
    {
        $this->rowDetailTrigger = $trigger;

        return $this;
    }

    public function setRowDetailTriggerButton(): self
    {
        $this->rowDetailTrigger = 'button';

        return $this;
    }

    public function setRowDetailTriggerRow(): self
    {
        $this->rowDetailTrigger = 'row';

        return $this;
    }

    public function setRowDetailTriggerNone(): self
    {
        $this->rowDetailTrigger = 'none';

        return $this;
    }

    /**
     * @param  Closure(mixed $row): bool  $callback
     */
    public function setRowDetailVisibleCallback(Closure $callback): self
    {
        $this->rowDetailVisibleCallback = $callback;

        return $this;
    }
}
