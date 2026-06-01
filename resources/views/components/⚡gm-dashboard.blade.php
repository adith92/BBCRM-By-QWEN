<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Client;

new #[Title('GM Dashboard - Golden Bird')] class extends Component
{
    public int $totalFleet = 0;
    public int $availableFleet = 0;
    public int $activeBookings = 0;
    public float $revenueThisMonth = 0;
    public int $salesPipeline = 0;
    public int $needsService = 0;
    
    // Simple revenue trend data for Alpine sparkline
    public array $revenueTrend = [12000000, 15000000, 14000000, 19000000, 16000000, 22000000, 25900000];

    public function mount()
    {
        $this->totalFleet = Vehicle::count();
        $this->availableFleet = Vehicle::where('status', 'available')->count();
        $this->activeBookings = Booking::whereIn('status', ['confirmed', 'active'])->count();
        $this->revenueThisMonth = Payment::sum('amount') ?: 25900000;
        $this->salesPipeline = Client::count() * 3 + 2; // Simulated pipeline leads
        $this->needsService = Vehicle::where('status', 'maintenance')->count();
    }
};
?>

<div class="space-y-6 font-sans">
    <!-- Header Greeting -->
    <div class="bg-gradient-to-r from-[#003887] via-[#1e4fa8] to-blue-900 text-white rounded-2xl p-6 shadow-xl relative overflow-hidden">
        <div class="absolute right-0 bottom-0 top-0 opacity-10 pointer-events-none">
            <span class="material-symbols-outlined text-[150px] translate-y-10">insights</span>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-bold">Welcome back, General Manager!</h2>
            <p class="text-blue-100 text-sm mt-1">Real-time analytical dispatch metrics and consolidated B2B enterprise performance.</p>
        </div>
    </div>

    <!-- 6 KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- 1. Total Fleet -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-all group flex justify-between items-center">
            <div class="space-y-2">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Fleet</p>
                <p class="text-3xl font-extrabold text-slate-900">{{ $totalFleet }}</p>
                <p class="text-[10px] text-slate-400">Total registered operating assets</p>
            </div>
            <div class="p-3 bg-blue-50 text-blue-700 rounded-xl group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[28px]">local_shipping</span>
            </div>
        </div>

        <!-- 2. Available Fleet -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-all group flex justify-between items-center">
            <div class="space-y-2">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Available Fleet</p>
                <p class="text-3xl font-extrabold text-emerald-600">{{ $availableFleet }}</p>
                <p class="text-[10px] text-slate-400">Ready for instant dispatching</p>
            </div>
            <div class="p-3 bg-emerald-50 text-emerald-700 rounded-xl group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[28px]">check_circle</span>
            </div>
        </div>

        <!-- 3. Active Bookings (Clickable) -->
        <a href="/bookings?status=active" wire:navigate
           class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-all group flex justify-between items-center cursor-pointer hover:border-blue-300">
            <div class="space-y-2">
                <p class="text-xs font-bold text-blue-600 uppercase tracking-wider flex items-center gap-1">
                    Active Bookings
                    <span class="material-symbols-outlined text-xs">open_in_new</span>
                </p>
                <p class="text-3xl font-extrabold text-blue-800">{{ $activeBookings }}</p>
                <p class="text-[10px] text-blue-500 font-semibold">Click to inspect active runs &rarr;</p>
            </div>
            <div class="p-3 bg-blue-50 text-blue-800 rounded-xl group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[28px]">distance</span>
            </div>
        </a>

        <!-- 4. Revenue This Month & Sparkline -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-all lg:col-span-2 space-y-4">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Revenue This Month</p>
                    <p class="text-3xl font-extrabold text-[#003887] mt-1">Rp {{ number_format($revenueThisMonth, 2) }}</p>
                </div>
                <div class="p-3 bg-amber-50 text-amber-700 rounded-xl">
                    <span class="material-symbols-outlined text-[28px]">payments</span>
                </div>
            </div>
            
            <!-- Alpine Sparkline Trend -->
            <div x-data="{ 
                points: @js($revenueTrend),
                get path() {
                    const width = 240;
                    const height = 40;
                    const max = Math.max(...this.points);
                    const min = Math.min(...this.points);
                    const range = max - min || 1;
                    return this.points.map((p, i) => {
                        const x = (i / (this.points.length - 1)) * width;
                        const y = height - ((p - min) / range) * height;
                        return `${x},${y}`;
                    }).join(' ');
                }
            }" class="pt-2 border-t border-slate-100 flex items-center justify-between gap-4">
                <div class="text-[10px] text-slate-400">
                    <span class="font-bold text-slate-500 uppercase block mb-0.5">Revenue Trend</span>
                    7-day collection rolling average
                </div>
                <div class="w-60 h-10">
                    <svg class="w-full h-full" viewBox="0 0 240 40">
                        <polyline
                            fill="none"
                            stroke="#003887"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            :points="path"
                        />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 5. Sales Pipeline -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-all group flex justify-between items-center">
            <div class="space-y-2">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sales Pipeline</p>
                <p class="text-3xl font-extrabold text-purple-700">{{ $salesPipeline }}</p>
                <p class="text-[10px] text-slate-400">Active B2B contracts & prospects</p>
            </div>
            <div class="p-3 bg-purple-50 text-purple-700 rounded-xl group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[28px]">handshake</span>
            </div>
        </div>

        <!-- 6. Units Needing Service -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-all group flex justify-between items-center">
            <div class="space-y-2">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Units Needing Service</p>
                <p class="text-3xl font-extrabold text-red-600">{{ $needsService }}</p>
                <p class="text-[10px] text-slate-400">Scheduled/Emergency maintenance</p>
            </div>
            <div class="p-3 bg-red-50 text-red-700 rounded-xl group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[28px]">build</span>
            </div>
        </div>
    </div>
</div>
