<button {{ $attributes->merge(['type' => 'submit', 'class' => 'neo-btn neo-btn-danger']) }}>
    {{ $slot }}
</button>
