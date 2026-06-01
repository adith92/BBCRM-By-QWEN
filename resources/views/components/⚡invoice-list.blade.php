<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\Invoice;

new #[Title('Invoices - Golden Bird')] class extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function with(): array
    {
        $query = Invoice::with(['booking.client', 'booking.vehicle']);

        if ($this->search !== '') {
            $query->whereHas('booking.client', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        return [
            'invoices' => $query->latest()->paginate(10)
        ];
    }
};
?>

<div class="space-y-6">
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
                    <span class="ml-1 text-sm font-bold text-blue-600 md:ml-2">Invoices</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div>
        <h2 class="text-2xl font-bold text-slate-800">Billing & Invoices Directory</h2>
        <p class="text-slate-500 text-sm">Review transaction summaries, unpaid balance logs, and record client payment records.</p>
    </div>

    <!-- Filter & Search Panel -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                   class="block w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-sm shadow-sm"
                   placeholder="Search by client name...">
        </div>
    </div>

    <!-- Invoices Directory -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Invoice ID</th>
                        <th class="px-6 py-4 text-left">Client</th>
                        <th class="px-6 py-4 text-left">Vehicle Booking</th>
                        <th class="px-6 py-4 text-left">Total Amount</th>
                        <th class="px-6 py-4 text-left">Remaining Balance</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-slate-900">
                                #INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $invoice->booking->client->name }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $invoice->booking->vehicle->brand }} ({{ $invoice->booking->vehicle->plate }})
                            </td>
                            <td class="px-6 py-4 font-semibold">
                                Rp {{ number_format($invoice->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 font-bold text-red-600">
                                Rp {{ number_format($invoice->remaining_balance, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statuses = [
                                        'paid' => 'bg-green-100 text-green-800 border-green-200',
                                        'partially_paid' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'unpaid' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        'overdue' => 'bg-red-100 text-red-800 border-red-200',
                                    ];
                                    $color = $statuses[$invoice->status] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                                    $label = $invoice->status === 'partially_paid' ? 'PARTIAL' : strtoupper($invoice->status);
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $color }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="/invoices/{{ $invoice->id }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                                    View Details &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                No invoices found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($invoices->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>