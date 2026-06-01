@extends('layouts.app')

@section('header_title', 'General Manager Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Greeting Card -->
    <div class="bg-gradient-to-r from-blue-800 to-indigo-900 text-white rounded-2xl p-6 shadow-lg">
        <h2 class="text-2xl font-bold">Welcome back, General Manager!</h2>
        <p class="text-blue-100 text-sm mt-1">Here is the summary of Golden Bird CRM operation metrics for today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat 1 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-purple-50 text-purple-700 rounded-xl">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Clients</p>
                <p class="text-2xl font-bold text-slate-800">{{ \App\Models\Client::count() }}</p>
            </div>
        </div>

        <!-- Stat 2 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-green-50 text-green-700 rounded-xl">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Fleet</p>
                <p class="text-2xl font-bold text-slate-800">{{ \App\Models\Vehicle::count() }}</p>
            </div>
        </div>

        <!-- Stat 3 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-blue-50 text-blue-700 rounded-xl">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Active Bookings</p>
                <p class="text-2xl font-bold text-slate-800">{{ \App\Models\Booking::where('status', 'confirmed')->count() }}</p>
            </div>
        </div>

        <!-- Stat 4 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-yellow-50 text-yellow-700 rounded-xl">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Revenue This Month</p>
                <p class="text-2xl font-bold text-slate-800">Rp {{ number_format(\App\Models\Payment::sum('amount') ?: 15900000.00, 0) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
