<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FileUpload extends Component
{
    public function __construct(
        public ?string $name = null,
        public ?string $id = null,
        public ?string $label = null,
        public ?string $helper = null,
        public bool $multiple = false,
        public array $acceptedTypes = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'],
        public int $maxSizeMB = 10,
        public ?string $error = null,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.forms.file-upload');
    }

    public function uploadId(): string
    {
        return $this->id ?? $this->name ?? 'file-upload-'.uniqid();
    }

    public function acceptString(): string
    {
        $mimeMap = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        return implode(',', array_map(fn ($ext) => $mimeMap[$ext] ?? ".{$ext}", $this->acceptedTypes));
    }
}
