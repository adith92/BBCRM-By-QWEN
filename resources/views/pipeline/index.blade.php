@extends('layouts.app')

@section('header_title', 'Sales Pipeline')

@section('content')
<div
    x-data="{
        searchTerm: '',
        filterBySearch(title, clientName) {
            if (!this.searchTerm) return true;
            const q = this.searchTerm.toLowerCase();
            return title.toLowerCase().includes(q) || clientName.toLowerCase().includes(q);
        }
    }"
    class="p-4 md:p-6 space-y-6"
>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-100 tracking-wide font-outfit bg-clip-text bg-gradient-to-r from-slate-100 to-slate-300">Sales Pipeline</h1>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Kelola pipeline deal secara visual per tahap</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Search --}}
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-slate-500">search</span>
                <input
                    x-model="searchTerm"
                    type="text"
                    placeholder="Cari deal..."
                    class="pl-9 pr-4 py-2 text-sm bg-slate-950/60 border border-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500/80 focus:border-cyan-500/80 w-56 text-slate-200 placeholder-slate-500 transition-all duration-200"
                >
            </div>

            @if(auth()->user()->isSales() || auth()->user()->isManager() || auth()->user()->isGM() || auth()->user()->isDirector())
            <a href="{{ route('opportunities.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white text-sm font-semibold rounded-xl transition-all duration-200 cursor-pointer shadow-[0_0_15px_rgba(6,182,212,0.25)] hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Deal
            </a>
            @endif
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="flex items-start gap-3 p-4 bg-emerald-950/40 border border-emerald-800/40 rounded-xl text-emerald-400 text-sm shadow-[0_0_15px_rgba(16,185,129,0.05)]">
        <span class="material-symbols-outlined text-[20px] text-emerald-400 flex-shrink-0">check_circle</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Kanban Board --}}
    <div class="overflow-x-auto pb-4 -mx-4 md:-mx-6 px-4 md:px-6">
        <div class="flex gap-5 min-w-max">

            @php
            $stageConfig = [
                'prospecting' => [
                    'label'      => 'Prospekting',
                    'header_bg'  => 'bg-blue-950/40 border-blue-800/40 text-blue-400',
                    'badge_glow' => 'border-t-2 border-t-blue-500',
                    'card_border'=> 'border-l-blue-500',
                    'count_bg'   => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                ],
                'proposal' => [
                    'label'      => 'Proposal',
                    'header_bg'  => 'bg-amber-950/40 border-amber-800/40 text-amber-400',
                    'badge_glow' => 'border-t-2 border-t-amber-500',
                    'card_border'=> 'border-l-amber-500',
                    'count_bg'   => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                ],
                'negotiation' => [
                    'label'      => 'Negosiasi',
                    'header_bg'  => 'bg-orange-950/40 border-orange-800/40 text-orange-400',
                    'badge_glow' => 'border-t-2 border-t-orange-500',
                    'card_border'=> 'border-l-orange-500',
                    'count_bg'   => 'bg-orange-500/10 text-orange-400 border border-orange-500/20',
                ],
                'won' => [
                    'label'      => 'Menang',
                    'header_bg'  => 'bg-emerald-950/40 border-emerald-800/40 text-emerald-400',
                    'badge_glow' => 'border-t-2 border-t-emerald-500',
                    'card_border'=> 'border-l-emerald-500',
                    'count_bg'   => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                ],
                'lost' => [
                    'label'      => 'Kalah',
                    'header_bg'  => 'bg-rose-950/40 border-rose-800/40 text-rose-400',
                    'badge_glow' => 'border-t-2 border-t-rose-500',
                    'card_border'=> 'border-l-rose-500',
                    'count_bg'   => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
                ],
            ];
            @endphp

            @foreach($stages as $stage)
            @php
            $cfg   = $stageConfig[$stage];
            $col   = $kanban[$stage];
            $opps  = $col['opportunities'];
            $count = $col['count'];
            $total = $col['total_value'];
            @endphp

            {{-- Kanban Column --}}
            <div class="w-80 bg-[#0b0c10]/40 border border-slate-800/40 rounded-2xl p-4 flex flex-col gap-4 flex-shrink-0 max-h-[calc(100vh-190px)] min-h-[450px]">

                {{-- Column Header --}}
                <div class="{{ $cfg['header_bg'] }} {{ $cfg['badge_glow'] }} rounded-xl p-3.5 border shadow-sm">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs tracking-wider uppercase font-outfit">{{ $cfg['label'] }}</span>
                        <span class="{{ $cfg['count_bg'] }} text-[10px] font-black px-2 py-0.5 rounded-md">{{ $count }}</span>
                    </div>
                    <div class="mt-1.5 text-sm font-extrabold text-slate-100 font-mono">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </div>
                </div>

                {{-- Cards Container - Scrollable individually with dynamic size --}}
                <div class="flex-grow flex flex-col gap-3 overflow-y-auto pr-1 custom-scrollbar min-h-0">
                    @forelse($opps as $opp)
                    <div
                        x-show="filterBySearch('{{ addslashes($opp->title) }}', '{{ addslashes($opp->client->company_name ?? '') }}')"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="bg-[#0f111a]/90 hover:bg-[#131622]/90 border border-slate-800/80 border-l-4 {{ $cfg['card_border'] }} rounded-xl p-4 shadow-sm hover:shadow-[0_0_15px_rgba(6,182,212,0.12)] hover:border-slate-700/80 hover:-translate-y-[1px] transition-all duration-200 cursor-pointer group text-left"
                    >
                        <a href="{{ route('opportunities.show', $opp->id) }}" class="block space-y-3">
                            {{-- OPP number & close date --}}
                            <div class="flex items-center justify-between text-[10px]">
                                <span class="text-cyan-500/80 font-mono tracking-wider uppercase">{{ $opp->opp_number }}</span>
                                @if($opp->expected_close_date)
                                <span @class([
                                    'font-medium font-mono',
                                    'text-rose-400' => $opp->expected_close_date->isPast() && !in_array($opp->stage, ['won','lost']),
                                    'text-slate-500' => !($opp->expected_close_date->isPast() && !in_array($opp->stage, ['won','lost']))
                                ])>
                                    {{ $opp->expected_close_date->format('d M Y') }}
                                </span>
                                @endif
                            </div>

                            {{-- Title --}}
                            <h3 class="text-sm font-bold text-slate-100 group-hover:text-cyan-400 transition-colors line-clamp-2 leading-snug">
                                {{ $opp->title }}
                            </h3>

                            {{-- Client Company --}}
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <span class="material-symbols-outlined text-[15px] text-slate-500">business</span>
                                <span class="truncate font-medium">{{ $opp->client->company_name ?? '-' }}</span>
                            </div>

                            {{-- Price & Info --}}
                            <div class="flex items-center justify-between pt-2 border-t border-slate-900/60">
                                @if($opp->estimated_value)
                                <span class="text-sm font-extrabold text-slate-100 font-mono">
                                    Rp {{ number_format((float)$opp->estimated_value, 0, ',', '.') }}
                                </span>
                                @else
                                <span class="text-xs text-slate-500 font-medium">Rp -</span>
                                @endif
                                
                                @if($opp->pax)
                                <span class="text-[10px] text-slate-400 bg-slate-950/60 border border-slate-800 px-1.5 py-0.5 rounded font-medium">
                                    {{ $opp->pax }} pax
                                </span>
                                @endif
                            </div>

                            {{-- Discount warning --}}
                            @if($opp->discount_percent > 0 && !$opp->discount_approved)
                            <div class="flex items-center gap-1.5 text-[10px] text-amber-400 bg-amber-950/20 border border-amber-900/30 px-2 py-1 rounded-lg">
                                <span class="material-symbols-outlined text-[13px] text-amber-400 flex-shrink-0">warning</span>
                                <span class="font-medium">Diskon {{ $opp->discount_percent }}% pending</span>
                            </div>
                            @endif

                            {{-- Sales owner avatar --}}
                            @if($opp->sales && !auth()->user()->isSales())
                            <div class="pt-2 border-t border-slate-900/40 flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full bg-cyan-950 border border-cyan-800/60 flex items-center justify-center text-[10px] text-cyan-400 font-black">
                                    {{ strtoupper(substr($opp->sales->name, 0, 1)) }}
                                </div>
                                <span class="text-[10px] text-slate-400 font-medium truncate">{{ $opp->sales->name }}</span>
                            </div>
                            @endif
                        </a>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-12 px-4 text-center rounded-xl border border-dashed border-slate-800/80 bg-slate-950/20">
                        <span class="material-symbols-outlined text-slate-600 text-[32px] mb-2">inbox</span>
                        <p class="text-xs text-slate-500 font-medium">Belum ada deal</p>
                    </div>
                    @endforelse
                </div>

            </div>
            @endforeach

        </div>
    </div>

</div>

<style>
    /* Custom thin scrollbar layout for Kanban column scrollable card area */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.1);
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.3);
    }
</style>
@endsection
