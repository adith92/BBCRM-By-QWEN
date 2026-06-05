@props([
    'title',
    'subtitle' => null,
    'eyebrow' => null,
])

<header {{ $attributes->merge(['class' => 'flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between']) }}>
    <div class="max-w-3xl">
        @if($eyebrow)
            <p class="text-xs font-black uppercase tracking-[0.18em] text-brand-600 dark:text-cyan-300">{{ $eyebrow }}</p>
        @endif
        <h1 class="mt-2 text-2xl font-black tracking-normal text-slate-950 dark:text-white md:text-3xl">{{ $title }}</h1>
        @if($subtitle)
            <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $subtitle }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</header>
