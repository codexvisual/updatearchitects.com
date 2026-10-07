@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'options' => [],
    'placeholder' => 'Select an option',
    'value' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'helper' => null,
    'multiple' => false,
])

@php
    $selectId = $id ?? $name ?? 'select-'.uniqid();
    $errorMessage = $error ?: ($name ? $errors->get($name) : null);
    $errorMessage = is_array($errorMessage) ? ($errorMessage[0] ?? null) : $errorMessage;

    $selected = $value ?? old($name);

    $describedBy = array_filter([
        $errorMessage ? "{$selectId}-error" : null,
        $helper ? "{$selectId}-helper" : null,
    ]);

    $nameAttr = $multiple ? $name.'[]' : $name;
@endphp

<div class="input-group {{ $attributes->get('class') ?? '' }}">
    @if($label)
        <label for="{{ $selectId }}" class="label {{ $attributes->get('label-class') ?? '' }}">
            {{ $label }}
            @if($required)
                <span class="text-accent-600 ml-1" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <select
        id="{{ $selectId }}"
        name="{{ $nameAttr }}"
        @if($errorMessage) aria-invalid="true" @endif
        @if($describedBy) aria-describedby="{{ implode(' ', $describedBy) }}" @endif
        class="input {{ $errorMessage ? 'input-error' : '' }} {{ $attributes->get('input-class') ?? '' }}"
        @if($disabled) disabled @endif
        @if($required) required @endif
        @if($multiple) multiple @endif
        {{ $attributes->except(['class', 'label-class', 'input-class'])->toHtml() }}
    >
        @if($placeholder)
            <option value="" disabled @selected(empty($selected) && ! $multiple)>{{ $placeholder }}</option>
        @endif
        @foreach($options as $optionValue => $optionLabel)
            <option
                value="{{ $optionValue }}"
                @selected(is_array($selected) ? in_array((string) $optionValue, array_map('strval', $selected)) : (string) $selected === (string) $optionValue)
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @if($errorMessage)
        <p id="{{ $selectId }}-error" class="error-text" role="alert">{{ $errorMessage }}</p>
    @endif

    @if($helper)
        <p id="{{ $selectId }}-helper" class="helper-text">{{ $helper }}</p>
    @endif
</div>
