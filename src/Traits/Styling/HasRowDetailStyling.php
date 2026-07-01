<?php

namespace Rappasoft\LaravelLivewireTables\Traits\Styling;

use Livewire\Attributes\Computed;

trait HasRowDetailStyling
{
    /** @var array<mixed> */
    protected array $rowDetailButtonExpandAttributes = ['default-styling' => true, 'default-colors' => true];

    /** @var array<mixed> */
    protected array $rowDetailButtonCollapseAttributes = ['default-styling' => true, 'default-colors' => true];

    /**
     * @param  array<mixed>  $rowDetailButtonExpandAttributes
     */
    public function setRowDetailButtonExpandAttributes(array $rowDetailButtonExpandAttributes): self
    {
        $this->rowDetailButtonExpandAttributes = [...['default-colors' => false, 'default-styling' => false], ...$rowDetailButtonExpandAttributes];

        return $this;
    }

    /**
     * @param  array<mixed>  $rowDetailButtonCollapseAttributes
     */
    public function setRowDetailButtonCollapseAttributes(array $rowDetailButtonCollapseAttributes): self
    {
        $this->rowDetailButtonCollapseAttributes = [...['default-colors' => false, 'default-styling' => false], ...$rowDetailButtonCollapseAttributes];

        return $this;
    }

    /**
     * @return array<mixed>
     */
    #[Computed]
    public function getRowDetailButtonExpandAttributes(): array
    {
        return [...['default-styling' => true, 'default-colors' => true], ...$this->rowDetailButtonExpandAttributes];
    }

    /**
     * @return array<mixed>
     */
    #[Computed]
    public function getRowDetailButtonCollapseAttributes(): array
    {
        return [...['default-styling' => true, 'default-colors' => true], ...$this->rowDetailButtonCollapseAttributes];
    }
}
