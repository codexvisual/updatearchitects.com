<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    public function __construct(
        public string $variant = 'primary',
        public string $size = 'md',
        public string $type = 'button',
        public bool $disabled = false,
        public ?string $href = null,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.ui.button');
    }

    public function classes(): string
    {
        $base = 'inline-flex items-center justify-center gap-2 rounded-md font-medium transition-all duration-150 ease-out focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';

        $variants = [
            'primary' => 'bg-stone-900 text-white hover:bg-stone-900/90 active:bg-stone-900/[0.95] dark:bg-white dark:text-stone-900 dark:hover:bg-white/90',
            'secondary' => 'bg-stone-100 text-stone-900 hover:bg-stone-200 active:bg-stone-200/80 border border-stone-200 dark:bg-stone-800 dark:text-stone-100 dark:hover:bg-stone-700 dark:border-stone-700',
            'outline' => 'border-2 border-stone-900 text-stone-900 hover:bg-stone-900 hover:text-white dark:border-white dark:text-white dark:hover:bg-white dark:hover:text-stone-900',
            'ghost' => 'text-stone-700 hover:bg-stone-100 active:bg-stone-200/80 dark:text-stone-300 dark:hover:bg-stone-800',
            'accent' => 'bg-accent-600 text-white hover:bg-accent-700 active:bg-accent-800 border border-accent-600',
            'link' => 'p-0 text-stone-700 underline-offset-2 hover:underline dark:text-stone-300',
        ];

        $sizes = [
            'sm' => 'px-3 py-1.5 text-caption',
            'md' => 'px-5 py-3 text-body-sm',
            'lg' => 'px-8 py-4 text-body',
        ];

        $variant = $variants[$this->variant] ?? $variants['primary'];
        $size = $sizes[$this->size] ?? $sizes['md'];

        return trim("$base $variant $size");
    }

    public function isLink(): bool
    {
        return ! is_null($this->href);
    }
}
