@extends('layouts.app')

@section('header_title', 'Manager Dashboard')

@section('content')
<div class="space-y-6 font-sans">

    {{-- Page Header --}}
    <x-ui.page-header title="Manager Dashboard" subtitle="Sales team targets, pipeline conversions, and approval queue">
        <x-slot:actions>
            <x-ui.button variant="accent-cyan" size="sm" href="{{ route('kpi.index') }}">
                <span class="material-symbols-outlined text-sm">leaderboard</span> Manage Targets
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Team Overview Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <x-ui.stat-card label="Team Pipeline" value="Rp {{ number_format($teamPipelineValue, 0, ',', '.') }}" trend="flat" trendValue="Active Opportunities" icon="funnel" color="blue" />
        <x-ui.stat-card label="Won Deals (All Time)" value="{{ $teamWon }}" trend="up" trendValue="{{ $teamLost }} lost" icon="emoji_events" color="green" />
        <x-ui.stat-card label="Approval Level-1" value="{{ $pendingApprovals }}" trend="{{ $pendingApprovals > 0 ? 'up' : 'flat' }}" trendValue="Awaiting Review" icon="assignment_late" color="{{ $pendingApprovals > 0 ? 'red' : 'cyan' }}" />
        <x-ui.stat-card label="Team Members" value="{{ $teamMembers->count() }}" trend="flat" trendValue="Active Sales" icon="groups" color="gold" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left 8 Columns (Pipeline per Sales + KPI Table) --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- Pipeline Stage Bars --}}
            <x-ui.card title="Pipeline per Sales (Stage Breakdown)" subtitle="Opportunity progression tracking">
                <div class="space-y-4">
                    @php
                        $stageColors = [
                            'prospecting' => 'bg-slate-600 border border-slate-500/20 text-slate-300',
                            'proposal'    => 'bg-blue-600/80 border border-blue-500/20 text-blue-200',
                            'negotiation' => 'bg-amber-600/80 border border-amber-500/20 text-amber-200',
                        ];
                        $stageLabels = [
                            'prospecting' => 'Prospecting',
                            'proposal'    => 'Proposal',
                            'negotiation' => 'Negosiasi',
                        ];
                    @endphp

                    {{-- Legend --}}
                    <div class="flex flex-wrap gap-4 text-xs font-semibold pb-2 border-b border-slate-800/60">
                        @foreach($stages as $s)
                        <span class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded {{ $stageColors[$s] ?? 'bg-slate-700' }} inline-block"></span>
                            {{ $stageLabels[$s] ?? $s }}
                        </span>
                        @endforeach
                    </div>

                    @forelse($stageBreakdown as $row)
                    @php
                        $rowTotal = array_sum($row['totals']);
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold text-slate-200">{{ $row['name'] }}</span>
                            <span class="text-[10px] font-bold text-cyan-400 font-mono">{{ $rowTotal }} total</span>
                        </div>
                        <div class="flex h-5 rounded-lg overflow-hidden bg-slate-950 border border-slate-850">
                            @foreach($stages as $s)
                            @if(isset($row['totals'][$s]) && $row['totals'][$s] > 0 && $rowTotal > 0)
                            <div class="{{ $stageColors[$s] ?? 'bg-slate-700' }} flex items-center justify-center text-[10px] font-black"
                                 style="width: {{ round(($row['totals'][$s] / $rowTotal) * 100) }}%"
                                 title="{{ $stageLabels[$s] ?? $s }}: {{ $row['totals'][$s] }}">
                                {{ $row['totals'][$s] }}
                            </div>
                            @endif
                            @endforeach
                            @if($rowTotal == 0)
                            <div class="flex-1 flex items-center justify-center text-xs text-slate-500 font-medium">No opportunities recorded</div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-slate-500 font-semibold">Belum ada anggota tim.</p>
                    @endforelse
                </div>
            </x-ui.card>

            {{-- KPI Achievement Table --}}
            <x-ui.card title="Team KPI Target Achievement" subtitle="Target status for {{ now()->format('F Y') }}">
                <div class="overflow-x-auto -mx-6">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-950/60 text-xs text-slate-400 uppercase tracking-widest border-b border-slate-800/80">
                            <tr>
                                <th class="px-6 py-4.5 text-left font-bold">Sales Agent</th>
                                <th class="px-4 py-4.5 text-center font-bold">Won Count</th>
                                <th class="px-4 py-4.5 text-center font-bold">Win Rate</th>
                                <th class="px-6 py-4.5 text-right font-bold">Won Revenue</th>
                                <th class="px-6 py-4.5 text-center font-bold">KPI Target</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @forelse($teamMembers as $member)
                            <tr class="hover:bg-slate-850/30 transition-all duration-150">
                                <td class="px-6 py-4 font-bold text-slate-200">{{ $member->name }}</td>
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
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500 font-semibold">Belum ada anggota tim.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card>

        </div>

        {{-- Right 4 Columns (Approvals + Activities) --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- Pending Approvals --}}
            <x-ui.card title="Approval Level-1" subtitle="Action items pending manager authorization">
                <x-slot:actions>
                    @if($pendingApprovals > 0)
                        <x-ui.badge variant="danger" size="sm">{{ $pendingApprovals }} Urgent</x-ui.badge>
                    @endif
                </x-slot:actions>

                <div class="space-y-3">
                    @forelse($approvalQueue as $approval)
                    <div class="p-3 bg-slate-950/40 border border-slate-800/80 rounded-xl hover:border-cyan-500/20 transition-all duration-300">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-200 truncate">
                                    {{ optional(optional($approval->opportunity)->client)->company_name ?? 'N/A' }}
                                </h4>
                                <p class="text-[10px] text-slate-400 mt-1 font-semibold text-amber-400">
                                    {{ $approval->discount_percent }}% discount
                                </p>
                            </div>
                            <x-ui.button variant="accent-cyan" size="sm" href="{{ route('approvals.show', $approval) }}" class="py-1 px-2.5 text-[10px]">
                                Review
                            </x-ui.button>
                        </div>
                    </div>
                    @empty
                    <div class="py-8 text-center text-slate-500 font-semibold text-sm">No pending approvals.</div>
                    @endforelse
                </div>

                <div class="mt-4 pt-3 border-t border-slate-800/80">
                    <a href="{{ route('approvals.index') }}" class="text-xs text-cyan-400 hover:text-cyan-300 font-bold">
                        View all approvals &rarr;
                    </a>
                </div>
            </x-ui.card>

            {{-- Recent Team Activities --}}
            <x-ui.card title="Recent Team Activities" subtitle="Log of sales actions and calls">
                <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
                    @php
                        $activityIcons = [
                            'meeting'    => '🤝',
                            'call'       => '📞',
                            'visit'      => '🚗',
                            'follow_up'  => '📋',
                            'email'      => '📧',
                            'demo'       => '🎯',
                        ];
                    @endphp
                    @forelse($recentActivities as $activity)
                    <div class="p-3 bg-slate-950/40 border border-slate-800/80 rounded-xl">
                        <div class="flex items-start gap-2.5">
                            <span class="text-base flex-shrink-0 mt-0.5">
                                {{ $activityIcons[$activity->type] ?? '📌' }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <h5 class="text-xs font-bold text-slate-200 truncate">{{ $activity->subject }}</h5>
                                <p class="text-[10px] text-slate-400 mt-1">
                                    {{ optional($activity->sales)->name ?? '-' }}
                                    @if($activity->client) &bull; {{ optional($activity->client)->company_name }} @endif
                                </p>
                                <p class="text-[9px] text-slate-500 font-mono mt-0.5">
                                    {{ \Carbon\Carbon::parse($activity->activity_date)->format('d M H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-8 text-center text-slate-500 font-semibold text-sm">No recent team activities.</div>
                    @endforelse
                </div>

                <div class="mt-4 pt-3 border-t border-slate-800/80">
                    <a href="{{ route('activities.index') }}" class="text-xs text-cyan-400 hover:text-cyan-300 font-bold">
                        View all activity logs &rarr;
                    </a>
                </div>
            </x-ui.card>

        </div>

    </div>

</div>
@endsection
