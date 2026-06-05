@props([
    'variant' => 'neutral',
])

@php
    $variants = [
        'neutral' => 'bg-slate-100 text-slate-700 ring-slate-200 dark:bg-white/10 dark:text-slate-200 dark:ring-white/10',
        'primary' => 'bg-brand-100 text-brand-700 ring-brand-200 dark:bg-brand-500/15 dark:text-cyan-200 dark:ring-brand-400/20',
        'success' => 'bg-emerald-100 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/15 dark:text-emerald-200 dark:ring-emerald-400/20',
        'warning' => 'bg-amber-100 text-amber-800 ring-amber-200 dark:bg-amber-500/15 dark:text-amber-200 dark:ring-amber-400/20',
        'danger' => 'bg-red-100 text-red-700 ring-red-200 dark:bg-red-500/15 dark:text-red-200 dark:ring-red-400/20',
        'executive' => 'bg-executive-100 text-executive-600 ring-executive-400/30 dark:bg-executive-400/15 dark:text-executive-100 dark:ring-executive-400/20',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold ring-1 ring-inset ' . ($variants[$variant] ?? $variants['neutral'])]) }}>
    {{ $slot }}
</span>
