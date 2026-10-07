@props([
    'type' => 'text',
    'name' => null,
    'id' => null,
    'label' => null,
    'placeholder' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'error' => null,
    'helper' => null,
])

@php
    $inputId = $id ?? $name ?? 'input-'.uniqid();
    $errorMessage = $error ?: ($name ? $errors->get($name) : null);
    $errorMessage = is_array($errorMessage) ? ($errorMessage[0] ?? null) : $errorMessage;

    $describedBy = array_filter([
        $errorMessage ? "{$inputId}-error" : null,
        $helper ? "{$inputId}-helper" : null,
    ]);
@endphp

<div class="input-group {{ $attributes->get('class') ?? '' }}">
    @if($label)
        <label for="{{ $inputId }}" class="label {{ $attributes->get('label-class') ?? '' }}">
            {{ $label }}
            @if($required)
                <span class="text-accent-600 ml-1" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        id="{{ $inputId }}"
        name="{{ $name }}"
        value="{{ $value ?? old($name) }}"
        placeholder="{{ $placeholder }}"
        @if($errorMessage) aria-invalid="true" @endif
        @if($describedBy) aria-describedby="{{ implode(' ', $describedBy) }}" @endif
        class="input {{ $errorMessage ? 'input-error' : '' }} {{ $attributes->get('input-class') ?? '' }}"
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($required) required @endif
        {{ $attributes->except(['class', 'label-class', 'input-class'])->toHtml() }}
    />

    @if($errorMessage)
        <p id="{{ $inputId }}-error" class="error-text" role="alert">{{ $errorMessage }}</p>
    @endif

    @if($helper)
        <p id="{{ $inputId }}-helper" class="helper-text">{{ $helper }}</p>
    @endif
</div>
