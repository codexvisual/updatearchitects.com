<?php

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppLayout extends Component
{
    public function __construct(
        public string $title = '',
        public string $description = '',
        public ?string $canonicalUrl = null,
        public ?string $ogImage = null,
        public array $schema = [],
        public bool $noIndex = false,
        public bool $noFollow = false,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.layout.app');
    }
}
