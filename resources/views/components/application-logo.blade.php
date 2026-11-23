@props(['class' => 'h-9 w-9'])

<svg {{ $attributes->merge(['class' => $class, 'viewBox' => '0 0 48 48', 'fill' => 'none', 'xmlns' => 'http://www.w3.org/2000/svg']) }}>
    <rect x="1.5" y="1.5" width="45" height="45" rx="11" fill="#fff" stroke="#000" stroke-width="3" />
    <path d="M11 20.5 24 14l13 6.5-13 6.5-13-6.5Z" fill="#B8FF00" stroke="#000" stroke-width="2.5" stroke-linejoin="round" />
    <path d="M15.5 23.5V30c0 2.5 3.8 4.5 8.5 4.5s8.5-2 8.5-4.5v-6.5" stroke="#000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
    <path d="M37 21v8" stroke="#000" stroke-width="2.5" stroke-linecap="round" />
</svg>
