@props([
    'label'       => 'Label',
    'value'       => '0',
    'trend'       => null,        // up|down|flat
    'trendValue'  => null,        // e.g. '18.4%'
    'trendLabel'  => null,        // e.g. 'new' or 'this week'
    'icon'        => 'analytics',
    'color'       => 'cyan',      // cyan|blue|green|amber|red|gold
])

@php
    $colorClasses = [
        'cyan'   => 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 shadow-[0_0_10px_rgba(6,182,212,0.15)]',
        'blue'   => 'bg-blue-500/10 text-blue-400 border border-blue-500/20 shadow-[0_0_10px_rgba(59,130,246,0.15)]',
        'green'  => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-[0_0_10px_rgba(16,185,129,0.15)]',
        'amber'  => 'bg-amber-500/10 text-amber-400 border border-amber-500/20 shadow-[0_0_10px_rgba(245,158,11,0.15)]',
        'red'    => 'bg-rose-500/10 text-rose-400 border border-rose-500/20 shadow-[0_0_10px_rgba(244,63,94,0.15)]',
        'gold'   => 'bg-amber-500/15 text-amber-400 border border-amber-500/30 shadow-[0_0_15px_rgba(245,158,11,0.2)]',
    ];
    
    $borderClasses = [
        'cyan'   => 'border-l-4 border-l-cyan-500 hover:border-cyan-500/50',
        'blue'   => 'border-l-4 border-l-blue-500 hover:border-blue-500/50',
        'green'  => 'border-l-4 border-l-emerald-500 hover:border-emerald-500/50',
        'amber'  => 'border-l-4 border-l-amber-500 hover:border-amber-500/50',
        'red'    => 'border-l-4 border-l-rose-500 hover:border-rose-500/50',
        'gold'   => 'border-l-4 border-l-amber-400 hover:border-amber-400/50',
    ];

    $trendColors = [
        'up'   => 'text-emerald-400',
        'down' => 'text-rose-400',
        'flat' => 'text-slate-400',
    ];

    $trendIcons = [
        'up'   => '▲',
        'down' => '▼',
        'flat' => '■',
    ];
@endphp

<div class="bg-slate-900/50 backdrop-blur-md rounded-2xl border border-slate-800/80 p-5 
            {{ $borderClasses[$color] }} hover:shadow-[0_0_20px_rgba(6,182,212,0.08)] transition-all duration-300">
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">{{ $label }}</p>
            <p class="text-2xl font-extrabold text-slate-100 mt-2 truncate">{{ $value }}</p>
            @if($trend)
                <div class="flex items-center gap-1.5 mt-2">
                    <span class="text-xs font-bold {{ $trendColors[$trend] }}">
                        {{ $trendIcons[$trend] }} {{ $trendValue }}
                    </span>
                    @if($trendLabel)
                        <span class="text-[10px] text-slate-500 font-medium">
                            {{ $trendLabel }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $colorClasses[$color] }} flex-shrink-0">
            <span class="material-symbols-outlined text-lg">{{ $icon }}</span>
        </div>
    </div>
</div>
