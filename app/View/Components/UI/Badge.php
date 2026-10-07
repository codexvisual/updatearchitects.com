<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Badge extends Component
{
    public function __construct(
        public string $variant = 'primary',
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.ui.badge');
    }

    public function classes(): string
    {
        $base = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-overline font-medium';

        $variants = [
            'primary' => 'bg-stone-900 text-white dark:bg-white dark:text-stone-900',
            'secondary' => 'bg-stone-100 text-stone-700 dark:bg-stone-800 dark:text-stone-300',
            'outline' => 'border border-stone-300 text-stone-700 dark:border-stone-600 dark:text-stone-300',
            'accent' => 'bg-accent-100 text-accent-800 dark:bg-accent-900/30 dark:text-accent-300',
            'success' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
            'warning' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
        ];

        $variant = $variants[$this->variant] ?? $variants['secondary'];

        return trim("$base $variant");
    }
}
