<button {{ $attributes->merge(['type' => 'submit', 'class' => 'neo-btn']) }}>
    {{ $slot }}
</button>
