@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'placeholder' => null,
    'value' => null,
    'rows' => 4,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'error' => null,
    'helper' => null,
])

@php
    $textareaId = $id ?? $name ?? 'textarea-'.uniqid();
    $errorMessage = $error ?: ($name ? $errors->get($name) : null);
    $errorMessage = is_array($errorMessage) ? ($errorMessage[0] ?? null) : $errorMessage;

    $describedBy = array_filter([
        $errorMessage ? "{$textareaId}-error" : null,
        $helper ? "{$textareaId}-helper" : null,
    ]);
@endphp

<div class="input-group {{ $attributes->get('class') ?? '' }}">
    @if($label)
        <label for="{{ $textareaId }}" class="label {{ $attributes->get('label-class') ?? '' }}">
            {{ $label }}
            @if($required)
                <span class="text-accent-600 ml-1" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <textarea
        id="{{ $textareaId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($errorMessage) aria-invalid="true" @endif
        @if($describedBy) aria-describedby="{{ implode(' ', $describedBy) }}" @endif
        class="input {{ $errorMessage ? 'input-error' : '' }} {{ $attributes->get('input-class') ?? '' }}"
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($required) required @endif
        {{ $attributes->except(['class', 'label-class', 'input-class'])->toHtml() }}
    >{{ $value ?? old($name) }}</textarea>

    @if($errorMessage)
        <p id="{{ $textareaId }}-error" class="error-text" role="alert">{{ $errorMessage }}</p>
    @endif

    @if($helper)
        <p id="{{ $textareaId }}-helper" class="helper-text">{{ $helper }}</p>
    @endif
</div>
