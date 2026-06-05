@props([
    'title' => 'Title',
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6']) }}>
    <div>
        <h1 class="text-2xl font-extrabold text-slate-100 tracking-tight flex items-center gap-2">
            {{ $title }}
        </h1>
        @if($subtitle)
            <p class="text-sm text-slate-400 mt-1 font-medium">{{ $subtitle }}</p>
        @endif
    </div>
    @if(isset($actions))
        <div class="flex items-center gap-3 flex-wrap">
            {{ $actions }}
        </div>
    @endif
</div>
