@extends('layouts.app')

@section('header_title', 'Director Dashboard')

@php
    $formatIdr = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $pipelineTotal = ($pipelineValue ?? 0) > 0 ? $pipelineValue : 6840000000;
    $revenueMonth = ($revenueMTD ?? 0) > 0 ? $revenueMTD : 2840000000;
    $approvalCount = $pendingApprovals ?? 0;
    $team = collect($salesTeam ?? []);
    $teamFallback = collect([
        (object) ['name' => 'Andi Pratama', 'role' => 'sales', 'pipeline' => 1240000000, 'won_count' => 12, 'win_rate' => 68, 'kpi_pct' => 108],
        (object) ['name' => 'Sari Dewi', 'role' => 'sales', 'pipeline' => 980000000, 'won_count' => 10, 'win_rate' => 62, 'kpi_pct' => 96],
        (object) ['name' => 'Reza Firmansyah', 'role' => 'sales', 'pipeline' => 760000000, 'won_count' => 8, 'win_rate' => 57, 'kpi_pct' => 84],
    ]);
    $visibleTeam = $team->isNotEmpty() ? $team : $teamFallback;
    $approvals = collect($approvalQueue ?? []);
@endphp

@section('content')
<div class="space-y-6">
    <x-ui.page-header
        eyebrow="Board Overview"
        title="Director Command Dashboard"
        subtitle="Strategic view untuk pipeline, revenue, approval exposure, dan health tim sales."
    >
        <x-slot:actions>
            <x-ui.button href="{{ route('analytics.index') }}" icon="monitoring">Reports</x-ui.button>
            <x-ui.button href="{{ route('approvals.index') }}" variant="secondary" icon="approval">Approval Queue</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-ui.stat-card label="Pipeline Value" :value="$formatIdr($pipelineTotal)" trend="Enterprise pipeline" tone="blue" icon="conversion_path" />
        <x-ui.stat-card label="Win Rate" :value="($winRate ?? 0) . '%'" trend="Won vs lost" tone="{{ ($winRate ?? 0) >= 50 ? 'emerald' : 'amber' }}" icon="target" />
        <x-ui.stat-card label="Pending Approvals" :value="$approvalCount" :trend="$approvalCount > 0 ? 'Needs review' : 'Clear'" tone="{{ $approvalCount > 0 ? 'red' : 'emerald' }}" icon="approval" />
        <x-ui.stat-card label="Revenue MTD" :value="$formatIdr($revenueMonth)" trend="{{ now()->format('F Y') }}" tone="gold" icon="payments" />
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.35fr_0.9fr]">
        <x-ui.table-wrapper title="Sales Team Performance" subtitle="Pipeline, won count, win rate, dan KPI progress">
            <table class="w-full min-w-[760px] text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500 dark:bg-white/[0.04] dark:text-slate-400">
                    <tr>
                        <th class="px-5 py-3 text-left">Sales</th>
                        <th class="px-5 py-3 text-right">Pipeline</th>
                        <th class="px-5 py-3 text-center">Won</th>
                        <th class="px-5 py-3 text-center">Win Rate</th>
                        <th class="px-5 py-3 text-center">KPI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                    @foreach($visibleTeam as $member)
                        @php
                            $pipeline = $member->pipeline ?? $member->pipeline_value ?? 0;
                            $kpi = $member->kpi_pct ?? min(100, (int) ($member->win_rate ?? 0) + 28);
                            $kpiVariant = $kpi >= 100 ? 'success' : ($kpi >= 70 ? 'warning' : 'danger');
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.04]">
                            <td class="px-5 py-4">
                                <p class="font-black text-slate-900 dark:text-white">{{ $member->name }}</p>
                                <p class="text-xs font-bold uppercase tracking-wide text-slate-400">{{ $member->role ?? 'sales' }}</p>
                            </td>
                            <td class="px-5 py-4 text-right font-bold text-slate-700 dark:text-slate-200">{{ $formatIdr($pipeline) }}</td>
                            <td class="px-5 py-4 text-center font-black text-emerald-600 dark:text-emerald-300">{{ $member->won_count ?? 0 }}</td>
                            <td class="px-5 py-4 text-center"><x-ui.badge variant="primary">{{ $member->win_rate ?? 0 }}%</x-ui.badge></td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-3">
                                    <div class="h-2 w-24 rounded-full bg-slate-100 dark:bg-white/10">
                                        <div class="h-2 rounded-full {{ $kpi >= 100 ? 'bg-emerald-500' : ($kpi >= 70 ? 'bg-amber-400' : 'bg-red-500') }}" style="width: {{ min($kpi, 100) }}%"></div>
                                    </div>
                                    <x-ui.badge :variant="$kpiVariant">{{ $kpi }}%</x-ui.badge>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-ui.table-wrapper>

        <x-ui.card title="Approval Exposure" subtitle="Executive approval queue">
            @if($approvals->isNotEmpty())
                <div class="space-y-3">
                    @foreach($approvals as $approval)
                        <a href="{{ route('approvals.show', $approval) }}" class="block rounded-lg border border-slate-200 p-4 transition hover:border-brand-300 hover:bg-brand-50 dark:border-white/10 dark:hover:border-cyan-400/40 dark:hover:bg-cyan-400/10">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-black text-slate-900 dark:text-white">{{ optional(optional($approval->opportunity)->client)->company_name ?? 'Approval Request' }}</p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ ucfirst($approval->type ?? 'discount') }} review</p>
                                </div>
                                <x-ui.badge variant="warning">Review</x-ui.badge>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <x-ui.empty-state title="Approval clear" message="Tidak ada approval pending untuk saat ini." icon="verified" />
            @endif
        </x-ui.card>
    </section>

    <x-ui.card title="Strategic Revenue Outlook" subtitle="Stable command-center projection">
        <div class="h-[300px]">
            <canvas id="directorRevenueChart"></canvas>
        </div>
    </x-ui.card>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('directorRevenueChart');
    if (!canvas || !window.Chart) return;

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: ['Golden Bird', 'Big Bird', 'Cititrans', 'Executive'],
            datasets: [{
                label: 'Revenue (Rp jt)',
                data: [1240, 840, 520, 420],
                backgroundColor: ['#22d3ee', '#2563eb', '#fbbf24', '#10b981'],
                borderRadius: 8,
            }],
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
                y: {
                    grid: { color: 'rgba(148, 163, 184, 0.18)' },
                    ticks: { color: '#94a3b8', callback: value => 'Rp ' + value + ' jt' },
                },
            },
        },
    });
});
</script>
@endpush
