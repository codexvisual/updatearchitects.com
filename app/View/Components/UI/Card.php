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
        $base = 'rounded-lg border border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900';

        $variants = [
            'default' => 'shadow-soft',
            'hover' => 'shadow-soft transition-all duration-300 hover:-translate-y-1 hover:shadow-card hover:border-accent-300 dark:hover:border-accent-800',
            'premium' => 'card-sheen rounded-xl border border-stone-200/80 bg-white shadow-soft transition-all duration-300 hover:-translate-y-1.5 hover:shadow-elevated hover:border-accent-300 dark:border-stone-800/80 dark:bg-stone-900 dark:hover:border-accent-800',
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
