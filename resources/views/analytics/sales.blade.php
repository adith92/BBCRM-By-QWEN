@extends('layouts.app')

@section('header_title', 'Sales Performance')

@section('content')
<div class="space-y-6 font-sans">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-[#003887] text-[28px]">leaderboard</span>
        <div>
            <h2 class="text-xl font-bold text-slate-900">Sales Performance</h2>
            <p class="text-xs text-slate-400">Periode {{ $now->translatedFormat('F Y') }}</p>
        </div>
    </div>

    {{-- Performance table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3">#</th>
                        <th class="px-5 py-3">Sales</th>
                        <th class="px-5 py-3 text-center">Opportunities</th>
                        <th class="px-5 py-3 text-center">Won</th>
                        <th class="px-5 py-3 text-center">Lost</th>
                        <th class="px-5 py-3 text-center">Win Rate</th>
                        <th class="px-5 py-3 text-right">Revenue</th>
                        <th class="px-5 py-3 text-center">KPI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($performance as $i => $row)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <span class="w-7 h-7 rounded-full bg-blue-100 text-[#003887] text-xs font-extrabold flex items-center justify-center">{{ $loop->iteration }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-800">{{ $row['user']->name }}</div>
                            <div class="text-[11px] text-slate-400 capitalize">{{ $row['user']->role }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-center text-slate-600">{{ $row['total_opportunities'] }}</td>
                        <td class="px-5 py-3.5 text-center"><span class="font-bold text-emerald-600">{{ $row['won'] }}</span></td>
                        <td class="px-5 py-3.5 text-center"><span class="text-red-500">{{ $row['lost'] }}</span></td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-[11px] font-bold {{ $row['win_rate'] >= 50 ? 'bg-emerald-100 text-emerald-700' : ($row['win_rate'] > 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500') }}">{{ $row['win_rate'] }}%</span>
                        </td>
                        <td class="px-5 py-3.5 text-right font-bold text-slate-900">{{ \App\Helpers\FormatHelper::formatIDR($row['revenue'] ?? 0) }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden min-w-[50px]">
                                    <div class="bg-gradient-to-r from-[#003887] to-secondary h-full" style="width: {{ min($row['kpi_pct'] ?? 0,100) }}%"></div>
                                </div>
                                <span class="text-[11px] font-bold text-slate-600">{{ round($row['kpi_pct'] ?? 0) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-5 py-10 text-center text-slate-400">
                        <span class="material-symbols-outlined text-4xl block mb-2 opacity-40">inbox</span>
                        Belum ada data performa sales
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
