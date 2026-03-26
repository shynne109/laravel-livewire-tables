<?php

namespace Rappasoft\LaravelLivewireTables\Tests\Unit\Views\Traits;

use PHPUnit\Framework\Attributes\Group;
use Rappasoft\LaravelLivewireTables\Tests\TestCase;
use Rappasoft\LaravelLivewireTables\Views\Column;

#[Group('Columns')]
final class HasToolTipTest extends TestCase
{
    public function test_can_set_tooltip_on_column(): void
    {
        $column = Column::make('Name', 'name')
            ->setToolTip('heroicon-o-question-mark-circle', 'This is a tooltip');

        $this->assertTrue($column->hasToolTip());
        $this->assertSame('heroicon-o-question-mark-circle', $column->getToolTipIcon());
        $this->assertSame('This is a tooltip', $column->getToolTipTitle());
    }

    public function test_column_has_no_tooltip_by_default(): void
    {
        $column = Column::make('Name', 'name');

        $this->assertFalse($column->hasToolTip());
    }

    public function test_can_set_tooltip_attributes(): void
    {
        $column = Column::make('Name', 'name')
            ->setToolTip('heroicon-o-question-mark-circle', 'This is a tooltip')
            ->setToolTipAttributes(['class' => 'my-custom-class']);

        $attributes = $column->getToolTipAttributes();

        $this->assertSame('my-custom-class', $attributes->get('class'));
    }

    public function test_tooltip_attributes_merge_with_defaults(): void
    {
        $column = Column::make('Name', 'name')
            ->setToolTip('heroicon-o-question-mark-circle', 'This is a tooltip')
            ->setToolTipAttributes(['custom' => 'value']);

        $attributes = $column->getToolTipAttributes();

        $this->assertTrue($attributes->get('default-styling'));
        $this->assertSame('value', $attributes->get('custom'));
    }

    public function test_tooltip_returns_empty_string_when_not_set(): void
    {
        $column = Column::make('Name', 'name');

        $this->assertSame('', $column->getToolTipIcon());
        $this->assertSame('', $column->getToolTipTitle());
    }

    public function test_set_tooltip_returns_column_for_chaining(): void
    {
        $column = Column::make('Name', 'name');
        $result = $column->setToolTip('heroicon-o-question-mark-circle', 'Tooltip text');

        $this->assertInstanceOf(Column::class, $result);
    }
}
