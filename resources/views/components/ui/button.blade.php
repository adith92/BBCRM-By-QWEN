@props([
    'variant' => 'primary', // primary|secondary|danger|ghost|link|accent-cyan|accent-gold
    'size'    => 'md',        // sm|md|lg
    'type'    => 'button',
    'href'    => null,
    'disabled' => false,
])

@php
    $variants = [
        'primary'   => 'bg-blue-600 hover:bg-blue-500 text-white border border-blue-500/20 shadow-[0_0_10px_rgba(37,99,235,0.2)] hover:shadow-[0_0_15px_rgba(37,99,235,0.4)]',
        'secondary' => 'bg-slate-900/60 hover:bg-slate-800/80 text-slate-300 border border-slate-700/80 hover:border-slate-600',
        'danger'    => 'bg-rose-950/40 hover:bg-rose-900/60 text-rose-400 border border-rose-800/40',
        'ghost'     => 'bg-transparent hover:bg-slate-800/40 text-slate-400 hover:text-slate-200 border border-transparent',
        'link'      => 'bg-transparent text-cyan-400 hover:text-cyan-300 border border-transparent underline-offset-4 hover:underline',
        'accent-cyan' => 'bg-cyan-950/40 hover:bg-cyan-900/60 text-cyan-400 border border-cyan-500/30 shadow-[0_0_10px_rgba(6,182,212,0.15)] hover:shadow-[0_0_15px_rgba(6,182,212,0.35)]',
        'accent-gold' => 'bg-amber-950/40 hover:bg-amber-900/60 text-amber-400 border border-amber-500/30 shadow-[0_0_10px_rgba(245,158,11,0.15)] hover:shadow-[0_0_15px_rgba(245,158,11,0.35)]',
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs rounded-md gap-1.5',
        'md' => 'px-4 py-2 text-sm rounded-lg gap-2',
        'lg' => 'px-6 py-2.5 text-base rounded-lg gap-2',
    ];
    
    $base = 'inline-flex items-center justify-center font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 disabled:opacity-50 disabled:cursor-not-allowed';
@endphp

@if($href && !$disabled)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}>
        {{ $slot }}
    </a>
@else
    <button 
        type="{{ $type }}" 
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}
    >
        {{ $slot }}
    </button>
@endif
