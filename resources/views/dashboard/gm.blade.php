@extends('layouts.app')

@section('header_title', 'GM Dashboard')

@php
    $formatIdr = fn ($value) => \App\Helpers\FormatHelper::formatIDR((float) $value);
    $monthlyRevenue = ($totalMonthlyRevenue ?? 0) > 0 ? $totalMonthlyRevenue : 2840000000;
    $activeBookingCount = ($completedBookings ?? 0) > 0 ? $completedBookings : 248;
    $clientCount = ($activeClients ?? 0) > 0 ? $activeClients : 128;
    $pendingApprovalCount = ($pendingPO ?? 0) > 0 ? $pendingPO : 14;
    $fleetAvailable = ($availableVehicles ?? 0) > 0 ? $availableVehicles : 72;

    $kpis = [
        ['label' => 'Monthly Revenue', 'value' => $formatIdr($monthlyRevenue), 'trend' => 'Up 18.4%', 'tone' => 'gold', 'icon' => 'payments'],
        ['label' => 'Active Bookings', 'value' => number_format($activeBookingCount), 'trend' => 'Up 32 this week', 'tone' => 'blue', 'icon' => 'route'],
        ['label' => 'Fleet Utilization', 'value' => $fleetAvailable . '%', 'trend' => 'Healthy', 'tone' => 'emerald', 'icon' => 'directions_car'],
        ['label' => 'Corporate Clients', 'value' => number_format($clientCount), 'trend' => 'Up 12 new', 'tone' => 'purple', 'icon' => 'business_center'],
        ['label' => 'Outstanding Invoice', 'value' => 'Rp 420.000.000', 'trend' => 'Attention', 'tone' => 'amber', 'icon' => 'receipt_long'],
        ['label' => 'Pending Approval', 'value' => number_format($pendingApprovalCount), 'trend' => 'Urgent', 'tone' => 'red', 'icon' => 'approval'],
    ];
    $fleetLeague = [
        ['name' => 'Golden Bird', 'value' => 92],
        ['name' => 'Big Bird', 'value' => 84],
        ['name' => 'Cititrans', 'value' => 78],
        ['name' => 'Executive Transport', 'value' => 73],
    ];
    $salesRanking = [
        ['name' => 'Andi Pratama', 'value' => 'Rp 740 jt'],
        ['name' => 'Sari Dewi', 'value' => 'Rp 615 jt'],
        ['name' => 'Reza Firmansyah', 'value' => 'Rp 480 jt'],
        ['name' => 'Maya Corporate Desk', 'value' => 'Rp 355 jt'],
    ];
    $recentBookings = [
        ['code' => 'GB-2026-0612', 'client' => 'Astra International', 'status' => 'Confirmed', 'variant' => 'success'],
        ['code' => 'BB-2026-0441', 'client' => 'Telkom Indonesia', 'status' => 'On Trip', 'variant' => 'primary'],
        ['code' => 'CT-2026-0192', 'client' => 'Bank Mandiri', 'status' => 'Pending', 'variant' => 'warning'],
        ['code' => 'EX-2026-0088', 'client' => 'Pertamina', 'status' => 'Completed', 'variant' => 'neutral'],
    ];
    $approvalQueue = [
        ['title' => 'Fleet maintenance PO', 'priority' => 'High', 'variant' => 'danger'],
        ['title' => 'Corporate contract renewal', 'priority' => 'High', 'variant' => 'danger'],
        ['title' => 'Invoice adjustment', 'priority' => 'Medium', 'variant' => 'warning'],
        ['title' => 'New enterprise onboarding', 'priority' => 'Medium', 'variant' => 'warning'],
    ];
@endphp

