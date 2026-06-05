@props([
    'label',
    'value',
    'trend' => null,
    'tone' => 'blue',
    'icon' => 'monitoring',
])

@php
    $tones = [
        'blue' => 'from-brand-500/18 to-cyan-400/10 text-brand-600 dark:text-cyan-300',
        'gold' => 'from-executive-400/25 to-amber-300/10 text-executive-600 dark:text-amber-300',
        'emerald' => 'from-emerald-400/20 to-teal-300/10 text-emerald-600 dark:text-emerald-300',
        'amber' => 'from-amber-400/25 to-orange-300/10 text-amber-600 dark:text-amber-300',
        'red' => 'from-red-400/20 to-rose-300/10 text-red-600 dark:text-red-300',
        'purple' => 'from-purple-400/20 to-indigo-300/10 text-purple-600 dark:text-purple-300',
    ];
    $toneClass = $tones[$tone] ?? $tones['blue'];
@endphp

<article {{ $attributes->merge(['class' => 'command-panel p-5']) }}>
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $label }}</p>
            <p class="mt-2 break-words text-2xl font-black text-slate-950 dark:text-white">{{ $value }}</p>
        </div>
        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-gradient-to-br {{ $toneClass }}">
            <span class="material-symbols-outlined text-[22px]">{{ $icon }}</span>
        </div>
    </div>
    @if($trend)
        <p class="mt-4 text-xs font-semibold {{ str_contains($trend, 'Urgent') || str_contains($trend, 'Attention') ? 'text-amber-600 dark:text-amber-300' : 'text-emerald-600 dark:text-emerald-300' }}">{{ $trend }}</p>
    @endif
</article>
