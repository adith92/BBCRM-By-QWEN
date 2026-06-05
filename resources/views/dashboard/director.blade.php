@extends('layouts.app')

@section('header_title', 'Director Cockpit')

@section('content')
<div class="space-y-6 font-sans">

    {{-- HEADER BLOCK --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 p-6 bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950/30 rounded-2xl border border-slate-800/80 shadow-[0_4px_25px_rgba(0,0,0,0.6)]">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-slate-100 font-outfit">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-blue-400 to-cyan-400">Director Cockpit</span>
            </h2>
            <p class="text-xs text-slate-400 font-medium tracking-wide mt-1.5">
                Executive Strategy, Pipeline Valuations & Approval Delegations
            </p>
        </div>
        <div class="flex flex-wrap gap-2.5 items-center">
            <x-ui.badge variant="purple" size="sm">HQ Authority</x-ui.badge>
            <x-ui.badge variant="cyan" size="sm">Active Session</x-ui.badge>
            <x-ui.badge variant="success" size="sm">Pipeline Secured</x-ui.badge>
        </div>
    </div>

    {{-- KPI SUMMARY CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <x-ui.stat-card label="Pipeline Value" value="Rp {{ number_format($pipelineValue, 0, ',', '.') }}" trend="flat" trendValue="Opportunity List" icon="funnel" color="blue" />
        <x-ui.stat-card label="Win Rate" value="{{ $winRate }}%" trend="{{ $winRate >= 50 ? 'up' : 'down' }}" trendValue="Avg Won/Lost" icon="leaderboard" color="{{ $winRate >= 50 ? 'green' : 'amber' }}" />
        <x-ui.stat-card label="Pending Approvals" value="{{ $pendingApprovals }}" trend="{{ $pendingApprovals > 0 ? 'up' : 'flat' }}" trendValue="Requires Action" icon="assignment_late" color="{{ $pendingApprovals > 0 ? 'red' : 'cyan' }}" />
        <x-ui.stat-card label="Revenue MTD" value="Rp {{ number_format($revenueMTD, 0, ',', '.') }}" trend="up" trendValue="{{ now()->format('F Y') }}" icon="payments" color="green" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Team Performance Table (Left 8 Columns) --}}
        <div class="lg:col-span-8">
            <x-ui.card title="Sales Representative Pipeline" subtitle="Opportunity conversions and KPI progress">
                <div class="overflow-x-auto -mx-6">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-950/60 text-xs text-slate-400 uppercase tracking-widest border-b border-slate-800/80">
                            <tr>
                                <th class="px-6 py-4.5 text-left font-bold">Sales Agent</th>
                                <th class="px-4 py-4.5 text-center font-bold">Pipeline</th>
                                <th class="px-4 py-4.5 text-center font-bold">Won</th>
                                <th class="px-4 py-4.5 text-center font-bold">Win Rate</th>
                                <th class="px-6 py-4.5 text-right font-bold">Revenue</th>
                                <th class="px-6 py-4.5 text-center font-bold">KPI Target</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @forelse($salesTeam as $member)
                            <tr class="hover:bg-slate-850/30 transition-all duration-150">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-200">{{ $member->name }}</div>
                                    <div class="text-[10px] text-cyan-400 uppercase font-extrabold mt-0.5 tracking-wider">{{ $member->role }}</div>
                                </td>
                                <td class="px-4 py-4 text-center text-slate-300 font-mono">{{ $member->pipeline_count }}</td>
                                <td class="px-4 py-4 text-center font-bold text-emerald-400 font-mono">{{ $member->won_count }}</td>
                                <td class="px-4 py-4 text-center">
                                    <x-ui.badge variant="{{ $member->win_rate >= 50 ? 'success' : 'warning' }}" size="sm">
                                        {{ $member->win_rate }}%
                                    </x-ui.badge>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-slate-100 font-mono">
                                    Rp {{ number_format($member->won_revenue ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5 justify-center">
                                        <div class="w-16 bg-slate-900 border border-slate-800 rounded-full h-2">
                                            <div class="h-full rounded-full {{ $member->kpi_pct >= 100 ? 'bg-emerald-500 shadow-[0_0_8px_#10b981]' : ($member->kpi_pct >= 60 ? 'bg-amber-500 shadow-[0_0_8px_#f59e0b]' : 'bg-rose-500 shadow-[0_0_8px_#ef4444]') }}"
                                                style="width: {{ min($member->kpi_pct, 100) }}%"></div>
                                        </div>
                                        <span class="text-xs text-slate-400 font-bold font-mono">{{ $member->kpi_pct }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500 font-semibold">Belum ada data sales.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </div>

        {{-- Approval Queue (Right 4 Columns) --}}
        <div class="lg:col-span-4">
            <x-ui.card title="Approval Queue" subtitle="Requests requiring executive signature">
                <x-slot:actions>
                    @if($pendingApprovals > 0)
                        <x-ui.badge variant="danger" size="sm">{{ $pendingApprovals }} Pending</x-ui.badge>
                    @endif
                </x-slot:actions>
                
                <div class="space-y-3">
                    @forelse($approvalQueue as $approval)
                    <div class="p-3 bg-slate-950/40 border border-slate-800/80 rounded-xl hover:border-purple-500/20 transition-all duration-300">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-200 truncate">
                                    {{ optional(optional($approval->opportunity)->client)->company_name ?? 'N/A' }}
                                </h4>
                                <p class="text-[10px] text-slate-400 mt-1">
                                    {{ ucfirst($approval->type) }} &bull;
                                    <span class="text-amber-400 font-semibold">{{ $approval->discount_percent }}% discount</span>
                                </p>
                                <p class="text-[9px] text-slate-500 mt-0.5">
                                    Requested by {{ optional($approval->requestedBy)->name ?? '-' }}
                                </p>
                            </div>
                            <x-ui.button variant="accent-cyan" size="sm" href="{{ route('approvals.show', $approval) }}" class="py-1 px-2.5 text-[10px]">
                                Review
                            </x-ui.button>
                        </div>
                    </div>
                    @empty
                    <div class="py-12 text-center text-slate-500 text-sm font-semibold">
                        Tidak ada approval pending.
                    </div>
                    @endforelse
                </div>
                
                @if($pendingApprovals > 5)
                <div class="mt-4 pt-3 border-t border-slate-800/80">
                    <a href="{{ route('approvals.index') }}" class="text-xs text-cyan-400 hover:text-cyan-300 font-bold">
                        Lihat semua {{ $pendingApprovals }} approval &rarr;
                    </a>
                </div>
                @endif
            </x-ui.card>
        </div>

    </div>

    {{-- Revenue Chart --}}
    <x-ui.card title="Revenue Trend" subtitle="Twelve-month rolling revenue chart (Millions Rp)">
        <div class="h-64 relative">
            <canvas id="revenueTrendChart" style="position: relative; height: 250px !important;"></canvas>
        </div>
    </x-ui.card>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = 'rgba(255, 255, 255, 0.04)';
    const textColor = '#94a3b8';
    
    fetch('/api/revenue')
        .then(r => r.json())
        .then(data => {
            const canvas = document.getElementById('revenueTrendChart');
            if (!canvas) return;
            
            const ctx = canvas.getContext('2d');
            
            // Create gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 220);
            gradient.addColorStop(0, 'rgba(168, 85, 247, 0.35)');
            gradient.addColorStop(0.5, 'rgba(59, 130, 246, 0.1)');
            gradient.addColorStop(1, 'rgba(6, 7, 10, 0)');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels || [],
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: data.values || [],
                        borderColor: '#a855f7',
                        backgroundColor: gradient,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#a855f7',
                        pointBorderColor: '#06070a',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#f8fafc',
                            bodyColor: '#a855f7',
                            borderColor: 'rgba(255,255,255,0.08)',
                            borderWidth: 1,
                            padding: 10,
                            callbacks: {
                                label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: gridColor },
                            ticks: { color: textColor, font: { family: 'Inter' } }
                        },
                        y: {
                            grid: { color: gridColor },
                            ticks: {
                                color: textColor,
                                font: { family: 'Inter' },
                                callback: v => 'Rp ' + (v / 1000000).toFixed(0) + ' Jt'
                            }
                        }
                    }
                }
            });
        })
        .catch(() => {});
});
</script>
@endpush
