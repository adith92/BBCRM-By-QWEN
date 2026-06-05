@props([
    'variant' => 'info',
    'icon' => null,
])

@php
    $variants = [
        'info' => ['class' => 'border-brand-200 bg-brand-50 text-brand-800 dark:border-brand-400/20 dark:bg-brand-500/10 dark:text-brand-100', 'icon' => 'info'],
        'success' => ['class' => 'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-400/20 dark:bg-emerald-500/10 dark:text-emerald-100', 'icon' => 'check_circle'],
        'warning' => ['class' => 'border-amber-200 bg-amber-50 text-amber-900 dark:border-amber-400/20 dark:bg-amber-500/10 dark:text-amber-100', 'icon' => 'warning'],
        'danger' => ['class' => 'border-red-200 bg-red-50 text-red-800 dark:border-red-400/20 dark:bg-red-500/10 dark:text-red-100', 'icon' => 'error'],
    ];
    $meta = $variants[$variant] ?? $variants['info'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-lg border px-4 py-3 text-sm font-medium ' . $meta['class']]) }}>
    <span class="material-symbols-outlined mt-0.5 text-[18px]">{{ $icon ?? $meta['icon'] }}</span>
    <div class="min-w-0">{{ $slot }}</div>
</div>
