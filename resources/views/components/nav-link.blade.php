@props(['active'])

@php
$classes = ($active ?? false)
            ? 'neo-nav-link neo-nav-link-active'
            : 'neo-nav-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
