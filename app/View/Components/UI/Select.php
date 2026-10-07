<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public ?string $label = null,
        public array $options = [],
        public ?string $placeholder = 'Select an option',
        public ?string $value = null,
        public bool $required = false,
        public bool $disabled = false,
        public ?string $error = null,
        public ?string $helper = null,
        public bool $multiple = false,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.ui.select');
    }

    public function selectId(): string
    {
        return $this->id ?? $this->name ?? 'select-'.uniqid();
    }
}
