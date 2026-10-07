<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public function __construct(
        public string $variant = 'default',
        public ?string $href = null,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.ui.card');
    }

    public function classes(): string
    {
        // One silhouette for every card - radius, softened border and rest
        // elevation are shared, so a variant only ever decides how the card
        // behaves when it moves. Premium's distinguishing feature was never
        // its outline (it differed only in radius and border strength from the
        // default) but the sheen it carries, which comes from the class list in
        // the component rather than here.
        $base = 'rounded-xl border border-stone-200/70 bg-white dark:border-stone-800/70 dark:bg-stone-900';

        $variants = [
            'default' => 'shadow-soft transition-shadow duration-300',
            'hover' => 'shadow-soft transition-all duration-300 hover:-translate-y-1 hover:shadow-elevated hover:border-accent-300 dark:hover:border-accent-800',
            'premium' => 'card-sheen shadow-soft transition-all duration-300 hover:-translate-y-1.5 hover:shadow-elevated hover:border-accent-300 dark:hover:border-accent-800',
            'elevated' => 'shadow-elevated',
            'flat' => '',
        ];

        $variant = $variants[$this->variant] ?? $variants['default'];

        return trim("$base $variant");
    }

    public function isLink(): bool
    {
        return ! is_null($this->href);
    }
}
