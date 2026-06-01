@extends('layouts.app')

@section('header_title', 'Fleet Management (Sales Dashboard)')

@section('content')
<div class="space-y-6">
    <!-- Greeting Card -->
    <div class="bg-gradient-to-r from-emerald-800 to-teal-900 text-white rounded-2xl p-6 shadow-lg">
        <h2 class="text-2xl font-bold">Welcome back, Sales Officer!</h2>
        <p class="text-teal-100 text-sm mt-1">Monitor vehicle availability and assign vehicles to clients.</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat 1 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-green-50 text-green-700 rounded-xl">
                <span class="text-lg font-extrabold">AV</span>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Available Fleet</p>
                <p class="text-2xl font-bold text-slate-800">4 Vehicles</p>
            </div>
        </div>

        <!-- Stat 2 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-blue-50 text-blue-700 rounded-xl">
                <span class="text-lg font-extrabold">PO</span>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Pre-Ordered (PO)</p>
                <p class="text-2xl font-bold text-slate-800">2 Vehicles</p>
            </div>
        </div>

        <!-- Stat 3 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-yellow-50 text-yellow-700 rounded-xl">
                <span class="text-lg font-extrabold">MT</span>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">In Maintenance</p>
                <p class="text-2xl font-bold text-slate-800">2 Vehicles</p>
            </div>
        </div>

        <!-- Stat 4 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center space-x-4">
            <div class="p-3 bg-red-50 text-red-700 rounded-xl">
                <span class="text-lg font-extrabold">HD</span>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">On Hold</p>
                <p class="text-2xl font-bold text-slate-800">2 Vehicles</p>
            </div>
        </div>
    </div>
</div>
@endsection
