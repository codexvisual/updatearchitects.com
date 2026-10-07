@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'error-text space-y-1 list-none p-0']) }} role="alert">
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
