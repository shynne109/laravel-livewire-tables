<?php

namespace Rappasoft\LaravelLivewireTables\Traits\Styling;

use Livewire\Attributes\Computed;

trait HasRowExpandableStyling
{
    /** @var array<mixed> */
    protected array $rowExpandableButtonExpandAttributes = ['default-styling' => true, 'default-colors' => true];

    /** @var array<mixed> */
    protected array $rowExpandableButtonCollapseAttributes = ['default-styling' => true, 'default-colors' => true];

    /**
     * @param  array<mixed>  $rowExpandableButtonExpandAttributes
     */
    public function setRowExpandableButtonExpandAttributes(array $rowExpandableButtonExpandAttributes): self
    {
        $this->rowExpandableButtonExpandAttributes = [...['default-colors' => false, 'default-styling' => false], ...$rowExpandableButtonExpandAttributes];

        return $this;
    }

    /**
     * @param  array<mixed>  $rowExpandableButtonCollapseAttributes
     */
    public function setRowExpandableButtonCollapseAttributes(array $rowExpandableButtonCollapseAttributes): self
    {
        $this->rowExpandableButtonCollapseAttributes = [...['default-colors' => false, 'default-styling' => false], ...$rowExpandableButtonCollapseAttributes];

        return $this;
    }

    /**
     * @return array<mixed>
     */
    #[Computed]
    public function getRowExpandableButtonExpandAttributes(): array
    {
        return [...['default-styling' => true, 'default-colors' => true], ...$this->rowExpandableButtonExpandAttributes];
    }

    /**
     * @return array<mixed>
     */
    #[Computed]
    public function getRowExpandableButtonCollapseAttributes(): array
    {
        return [...['default-styling' => true, 'default-colors' => true], ...$this->rowExpandableButtonCollapseAttributes];
    }
}
