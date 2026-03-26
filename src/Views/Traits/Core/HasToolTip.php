<?php

namespace Rappasoft\LaravelLivewireTables\Views\Traits\Core;

use Illuminate\View\ComponentAttributeBag;

trait HasToolTip
{
    public ?string $toolTipIcon = null;

    public ?string $toolTipTitle = null;

    public array $toolTipAttributes = ['class' => '', 'default-styling' => true];

    public function setToolTip(string $icon, string $title): self
    {
        $this->toolTipIcon = $icon;
        $this->toolTipTitle = $title;

        return $this;
    }

    public function hasToolTip(): bool
    {
        return isset($this->toolTipIcon) && isset($this->toolTipTitle);
    }

    public function getToolTipIcon(): string
    {
        return $this->toolTipIcon ?? '';
    }

    public function getToolTipTitle(): string
    {
        return $this->toolTipTitle ?? '';
    }

    public function setToolTipAttributes(array $toolTipAttributes): self
    {
        $this->toolTipAttributes = [...$this->toolTipAttributes, ...$toolTipAttributes];

        return $this;
    }

    public function getToolTipAttributes(): ComponentAttributeBag
    {
        return new ComponentAttributeBag([...['class' => '', 'default-styling' => true], ...$this->toolTipAttributes]);
    }
}
