@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-xl border-2 border-black bg-neo-lime px-4 py-3 font-hand text-sm text-black shadow-[3px_3px_0_0_#000]']) }}>
        ✓ {{ $status }}
    </div>
@endif