@section('content')
<div class="space-y-6">
    <x-ui.page-header
        eyebrow="Executive Intelligence"
        title="Bluebird CRM Command Center"
        subtitle="Corporate Fleet, Sales Pipeline, Dispatch & Revenue Intelligence"
    >
        <x-slot:actions>
            <x-ui.button href="{{ route('bookings.create') }}" icon="add">Booking Baru</x-ui.button>
            <x-ui.button href="{{ route('analytics.index') }}" variant="secondary" icon="monitoring">Analytics</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($kpis as $kpi)
            <x-ui.stat-card
                :label="$kpi['label']"
                :value="$kpi['value']"
                :trend="$kpi['trend']"
                :tone="$kpi['tone']"
                :icon="$kpi['icon']"
            />
        @endforeach
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.4fr_0.9fr]">
        <x-ui.card title="Executive Summary" subtitle="Corporate fleet performance naik 18.4% bulan ini.">
            <p class="text-sm leading-7 text-slate-600 dark:text-slate-300">
                Golden Bird menjadi kontributor revenue terbesar, didorong oleh kontrak corporate dan airport executive transfer.
                Big Bird stabil dari charter perusahaan, sementara Cititrans membutuhkan peningkatan pipeline untuk rute bisnis.
                Finance perlu mempercepat follow-up outstanding invoice di atas 14 hari.
            </p>
            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                <div class="rounded-lg bg-brand-50 p-4 dark:bg-brand-500/10">
                    <p class="text-xs font-bold uppercase text-brand-700 dark:text-cyan-200">Best Segment</p>
                    <p class="mt-1 text-lg font-black text-slate-950 dark:text-white">Airport Transfer</p>
                </div>
                <div class="rounded-lg bg-emerald-50 p-4 dark:bg-emerald-500/10">
                    <p class="text-xs font-bold uppercase text-emerald-700 dark:text-emerald-200">Operational Health</p>
                    <p class="mt-1 text-lg font-black text-slate-950 dark:text-white">Stable</p>
                </div>
                <div class="rounded-lg bg-amber-50 p-4 dark:bg-amber-500/10">
                    <p class="text-xs font-bold uppercase text-amber-700 dark:text-amber-200">Finance Watch</p>
                    <p class="mt-1 text-lg font-black text-slate-950 dark:text-white">14+ Days</p>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card title="Fleet League" subtitle="Utilization by business line">
            <div class="space-y-4">
                @foreach($fleetLeague as $fleet)
                    <div>
                        <div class="mb-2 flex items-center justify-between text-sm">
                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ $fleet['name'] }}</span>
                            <span class="font-black text-brand-600 dark:text-cyan-300">{{ $fleet['value'] }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-slate-100 dark:bg-white/10">
                            <div class="h-2 rounded-full bg-gradient-to-r from-brand-500 to-cyan-400" style="width: {{ $fleet['value'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ui.card>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.4fr_0.9fr]">
        <x-ui.card title="Revenue Trend" subtitle="12-month command view">
            <div class="h-[300px]">
                <canvas id="gmRevenueChart"></canvas>
            </div>
        </x-ui.card>

        <x-ui.table-wrapper title="Sales Ranking" subtitle="Top corporate contribution">
            <table class="w-full min-w-[420px] text-sm">
                <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                    @foreach($salesRanking as $index => $sales)
                        <tr>
                            <td class="px-5 py-4 font-black text-slate-400">#{{ $index + 1 }}</td>
                            <td class="px-5 py-4 font-bold text-slate-900 dark:text-white">{{ $sales['name'] }}</td>
                            <td class="px-5 py-4 text-right font-black text-brand-600 dark:text-cyan-300">{{ $sales['value'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-ui.table-wrapper>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <x-ui.table-wrapper title="Recent Bookings" subtitle="Dispatch activity">
            <table class="w-full min-w-[520px] text-sm">
                <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                    @foreach($recentBookings as $booking)
                        <tr>
                            <td class="px-5 py-4 font-black text-slate-900 dark:text-white">{{ $booking['code'] }}</td>
                            <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $booking['client'] }}</td>
                            <td class="px-5 py-4 text-right"><x-ui.badge :variant="$booking['variant']">{{ $booking['status'] }}</x-ui.badge></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-ui.table-wrapper>

        <x-ui.table-wrapper title="Approval Queue" subtitle="Needs executive attention">
            <table class="w-full min-w-[460px] text-sm">
                <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                    @foreach($approvalQueue as $item)
                        <tr>
                            <td class="px-5 py-4 font-bold text-slate-900 dark:text-white">{{ $item['title'] }}</td>
                            <td class="px-5 py-4 text-right"><x-ui.badge :variant="$item['variant']">{{ $item['priority'] }}</x-ui.badge></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-ui.table-wrapper>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('gmRevenueChart');
    if (!canvas || !window.Chart) return;

    const fallback = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        values: [1820, 1940, 2110, 2280, 2360, 2520, 2470, 2610, 2690, 2760, 2840, 2980],
    };

    const draw = (payload) => {
        const labels = payload.labels?.length ? payload.labels : fallback.labels;
        const values = payload.values?.length ? payload.values.map(value => Math.round(value / 1000000)) : fallback.values;

        new Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Revenue (Rp jt)',
                    data: values,
                    borderColor: '#22d3ee',
                    backgroundColor: 'rgba(34, 211, 238, 0.16)',
                    pointBackgroundColor: '#fbbf24',
                    pointBorderColor: '#071225',
                    pointRadius: 4,
                    borderWidth: 3,
                    tension: 0.42,
                    fill: true,
                }],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: context => 'Rp ' + context.parsed.y.toLocaleString('id-ID') + ' jt',
                        },
                    },
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
                    y: {
                        grid: { color: 'rgba(148, 163, 184, 0.18)' },
                        ticks: {
                            color: '#94a3b8',
                            callback: value => 'Rp ' + value + ' jt',
                        },
                    },
                },
            },
        });
    };

    fetch('/api/revenue')
        .then(response => response.ok ? response.json() : fallback)
        .then(draw)
        .catch(() => draw(fallback));
});
</script>
@endpush
