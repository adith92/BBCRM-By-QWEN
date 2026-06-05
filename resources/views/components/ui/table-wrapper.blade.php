@props([
    'title' => null,
    'subtitle' => null,
])

<section {{ $attributes->merge(['class' => 'command-panel overflow-hidden']) }}>
    @if($title || $subtitle || isset($actions))
        <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-5 py-4 dark:border-white/10">
            <div>
                @if($title)
                    <h3 class="text-sm font-bold uppercase tracking-wide text-slate-900 dark:text-white">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="shrink-0">{{ $actions }}</div>
            @endisset
        </div>
    @endif
    <div class="overflow-x-auto">
        {{ $slot }}
    </div>
</section>
