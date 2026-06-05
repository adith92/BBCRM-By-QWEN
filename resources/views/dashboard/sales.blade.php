@extends('layouts.app')

@section('header_title', 'Sales Cockpit')

@section('content')
<div class="space-y-6 font-sans">

    {{-- Page Header --}}
    <x-ui.page-header title="Sales Cockpit" subtitle="Personal pipeline, billing summary, and active bookings">
        <x-slot:actions>
            <x-ui.button variant="accent-cyan" size="sm" href="{{ route('bookings.create') }}">
                ➕ New Booking
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Revenue KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <x-ui.stat-card label="My Revenue Today" value="{{ \App\Helpers\FormatHelper::formatIDR($todayRevenue) }}" trend="flat" trendValue="Cleared" icon="payments" color="blue" />
        <x-ui.stat-card label="My Revenue Week" value="{{ \App\Helpers\FormatHelper::formatIDR($weekRevenue) }}" trend="up" trendValue="Weekly" icon="insights" color="cyan" />
        <x-ui.stat-card label="My Revenue Month" value="{{ \App\Helpers\FormatHelper::formatIDR($monthRevenue) }}" trend="up" trendValue="MTD" icon="trending_up" color="green" />
        <x-ui.stat-card label="My Revenue Year" value="{{ \App\Helpers\FormatHelper::formatIDR($yearRevenue) }}" trend="up" trendValue="YTD" icon="currency_exchange" color="gold" />
    </div>

    {{-- Client & Booking Activity Summary --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <x-ui.stat-card label="Active Bookings" value="{{ $activeBookings }}" trend="up" trendValue="Current Trips" icon="distance" color="amber" />
        <x-ui.stat-card label="My Clients" value="{{ $myClients }}" trend="up" trendValue="Assigned Partners" icon="business" color="cyan" />
        
        <x-ui.card padding="sm" class="flex items-center justify-center bg-slate-900/60 hover:bg-slate-850/60 border border-dashed border-slate-700/80 transition-all duration-300">
            <div class="text-center">
                <p class="text-xs text-slate-400 font-bold mb-2">Track target achievement progress</p>
                <x-ui.button variant="accent-cyan" size="sm" href="{{ route('sales.performance', ['user' => auth()->id()]) }}">
                    View Performance Dashboard
                </x-ui.button>
            </div>
        </x-ui.card>
    </div>

    {{-- Recent Bookings --}}
    <x-ui.card title="Recent Dispatched Bookings" subtitle="Summary of your recent customer dispatches">
        <x-slot:actions>
            <a href="{{ route('bookings.index') }}" class="text-xs text-cyan-400 hover:text-cyan-300 font-bold">View all bookings &rarr;</a>
        </x-slot:actions>

        <div class="overflow-x-auto -mx-6">
            <table class="w-full text-sm">
                <thead class="bg-slate-950/60 text-xs text-slate-400 uppercase tracking-widest border-b border-slate-800/80">
                    <tr>
                        <th class="px-6 py-4 text-left font-bold">Booking #</th>
                        <th class="px-4 py-4 text-left font-bold">Client Partner</th>
                        <th class="px-4 py-4 text-left font-bold">Assigned Vehicle</th>
                        <th class="px-4 py-4 text-center font-bold">Status</th>
                        <th class="px-6 py-4 text-right font-bold">Price Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse($recentBookings as $booking)
                    <tr class="hover:bg-slate-850/30 transition-all duration-150">
                        <td class="px-6 py-4 font-mono font-bold">
                            <a href="{{ route('bookings.show', $booking->id) }}" class="text-cyan-400 hover:text-cyan-300">
                                {{ $booking->booking_number }}
                            </a>
                        </td>
                        <td class="px-4 py-4">
                            <a href="{{ route('clients.show', $booking->client_id) }}" class="font-semibold text-slate-200 hover:text-cyan-400">
                                {{ $booking->client->company_name }}
                            </a>
                        </td>
                        <td class="px-4 py-4 font-mono text-slate-400 text-xs">
                            {{ $booking->vehicle->plate_number }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            <x-ui.badge variant="info" size="sm">{{ $booking->status }}</x-ui.badge>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-slate-100 font-mono">
                            {{ \App\Helpers\FormatHelper::formatIDR($booking->price) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 font-semibold">No recent bookings recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>

</div>
@endsection
