@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-2 space-y-1 rounded-lg border-2 border-black bg-neo-blush px-3 py-2 font-hand text-sm text-black']) }}>
        @foreach ((array) $messages as $message)
            <li>⚠ {{ $message }}</li>
        @endforeach
    </ul>
@endif
