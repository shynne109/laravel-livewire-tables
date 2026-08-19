<?php

namespace Rappasoft\LaravelLivewireTables;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\Traits\HasAllTraits;

abstract class DataTableComponent extends Component
{
    use HasAllTraits;

    /**
     * Runs on every request, immediately after the component is instantiated, but before any other lifecycle methods are called
     * Called when refreshDatatable is called as an event
     */
    #[On('refreshDatatable')]
    public function boot(): void
    {
        //
    }

    /**
     * Runs on every request, after the component is mounted or hydrated, but before any update methods are called
     */
    public function booted(): void {}

    public function render(): Application|Factory|View
    {
        return view('livewire-tables::datatable');
    }

    /**
     * Returns a placeholder view for Livewire lazy loading support.
     * Uses the configured lazy placeholder view if set, otherwise falls back to the default.
     * The placeholder always carries the Alpine fallback scope to prevent ReferenceErrors
     * when Alpine initialises before the real table is morphed in.
     * Override this method in your table component to provide a fully custom placeholder.
     */
    public function placeholder()
    {
        $content = null;

        if ($this->hasLazyPlaceholderEnabled()) {
            $content = $this->hasLazyPlaceholderView()
                ? $this->getLazyPlaceholderView()
                : 'livewire-tables::lazy-placeholder-content';
        }

        return view('livewire-tables::lazy-placeholder', [
            'lazyPlaceholderContent' => $content,
            'alpineDefaultScope' => $this->getAlpineDefaultScope(),
            'isTailwind' => $this->isTailwind,
            'isBootstrap' => $this->isBootstrap,
            'isBootstrap4' => $this->isBootstrap4,
            'isBootstrap5' => $this->isBootstrap5,
        ]);
    }
}
