@extends('layouts.app')

@section('header_title', 'Booking Management (Operations Dashboard)')

@section('content')
<div class="space-y-6">
    <!-- Greeting Card -->
    <div class="bg-gradient-to-r from-blue-800 to-indigo-900 text-white rounded-2xl p-6 shadow-lg">
        <h2 class="text-2xl font-bold">Welcome back, Operations Head!</h2>
        <p class="text-blue-100 text-sm mt-1">Review operational schedules, ongoing bookings, and dispatcher assignments.</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat 1 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-blue-50 text-blue-700 rounded-xl">
                <span class="text-lg font-extrabold">CF</span>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Confirmed Bookings</p>
                <p class="text-2xl font-bold text-slate-800">2 Bookings</p>
            </div>
        </div>

        <!-- Stat 2 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-yellow-50 text-yellow-700 rounded-xl">
                <span class="text-lg font-extrabold">PD</span>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Pending Approval</p>
                <p class="text-2xl font-bold text-slate-800">1 Booking</p>
            </div>
        </div>

        <!-- Stat 3 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-green-50 text-green-700 rounded-xl">
                <span class="text-lg font-extrabold">CP</span>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Completed Bookings</p>
                <p class="text-2xl font-bold text-slate-800">1 Booking</p>
            </div>
        </div>

        <!-- Stat 4 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-red-50 text-red-700 rounded-xl">
                <span class="text-lg font-extrabold">CC</span>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Cancelled Bookings</p>
                <p class="text-2xl font-bold text-slate-800">1 Booking</p>
            </div>
        </div>
    </div>
</div>
@endsection
