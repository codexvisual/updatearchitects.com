<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{
    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public ?string $label = null,
        public ?string $placeholder = null,
        public ?string $value = null,
        public int $rows = 4,
        public bool $required = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public ?string $error = null,
        public ?string $helper = null,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.ui.textarea');
    }

    public function textareaId(): string
    {
        return $this->id ?? $this->name ?? 'textarea-'.uniqid();
    }
}
