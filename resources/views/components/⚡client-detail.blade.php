<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Client;

new #[Title('Client Profile - Golden Bird')] class extends Component
{
    public Client $client;

    public function mount(Client $client)
    {
        $this->client = $client->load(['bookings.vehicle', 'bookings.invoice']);
    }
};
?>

<div class="space-y-6 font-sans">
    <!-- Breadcrumb -->
    <nav class="flex px-5 py-3 text-slate-700 bg-white border border-slate-200 rounded-xl" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 font-sans">
            <li class="inline-flex items-center">
                <a href="/" class="inline-flex items-center text-sm font-semibold text-slate-700 hover:text-blue-600 transition">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-semibold text-slate-700">Clients</span>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-bold text-blue-600 md:ml-2">{{ $client->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Top Bar Navigation Shell style Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="/invoices" class="p-2 hover:bg-slate-100 rounded-full transition-colors" wire:navigate>
                <span class="material-symbols-outlined text-primary text-[24px]">arrow_back</span>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-bold text-slate-900">{{ $client->name }}</h2>
                    <span class="px-3 py-1 bg-amber-50 rounded-full text-[10px] tracking-widest font-extrabold text-amber-600 border border-amber-200">PLATINUM</span>
                    <span class="flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Active
                    </span>
                </div>
                <p class="text-slate-500 text-sm mt-1">Company: {{ $client->company ?? 'Personal Client' }}</p>
            </div>
        </div>
    </div>

    <!-- Stats & identity Bento grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-2">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Bookings</p>
                <span class="material-symbols-outlined text-primary">calendar_month</span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900">{{ $client->bookings->count() }}</p>
            <p class="text-xs text-emerald-600 mt-1 flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">trending_up</span> Active fleet usage
            </p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-2">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Invoices</p>
                <span class="material-symbols-outlined text-primary">receipt_long</span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900">
                {{ $client->bookings->pluck('invoice')->filter()->count() }}
            </p>
            <p class="text-xs text-emerald-600 mt-1 flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">check_circle</span> Payment healthy
            </p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all col-span-2">
            <div class="flex justify-between items-start mb-2">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Financial Overview</p>
                <span class="material-symbols-outlined text-primary font-bold">payments</span>
            </div>
            @php
                $invoices = $client->bookings->pluck('invoice')->filter();
                $totalBilled = $invoices->sum('total_amount');
                $remaining = $invoices->sum('remaining_balance');
            @endphp
            <div class="grid grid-cols-2 gap-4 mt-2">
                <div>
                    <span class="text-[10px] text-slate-400 font-bold block uppercase">Total Billed</span>
                    <span class="font-bold text-slate-900 text-sm">Rp {{ number_format($totalBilled, 2) }}</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 font-bold block uppercase">Outstanding</span>
                    <span class="font-bold text-red-600 text-sm">Rp {{ number_format($remaining, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bento layout content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Client Identity Card (Left) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm space-y-6">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-primary text-[20px]">info</span> Client Identity
                </h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                <div class="space-y-1">
                    <p class="font-bold text-slate-400 uppercase">Company Name</p>
                    <p class="font-semibold text-slate-800 text-sm">{{ $client->company ?? 'Personal Client' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="font-bold text-slate-400 uppercase">Contact PIC Name</p>
                    <p class="font-semibold text-slate-800 text-sm">{{ $client->name }}</p>
                </div>
                <div class="space-y-1">
                    <p class="font-bold text-slate-400 uppercase">Email Address</p>
                    <p class="font-semibold text-blue-600 text-sm">{{ $client->email ?? '-' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="font-bold text-slate-400 uppercase">Phone Number</p>
                    <p class="font-semibold text-slate-800 text-sm">{{ $client->phone ?? '-' }}</p>
                </div>
                <div class="col-span-full space-y-1">
                    <p class="font-bold text-slate-400 uppercase">Billing Address</p>
                    <p class="font-semibold text-slate-800 text-sm">{{ $client->address ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Client Bookings & Invoice logs -->
            <div class="p-6 border-t border-slate-200 space-y-4">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Bookings & Invoices ledger</h4>
                <div class="space-y-2">
                    @forelse($client->bookings as $b)
                        <div class="flex justify-between items-center p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                            <div>
                                <a href="/fleet/{{ $b->vehicle->id }}" wire:navigate class="font-bold text-blue-600 hover:underline">
                                    {{ $b->vehicle->brand }} ({{ $b->vehicle->plate }})
                                </a>
                                <p class="text-slate-500 mt-0.5">
                                    Period: {{ \Carbon\Carbon::parse($b->start_datetime)->format('d M Y') }} - {{ \Carbon\Carbon::parse($b->end_datetime)->format('d M Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                @if($b->invoice)
                                    <a href="/invoices/{{ $b->invoice->id }}" wire:navigate class="inline-block px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-bold hover:underline mb-1">
                                        #INV-{{ str_pad($b->invoice->id, 5, '0', STR_PAD_LEFT) }}
                                    </a>
                                    <span class="block text-[10px] text-red-600 font-bold">Rp {{ number_format($b->invoice->remaining_balance, 2) }}</span>
                                @else
                                    <span class="text-slate-400 italic">No Invoice</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">No bookings recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activity Timeline (Right) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                <h3 class="font-bold text-slate-800 flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-primary text-[20px]">history</span> Activity Timeline
                </h3>
            </div>
            <div class="p-6 flex-1 space-y-6">
                <div class="relative space-y-6 before:absolute before:inset-0 before:ml-4 before:h-full before:w-[1px] before:bg-slate-200">
                    <!-- Invoice Generation -->
                    @foreach($client->bookings as $index => $b)
                        @if($b->invoice)
                            <div class="relative flex items-start gap-4">
                                <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-500 text-white z-10 shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">description</span>
                                </div>
                                <div class="flex flex-col text-xs">
                                    <span class="text-slate-400 font-bold">{{ $b->invoice->created_at->format('d M Y - H:i') }}</span>
                                    <p class="font-bold text-slate-900 mt-0.5">Invoice #INV-{{ str_pad($b->invoice->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    <p class="text-slate-500">Issued grand total Rp {{ number_format($b->invoice->total_amount, 2) }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Booking placement -->
                        <div class="relative flex items-start gap-4">
                            <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white z-10 shadow-sm">
                                <span class="material-symbols-outlined text-[16px]">check_circle</span>
                            </div>
                            <div class="flex flex-col text-xs">
                                <span class="text-slate-400 font-bold">{{ \Carbon\Carbon::parse($b->created_at)->format('d M Y - H:i') }}</span>
                                <p class="font-bold text-slate-900 mt-0.5">Booking Placed</p>
                                <p class="text-slate-500">Vehicle: {{ $b->vehicle->brand }} ({{ $b->vehicle->plate }})</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
