@props([
    'title' => null,
    'subtitle' => null,
    'padding' => 'p-5',
])

<section {{ $attributes->merge(['class' => "command-panel {$padding}"]) }}>
    @if($title || $subtitle || isset($actions))
        <div class="mb-4 flex items-start justify-between gap-4">
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

    {{ $slot }}
</section>
