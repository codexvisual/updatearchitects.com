<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public function __construct(
        public string $type = 'text',
        public ?string $name = null,
        public ?string $id = null,
        public ?string $label = null,
        public ?string $placeholder = null,
        public ?string $value = null,
        public bool $required = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public ?string $error = null,
        public ?string $helper = null,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.ui.input');
    }

    public function inputId(): string
    {
        return $this->id ?? $this->name ?? 'input-'.uniqid();
    }
}
