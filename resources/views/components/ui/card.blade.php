@props([
    'title' => null,
    'subtitle' => null,
    'padding' => 'md', // sm|md|lg|none
    'glowing' => false,
])

@php
    $paddings = [
        'none' => '',
        'sm'   => 'p-4',
        'md'   => 'p-6',
        'lg'   => 'p-8',
    ];
    
    $glowClass = $glowing 
        ? 'border-cyan-500/20 shadow-[0_0_20px_rgba(6,182,212,0.08)] hover:border-cyan-500/40 hover:shadow-[0_0_25px_rgba(6,182,212,0.15)]' 
        : 'border-slate-800/80 hover:border-slate-700/80 hover:shadow-[0_4px_20px_rgba(0,0,0,0.4)]';
@endphp

<div {{ $attributes->merge(['class' => "bg-slate-900/40 backdrop-blur-md rounded-2xl border transition-all duration-300 {$glowClass}"]) }}>
    @if($title || $subtitle || isset($actions))
        <div class="flex items-center justify-between border-b border-slate-800/80 px-6 py-4">
            <div>
                @if($title)
                    <h3 class="text-base font-bold text-slate-100">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-xs text-slate-400 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($actions))
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
    
    <div class="{{ $paddings[$padding] }}">
        {{ $slot }}
    </div>
</div>
