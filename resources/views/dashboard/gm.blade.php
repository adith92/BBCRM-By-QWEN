@extends('layouts.app')

@section('header_title', 'GM Command Center')

@section('content')
<div class="space-y-6 font-sans">

    {{-- HEADER BLOCK --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 p-6 bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950/30 rounded-2xl border border-slate-800/80 shadow-[0_4px_25px_rgba(0,0,0,0.6)]">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-slate-100 font-outfit">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-400 to-indigo-400">Bluebird CRM</span> Command Center
            </h2>
            <p class="text-xs text-slate-400 font-medium tracking-wide mt-1.5">
                Corporate Fleet, Sales Pipeline, Dispatch & Revenue Intelligence
            </p>
        </div>
        <div class="flex flex-wrap gap-2.5 items-center">
            <x-ui.badge variant="cyan" size="sm">Live Demo</x-ui.badge>
            <x-ui.badge variant="gold" size="sm">June 2026</x-ui.badge>
            <x-ui.badge variant="purple" size="sm">Director HQ</x-ui.badge>
            <x-ui.badge variant="success" size="sm">API Ready</x-ui.badge>
            <x-ui.badge variant="info" size="sm">Railway/Render Deploy Ready</x-ui.badge>
        </div>
    </div>

    {{-- KPI CARDS ROW (6 COLUMNS Grid on desktop) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <x-ui.stat-card label="Monthly Revenue" value="Rp 2.840.000.000" trend="up" trendValue="18.4%" icon="payments" color="green" />
        <x-ui.stat-card label="Active Bookings" value="248" trend="up" trendValue="32 this week" icon="distance" color="blue" />
        <x-ui.stat-card label="Fleet Utilization" value="72%" trend="flat" trendValue="Healthy" icon="local_shipping" color="cyan" />
        <x-ui.stat-card label="Corporate Clients" value="128" trend="up" trendValue="12 new" icon="business" color="gold" />
        <x-ui.stat-card label="Outstanding Invoice" value="Rp 420.000.000" trend="flat" trendValue="Attention" icon="warning" color="amber" />
        <x-ui.stat-card label="Pending Approval" value="14" trend="down" trendValue="Urgent" icon="assignment_late" color="red" />
    </div>

    {{-- EXECUTIVE SUMMARY & RECOMMENDATIONS --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Executive Summary (Left 7 Columns) --}}
        <div class="lg:col-span-7">
            <x-ui.card title="Executive Summary" subtitle="Fleet and pipeline health summary for management review">
                <div class="space-y-4">
                    <p class="text-sm font-semibold text-cyan-400">
                        Corporate fleet performance naik 18.4% bulan ini.
                    </p>
                    <p class="text-sm leading-relaxed text-slate-300">
                        Golden Bird menjadi kontributor revenue terbesar, didorong oleh kontrak corporate dan airport executive transfer. Big Bird stabil dari charter perusahaan, sementara Cititrans membutuhkan peningkatan pipeline untuk rute bisnis. Finance perlu mempercepat follow-up outstanding invoice di atas 14 hari.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-3">
                        <div class="bg-slate-950/50 p-4 rounded-xl border border-slate-800/80">
                            <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Primary Driver</div>
                            <div class="text-sm font-bold text-slate-200 mt-1">Airport Transfer</div>
                        </div>
                        <div class="bg-slate-950/50 p-4 rounded-xl border border-slate-800/80">
                            <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Charter Status</div>
                            <div class="text-sm font-bold text-emerald-400 mt-1">Big Bird Stable</div>
                        </div>
                        <div class="bg-slate-950/50 p-4 rounded-xl border border-slate-800/80">
                            <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold">Cititrans Pipeline</div>
                            <div class="text-sm font-bold text-rose-400 mt-1">Needs Growth</div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>

        {{-- Recommendations (Right 5 Columns) --}}
        <div class="lg:col-span-5">
            <x-ui.card title="System Recommendations" subtitle="Automated tactical suggestions to optimize performance" glowing="true">
                <ul class="space-y-3">
                    <li class="flex gap-2.5 items-start">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 mt-2 flex-shrink-0 shadow-[0_0_8px_#22d3ee]"></span>
                        <p class="text-xs text-slate-300 font-medium">
                            Prioritaskan 12 client corporate dengan potensi renewal.
                        </p>
                    </li>
                    <li class="flex gap-2.5 items-start border-t border-slate-800/60 pt-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mt-2 flex-shrink-0 shadow-[0_0_8px_#fbbf24]"></span>
                        <p class="text-xs text-slate-300 font-medium">
                            Follow-up invoice overdue di atas 14 hari.
                        </p>
                    </li>
                    <li class="flex gap-2.5 items-start border-t border-slate-800/60 pt-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 mt-2 flex-shrink-0 shadow-[0_0_8px_#60a5fa]"></span>
                        <p class="text-xs text-slate-300 font-medium">
                            Tambahkan fleet allocation untuk area Jakarta HQ.
                        </p>
                    </li>
                    <li class="flex gap-2.5 items-start border-t border-slate-800/60 pt-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400 mt-2 flex-shrink-0 shadow-[0_0_8px_#f43f5e]"></span>
                        <p class="text-xs text-slate-300 font-medium">
                            Percepat approval PO maintenance untuk unit high-demand.
                        </p>
                    </li>
                    <li class="flex gap-2.5 items-start border-t border-slate-800/60 pt-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mt-2 flex-shrink-0 shadow-[0_0_8px_#34d399]"></span>
                        <p class="text-xs text-slate-300 font-medium">
                            Dorong sales terbaik untuk handle enterprise account.
                        </p>
                    </li>
                </ul>
            </x-ui.card>
        </div>
    </div>

    {{-- CHART ROW --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Revenue Activity Chart (Left 8 Columns) --}}
        <div class="lg:col-span-8">
            <x-ui.card title="Revenue Activity" subtitle="Weekly revenue distribution (Millions Rp)">
                <div class="h-80 relative">
                    <canvas id="revenueActivityChart" style="position: relative; height: 320px !important;"></canvas>
                </div>
            </x-ui.card>
        </div>

        {{-- Fleet League (Right 4 Columns) --}}
        <div class="lg:col-span-4">
            <x-ui.card title="Fleet Performance League" subtitle="Utility and performance ranking per brand">
                <div class="space-y-4">
                    {{-- Item 1 --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-800/60 rounded-xl flex items-center justify-between hover:border-cyan-500/20 transition-all duration-300">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-extrabold text-cyan-400 font-mono">01</span>
                            <div>
                                <h4 class="text-xs font-black text-slate-200">Golden Bird</h4>
                                <p class="text-[10px] text-slate-500 mt-0.5">Corporate & Airport</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-200">92%</span>
                            <div class="mt-1"><x-ui.badge variant="success" size="sm">High Performer</x-ui.badge></div>
                        </div>
                    </div>

                    {{-- Item 2 --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-800/60 rounded-xl flex items-center justify-between hover:border-cyan-500/20 transition-all duration-300">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-extrabold text-blue-400 font-mono">02</span>
                            <div>
                                <h4 class="text-xs font-black text-slate-200">Big Bird</h4>
                                <p class="text-[10px] text-slate-500 mt-0.5">Company Charter</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-200">84%</span>
                            <div class="mt-1"><x-ui.badge variant="info" size="sm">Stable</x-ui.badge></div>
                        </div>
                    </div>

                    {{-- Item 3 --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-800/60 rounded-xl flex items-center justify-between hover:border-cyan-500/20 transition-all duration-300">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-extrabold text-amber-400 font-mono">03</span>
                            <div>
                                <h4 class="text-xs font-black text-slate-200">Cititrans</h4>
                                <p class="text-[10px] text-slate-500 mt-0.5">Business Route</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-200">78%</span>
                            <div class="mt-1"><x-ui.badge variant="warning" size="sm">Needs Growth</x-ui.badge></div>
                        </div>
                    </div>

                    {{-- Item 4 --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-800/60 rounded-xl flex items-center justify-between hover:border-cyan-500/20 transition-all duration-300">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-extrabold text-slate-400 font-mono">04</span>
                            <div>
                                <h4 class="text-xs font-black text-slate-200">Executive Transport</h4>
                                <p class="text-[10px] text-slate-500 mt-0.5">Special Purpose</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-200">73%</span>
                            <div class="mt-1"><x-ui.badge variant="neutral" size="sm">Under Review</x-ui.badge></div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    {{-- BOTTOM INFORMATION GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Sales Ranking (Left 4 Columns) --}}
        <div class="lg:col-span-4">
            <x-ui.card title="Sales Representative Ranking" subtitle="Monthly billing target leaderboard">
                <div class="divide-y divide-slate-800/60">
                    {{-- Sales 1 --}}
                    <div class="py-3 flex items-center justify-between first:pt-0 last:pb-0">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-950 border border-slate-850 flex items-center justify-center font-bold text-cyan-400 text-xs shadow-[0_2px_5px_rgba(0,0,0,0.4)]">
                                1
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-200">Andi Pratama</h4>
                                <p class="text-[10px] text-slate-500 mt-0.5">Enterprise Accounts</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-extrabold text-slate-200">Rp 740 Jt</span>
                            <p class="text-[9px] font-bold text-emerald-400 mt-0.5">Closing 38%</p>
                        </div>
                    </div>

                    {{-- Sales 2 --}}
                    <div class="py-3 flex items-center justify-between first:pt-0 last:pb-0">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-950 border border-slate-850 flex items-center justify-center font-bold text-blue-400 text-xs shadow-[0_2px_5px_rgba(0,0,0,0.4)]">
                                2
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-200">Sari Dewi</h4>
                                <p class="text-[10px] text-slate-500 mt-0.5">Retail Accounts</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-extrabold text-slate-200">Rp 615 Jt</span>
                            <p class="text-[9px] font-bold text-emerald-400 mt-0.5">Closing 34%</p>
                        </div>
                    </div>

                    {{-- Sales 3 --}}
                    <div class="py-3 flex items-center justify-between first:pt-0 last:pb-0">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-950 border border-slate-850 flex items-center justify-center font-bold text-slate-400 text-xs shadow-[0_2px_5px_rgba(0,0,0,0.4)]">
                                3
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-200">Reza Firmansyah</h4>
                                <p class="text-[10px] text-slate-500 mt-0.5">B2B Logistics</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-extrabold text-slate-200">Rp 480 Jt</span>
                            <p class="text-[9px] font-bold text-cyan-400 mt-0.5">Closing 29%</p>
                        </div>
                    </div>

                    {{-- Sales 4 --}}
                    <div class="py-3 flex items-center justify-between first:pt-0 last:pb-0">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-950 border border-slate-850 flex items-center justify-center font-bold text-slate-400 text-xs shadow-[0_2px_5px_rgba(0,0,0,0.4)]">
                                4
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-200">Maya Corporate Desk</h4>
                                <p class="text-[10px] text-slate-500 mt-0.5">Airlines & Crew Desk</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-extrabold text-slate-200">Rp 355 Jt</span>
                            <p class="text-[9px] font-bold text-cyan-400 mt-0.5">Closing 24%</p>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>

        {{-- Approval Queue (Middle 4 Columns) --}}
        <div class="lg:col-span-4">
            <x-ui.card title="Approval Queue" subtitle="Action items requiring manager review">
                <div class="space-y-3.5">
                    {{-- Item 1 --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-800/60 rounded-xl hover:border-rose-500/20 transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-slate-200">Fleet Maintenance PO</h4>
                                <p class="text-[10px] text-slate-500 mt-0.5">Operational Division</p>
                            </div>
                            <x-ui.badge variant="danger" size="sm">High</x-ui.badge>
                        </div>
                        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-850">
                            <span class="text-[10px] text-slate-500">2 hours ago</span>
                            <x-ui.button variant="accent-cyan" size="sm" class="py-1 px-2.5 text-[10px]">Review PO</x-ui.button>
                        </div>
                    </div>

                    {{-- Item 2 --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-800/60 rounded-xl hover:border-rose-500/20 transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-slate-200">Corporate Contract Renewal</h4>
                                <p class="text-[10px] text-slate-500 mt-0.5">Sales Division</p>
                            </div>
                            <x-ui.badge variant="danger" size="sm">High</x-ui.badge>
                        </div>
                        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-850">
                            <span class="text-[10px] text-slate-500">4 hours ago</span>
                            <x-ui.button variant="accent-cyan" size="sm" class="py-1 px-2.5 text-[10px]">Review Contract</x-ui.button>
                        </div>
                    </div>

                    {{-- Item 3 --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-800/60 rounded-xl hover:border-amber-500/20 transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-slate-200">Invoice Adjustment</h4>
                                <p class="text-[10px] text-slate-500 mt-0.5">Finance Division</p>
                            </div>
                            <x-ui.badge variant="warning" size="sm">Medium</x-ui.badge>
                        </div>
                        <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-850">
                            <span class="text-[10px] text-slate-500">1 day ago</span>
                            <x-ui.button variant="accent-cyan" size="sm" class="py-1 px-2.5 text-[10px]">Review Invoice</x-ui.button>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>

        {{-- Recent Bookings (Right 4 Columns) --}}
        <div class="lg:col-span-4">
            <x-ui.card title="Recent Dispatch Bookings" subtitle="Live active transport dispatch events">
                <div class="space-y-3">
                    {{-- Booking 1 --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-800/60 rounded-xl hover:border-cyan-500/20 transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-slate-100 font-mono">GB-2026-0612</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">Astra International &bull; Golden Bird</p>
                            </div>
                            <x-ui.badge variant="cyan" size="sm">Confirmed</x-ui.badge>
                        </div>
                    </div>

                    {{-- Booking 2 --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-800/60 rounded-xl hover:border-cyan-500/20 transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-slate-100 font-mono">BB-2026-0441</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">Telkom Indonesia &bull; Big Bird</p>
                            </div>
                            <x-ui.badge variant="info" size="sm">On Trip</x-ui.badge>
                        </div>
                    </div>

                    {{-- Booking 3 --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-800/60 rounded-xl hover:border-cyan-500/20 transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-slate-100 font-mono">CT-2026-0192</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">Bank Mandiri &bull; Cititrans</p>
                            </div>
                            <x-ui.badge variant="warning" size="sm">Pending</x-ui.badge>
                        </div>
                    </div>

                    {{-- Booking 4 --}}
                    <div class="p-3 bg-slate-950/40 border border-slate-800/60 rounded-xl hover:border-cyan-500/20 transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-slate-100 font-mono">EX-2026-0088</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">Pertamina &bull; Executive</p>
                            </div>
                            <x-ui.badge variant="success" size="sm">Completed</x-ui.badge>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = 'rgba(255, 255, 255, 0.04)';
    const textColor = '#94a3b8';
    
    // Revenue Activity Line Chart (Monday - Sunday)
    const ctx = document.getElementById('revenueActivityChart').getContext('2d');
    
    // Create subtle gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(6, 182, 212, 0.35)');
    gradient.addColorStop(0.5, 'rgba(37, 99, 235, 0.1)');
    gradient.addColorStop(1, 'rgba(6, 7, 10, 0)');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            datasets: [{
                label: 'Revenue Activity',
                data: [320000000, 410000000, 285000000, 520000000, 475000000, 240000000, 190000000],
                borderColor: '#22d3ee',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#22d3ee',
                pointBorderColor: '#08090d',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 8,
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
                    bodyColor: '#22d3ee',
                    borderColor: 'rgba(255,255,255,0.08)',
                    borderWidth: 1,
                    padding: 10,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + (context.parsed.y / 1000000).toFixed(0) + ' Jt';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: gridColor },
                    ticks: { color: textColor, font: { family: 'Inter', weight: 500 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: { 
                        color: textColor,
                        font: { family: 'Inter' },
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000).toFixed(0) + ' Jt';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
