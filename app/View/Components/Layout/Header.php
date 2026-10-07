<?php

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    public function __construct(
        public bool $transparent = false,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.layout.header');
    }
}
