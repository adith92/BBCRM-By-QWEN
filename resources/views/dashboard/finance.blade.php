@extends('layouts.app')

@section('header_title', 'Finance Dashboard')

@section('content')
<div class="space-y-6 font-sans">

    {{-- Page Header --}}
    <x-ui.page-header title="Finance Dashboard" subtitle="Revenue tracking, invoices, and outstanding account status">
        <x-slot:actions>
            <x-ui.button variant="accent-cyan" size="sm" href="{{ route('finance.index') }}">
                <span class="material-symbols-outlined text-sm">payments</span> Finance Ledger
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <x-ui.stat-card label="Today Revenue" value="{{ \App\Helpers\FormatHelper::formatIDR($todayRevenue) }}" trend="flat" trendValue="Deposits" icon="payments" color="blue" />
        <x-ui.stat-card label="Month Revenue" value="{{ \App\Helpers\FormatHelper::formatIDR($monthRevenue) }}" trend="up" trendValue="MTD" icon="insights" color="green" />
        <x-ui.stat-card label="Pending Invoices" value="{{ $pendingInvoice }}" trend="flat" trendValue="Awaiting Payment" icon="description" color="amber" />
        <x-ui.stat-card label="Outstanding Amount" value="{{ \App\Helpers\FormatHelper::formatIDR($outstanding) }}" trend="flat" trendValue="{{ $overdueCount }} Overdue" icon="warning" color="red" />
    </div>

    {{-- Summary Panel --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <x-ui.card title="Paid Transactions MTD" subtitle="Total paid invoices in the current billing cycle">
                <div class="text-center py-6">
                    <p class="text-3xl font-extrabold text-emerald-400 font-mono">{{ $paidThisMonth }}</p>
                    <p class="text-xs text-slate-400 mt-2 font-medium">Invoices Cleared</p>
                </div>
            </x-ui.card>
        </div>

        {{-- Overdue Invoices List (Left 2 Columns) --}}
        <div class="lg:col-span-2">
            @if($overdueInvoices->count())
                <x-ui.card title="⚠️ Overdue Invoices List" subtitle="Unpaid invoices past their payment due date">
                    <x-slot:actions>
                        <a href="{{ route('finance.index', ['status' => 'overdue']) }}" class="text-xs text-rose-400 hover:underline font-bold">View all &rarr;</a>
                    </x-slot:actions>
                    
                    <div class="space-y-3">
                        @foreach($overdueInvoices as $inv)
                        <div class="flex items-center justify-between p-3 bg-rose-950/20 border border-rose-900/30 rounded-xl hover:border-rose-500/20 transition-all duration-300">
                            <div>
                                <a href="{{ route('invoices.show', $inv->id) }}" class="text-xs font-bold text-rose-400 font-mono hover:underline">
                                    {{ $inv->invoice_number }}
                                </a>
                                <span class="text-slate-600 text-xs mx-2">|</span>
                                <a href="{{ route('clients.show', $inv->client_id) }}" class="text-xs text-slate-300 hover:text-cyan-400 font-medium">
                                    {{ $inv->client->company_name }}
                                </a>
                            </div>
                            <span class="text-xs font-black text-rose-400 font-mono">{{ \App\Helpers\FormatHelper::formatIDR($inv->amount) }}</span>
                        </div>
                        @endforeach
                    </div>
                </x-ui.card>
            @else
                <x-ui.card title="⚠️ Overdue Invoices List" subtitle="No overdue invoices at this time">
                    <div class="py-10 text-center text-slate-500 font-semibold text-sm">
                        Great! No overdue invoices on record.
                    </div>
                </x-ui.card>
            @endif
        </div>
    </div>

</div>
@endsection
