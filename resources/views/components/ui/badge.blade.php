@props([
    'variant' => 'info', // success|warning|danger|info|neutral|purple|cyan|gold
    'size'    => 'sm',     // sm|md
])

@php
    $variants = [
        'success' => 'bg-emerald-950/40 text-emerald-400 border border-emerald-500/20 shadow-[0_0_10px_rgba(16,185,129,0.1)]',
        'warning' => 'bg-amber-950/40 text-amber-400 border border-amber-500/20 shadow-[0_0_10px_rgba(245,158,11,0.1)]',
        'danger'  => 'bg-rose-950/40 text-rose-400 border border-rose-500/20 shadow-[0_0_10px_rgba(244,63,94,0.1)]',
        'info'    => 'bg-blue-950/40 text-blue-400 border border-blue-500/20 shadow-[0_0_10px_rgba(59,130,246,0.1)]',
        'neutral' => 'bg-slate-900/60 text-slate-400 border border-slate-700/60',
        'purple'  => 'bg-purple-950/40 text-purple-400 border border-purple-500/20 shadow-[0_0_10px_rgba(168,85,247,0.1)]',
        'cyan'    => 'bg-cyan-950/40 text-cyan-400 border border-cyan-500/20 shadow-[0_0_10px_rgba(6,182,212,0.1)]',
        'gold'    => 'bg-amber-950/50 text-amber-400 border border-amber-500/30 shadow-[0_0_10px_rgba(245,158,11,0.15)]',
    ];
    
    $sizes = [
        'sm' => 'px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-md border',
        'md' => 'px-2.5 py-1 text-xs font-bold uppercase tracking-wider rounded-md border',
    ];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center justify-center {$variants[$variant]} {$sizes[$size]}"]) }}>
    {{ $slot }}
</span>
