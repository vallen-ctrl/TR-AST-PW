@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block rounded-lg border-2 border-black bg-neo-lime px-3 py-2 font-hand text-base text-black shadow-[2px_2px_0_0_#000]'
            : 'block rounded-lg px-3 py-2 font-hand text-base text-black hover:bg-neo-lime focus:outline-none focus:bg-neo-lime';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
