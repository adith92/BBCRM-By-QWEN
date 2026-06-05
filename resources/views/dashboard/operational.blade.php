@extends('layouts.app')

@section('header_title', 'Operational Dashboard')

@section('content')
<div class="space-y-6 font-sans">

    {{-- Page Header --}}
    <x-ui.page-header title="Operational Dashboard" subtitle="Fleet status, active deployments, and maintenance schedules">
        <x-slot:actions>
            <x-ui.button variant="accent-cyan" size="sm" href="{{ route('bookings.index') }}">
                <span class="material-symbols-outlined text-sm">distance</span> Dispatch Control
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <x-ui.stat-card label="Available Fleet" value="{{ $availableFleet }}" trend="flat" trendValue="Idle Ready" icon="local_shipping" color="green" />
        <x-ui.stat-card label="On Trip" value="{{ $onTripFleet }}" trend="up" trendValue="Active Operations" icon="distance" color="blue" />
        <x-ui.stat-card label="Maintenance" value="{{ $maintenanceFleet }}" trend="down" trendValue="In Shop" icon="build" color="amber" />
        <x-ui.stat-card label="Active Bookings" value="{{ $activeBookings }}" trend="up" trendValue="Live Bookings" icon="pending_actions" color="purple" />
    </div>

    {{-- Active Trips Table --}}
    <x-ui.card title="Active Trips Log" subtitle="Real-time ongoing vehicle and driver dispatches">
        <div class="overflow-x-auto -mx-6">
            <table class="w-full text-sm">
                <thead class="bg-slate-950/60 text-xs text-slate-400 uppercase tracking-widest border-b border-slate-800/80">
                    <tr>
                        <th class="px-6 py-4.5 text-left font-bold">Booking #</th>
                        <th class="px-4 py-4.5 text-left font-bold">Client</th>
                        <th class="px-4 py-4.5 text-left font-bold">Vehicle (Plate)</th>
                        <th class="px-4 py-4.5 text-left font-bold">Driver Assigned</th>
                        <th class="px-4 py-4.5 text-left font-bold">Pickup Time</th>
                        <th class="px-6 py-4.5 text-center font-bold">Trip Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/50">
                    @forelse($activeBookingList as $booking)
                    <tr class="hover:bg-slate-850/30 transition-all duration-150">
                        <td class="px-6 py-4 font-mono font-bold text-slate-300">
                            <a href="{{ route('bookings.show', $booking->id) }}" class="text-cyan-400 hover:text-cyan-300">
                                {{ $booking->booking_number }}
                            </a>
                        </td>
                        <td class="px-4 py-4 text-slate-200 font-bold">{{ $booking->client->company_name }}</td>
                        <td class="px-4 py-4 font-mono text-slate-300">
                            <a href="{{ route('fleet.show', $booking->vehicle_id) }}" class="text-blue-400 hover:text-blue-300">
                                {{ $booking->vehicle->plate_number }}
                            </a>
                        </td>
                        <td class="px-4 py-4 text-slate-300 font-medium">{{ $booking->driver->name }}</td>
                        <td class="px-4 py-4 text-slate-400 font-mono text-xs">{{ $booking->pickup_datetime->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            <x-ui.badge variant="info" size="sm">{{ $booking->status }}</x-ui.badge>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 font-semibold">No active trips right now</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>

</div>
@endsection
