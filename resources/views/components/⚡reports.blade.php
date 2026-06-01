<?php

use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Reports & Analytics - Golden Bird')] class extends Component
{
    // Simple state
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
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-bold text-blue-600 md:ml-2">Reports & Analytics</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Reports & Analytics</h2>
        <p class="text-slate-500 text-sm">Download official and certified PDF summary reports compiled from database operation metrics.</p>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- 1. Fleet Summary -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between space-y-4 hover:shadow-md transition-all">
            <div class="space-y-2">
                <div class="p-3 bg-blue-50 text-blue-700 rounded-xl w-fit">
                    <span class="material-symbols-outlined text-[28px]">local_shipping</span>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Fleet Summary Report</h3>
                <p class="text-xs text-slate-500">Comprehensive breakdown of fleet sizes, active operation capacities, maintenance statuses, and occupancy loads.</p>
            </div>
            <a href="/reports/download/fleet" target="_blank"
               class="w-full text-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[16px]">download</span>
                Download Fleet Summary
            </a>
        </div>

        <!-- 2. Sales Performance -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between space-y-4 hover:shadow-md transition-all">
            <div class="space-y-2">
                <div class="p-3 bg-purple-50 text-purple-700 rounded-xl w-fit">
                    <span class="material-symbols-outlined text-[28px]">handshake</span>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Sales Performance Report</h3>
                <p class="text-xs text-slate-500">Analysis of active B2B corporate contracts, pipeline bookings, account tier counts, and client retention trends.</p>
            </div>
            <a href="/reports/download/sales" target="_blank"
               class="w-full text-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-xs font-semibold text-white bg-purple-600 hover:bg-purple-700 transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[16px]">download</span>
                Download Sales Report
            </a>
        </div>

        <!-- 3. Finance Recap -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex flex-col justify-between space-y-4 hover:shadow-md transition-all">
            <div class="space-y-2">
                <div class="p-3 bg-yellow-50 text-yellow-700 rounded-xl w-fit">
                    <span class="material-symbols-outlined text-[28px]">payments</span>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Finance & Billing Recap</h3>
                <p class="text-xs text-slate-500">Ledger details of monthly collections, outstanding invoice balances, cashflow logs, and payment method distributions.</p>
            </div>
            <a href="/reports/download/finance" target="_blank"
               class="w-full text-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-xs font-semibold text-white bg-amber-600 hover:bg-amber-700 transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[16px]">download</span>
                Download Finance Recap
            </a>
        </div>
    </div>
</div>
