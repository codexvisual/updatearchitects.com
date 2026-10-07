@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'helper' => null,
    'multiple' => false,
    'acceptedTypes' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'],
    'maxSizeMB' => 10,
    'error' => null,
])

@php
    $baseName = $name !== null && str_ends_with($name, '[]') ? substr($name, 0, -2) : $name;
    $uploadId = $id ?? $baseName ?? 'file-upload-'.uniqid();
    $accept = implode(',', array_map(fn ($ext) => ".{$ext}", $acceptedTypes));
    $nameAttr = $multiple ? $baseName.'[]' : $baseName;
    $errorMessage = $error ?: ($baseName ? $errors->get($baseName) : null);
    $errorMessage = is_array($errorMessage) ? ($errorMessage[0] ?? null) : $errorMessage;
@endphp

<div class="input-group {{ $attributes->get('class') ?? '' }}" x-data="fileUpload">
    @if($label)
        <label for="{{ $uploadId }}" class="label {{ $attributes->get('label-class') ?? '' }}">
            {{ $label }}
            @if($attributes->get('required'))
                <span class="text-accent-600 ml-1" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div
        class="relative border-2 border-dashed border-stone-300 rounded-lg p-6 text-center transition-colors hover:border-stone-400 dark:border-stone-700 dark:hover:border-stone-600"
        @drop.prevent="onDrop($event)"
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        :class="{ 'border-accent-500 bg-accent-50 dark:bg-accent-900/20': dragging }"
    >
        <input
            type="file"
            id="{{ $uploadId }}"
            name="{{ $nameAttr }}"
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
            @change="onChange($event)"
            @if($multiple) multiple @endif
            accept="{{ $accept }}"
            aria-describedby="{{ $uploadId }}-helper"
        >

        <div class="flex flex-col items-center gap-3 pointer-events-none">
            <svg class="w-12 h-12 text-stone-400 dark:text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            <div>
                <p class="text-body font-medium text-stone-900 dark:text-white">Drag &amp; drop files here, or click to browse</p>
                <p class="text-caption text-stone-500 dark:text-stone-400 mt-1">
                    Accepted: {{ collect($acceptedTypes)->map(fn ($ext) => strtoupper($ext))->join(', ') }} · Max {{ $maxSizeMB }} MB
                </p>
            </div>
        </div>
    </div>

    @if($helper)
        <p id="{{ $uploadId }}-helper" class="helper-text">{{ $helper }}</p>
    @endif

    @if($errorMessage)
        <p id="{{ $uploadId }}-error" class="error-text" role="alert">{{ $errorMessage }}</p>
    @endif

    {{-- File List Preview --}}
    <div x-show="files.length > 0" x-cloak x-transition class="mt-4 space-y-2" role="list" aria-label="Selected files">
        <template x-for="(file, index) in files" :key="file.name + index">
            <div class="flex items-center justify-between p-3 bg-stone-50 rounded-lg border border-stone-200 dark:bg-stone-800 dark:border-stone-700" role="listitem">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <svg class="w-6 h-6 text-stone-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6H18a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V4z"></path>
                    </svg>
                    <div class="min-w-0">
                        <p class="text-body-sm font-medium text-stone-900 dark:text-white truncate" x-text="file.name"></p>
                        <p class="text-caption text-stone-500 dark:text-stone-400" x-text="formatFileSize(file.size)"></p>
                    </div>
                </div>
                <button
                    type="button"
                    class="btn-ghost p-1.5 text-stone-500 hover:text-accent-600 dark:text-stone-400 dark:hover:text-accent-400"
                    @click="removeFile(index)"
                    aria-label="Remove file"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </template>
    </div>

    @error('files.*')
        <p class="error-text" role="alert">{{ $message }}</p>
    @enderror
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('fileUpload', () => ({
            files: [],
            dragging: false,
            maxSizeBytes: {{ (int) $maxSizeMB * 1024 * 1024 }},
            allowedTypes: @js($acceptedTypes),

            openFileDialog() {
                this.$root.querySelector('input[type="file"]')?.click();
            },

            onDrop(event) {
                this.dragging = false;
                this.addFiles(Array.from(event.dataTransfer.files));
            },

            onChange(event) {
                this.addFiles(Array.from(event.target.files));
            },

            addFiles(newFiles) {
                newFiles.slice(0, 5).forEach((file) => {
                    if (this.validateFile(file)) {
                        this.files.push(file);
                    }
                });
            },

            validateFile(file) {
                if (file.size > this.maxSizeBytes) {
                    alert(`"${file.name}" is larger than the {{ $maxSizeMB }} MB limit.`);
                    return false;
                }

                const extension = file.name.split('.').pop()?.toLowerCase();

                if (!this.allowedTypes.includes(extension)) {
                    alert(`"${file.name}" is not an accepted file type.`);
                    return false;
                }

                return true;
            },

            removeFile(index) {
                this.files.splice(index, 1);
            },

            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },
        }));
    });
</script>
@endpush