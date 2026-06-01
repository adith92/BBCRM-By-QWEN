<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

new #[Title('Invoices - Golden Bird')] class extends Component
{
    use WithPagination;

    public string $search = '';

    // Popup Modal States
    public ?int $selectedInvoiceId = null;
    public ?Invoice $selectedInvoice = null;
    public bool $showDetailModal = false;

    // Record Payment Modal states
    public bool $showPaymentModal = false;
    public string $paymentAmount = '';
    public string $paymentMethod = 'Bank Transfer';
    public string $paymentReference = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openDetail(int $id)
    {
        return $this->redirect("/invoices/{$id}", navigate: true);
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedInvoice = null;
        $this->selectedInvoiceId = null;
    }

    public function openPaymentModal()
    {
        if ($this->selectedInvoice) {
            $this->paymentAmount = $this->selectedInvoice->remaining_balance;
            $this->paymentReference = '';
            $this->showPaymentModal = true;
        }
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
    }

    public function recordPayment()
    {
        $maxAmount = $this->selectedInvoice->remaining_balance;
        
        $this->validate([
            'paymentAmount' => 'required|numeric|min:1000|max:' . $maxAmount,
            'paymentMethod' => 'required|in:Cash,Bank Transfer,CC,Ovo,Gopay',
            'paymentReference' => 'nullable|string|max:100',
        ], [
            'paymentAmount.required' => 'Jumlah pembayaran wajib diisi.',
            'paymentAmount.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'paymentAmount.min' => 'Jumlah pembayaran minimal Rp 1.000.',
            'paymentAmount.max' => 'Jumlah pembayaran tidak boleh melebihi sisa tagihan (Rp ' . number_format($maxAmount, 2) . ').',
        ]);

        try {
            DB::transaction(function () {
                // Record the payment
                Payment::create([
                    'invoice_id' => $this->selectedInvoice->id,
                    'amount' => $this->paymentAmount,
                    'method' => $this->paymentMethod,
                    'reference' => $this->paymentReference,
                ]);

                // Update the remaining balance
                $newRemaining = $this->selectedInvoice->remaining_balance - $this->paymentAmount;
                $this->selectedInvoice->remaining_balance = $newRemaining;
                
                // Status calculation
                if ($newRemaining == 0) {
                    $this->selectedInvoice->status = 'paid';
                } else {
                    $this->selectedInvoice->status = 'partially_paid';
                }

                $this->selectedInvoice->save();
            });

            session()->flash('modal_message', 'Payment successfully recorded!');
            $this->showPaymentModal = false;
            
            // Refresh Selected Invoice Data
            $this->selectedInvoice = Invoice::with(['booking.client', 'booking.vehicle', 'payments'])->findOrFail($this->selectedInvoiceId);

        } catch (\Exception $e) {
            $this->addError('paymentAmount', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
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
                        <tr class="hover:bg-slate-50 transition cursor-pointer" wire:click="openDetail({{ $invoice->id }})">
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
                                <button type="button" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                                    View Details &rarr;
                                </button>
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

    <!-- Invoice Detail Popup Modal -->
    @if($showDetailModal && $selectedInvoice)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50 px-4">
            <div class="bg-white rounded-2xl max-w-3xl w-full p-6 shadow-2xl space-y-6 border border-slate-200 animate-fade-in max-h-[90vh] overflow-y-auto">
                
                <!-- Modal Header -->
                <div class="flex justify-between items-start border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">#INV-{{ str_pad($selectedInvoice->id, 5, '0', STR_PAD_LEFT) }}</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">Issued on {{ $selectedInvoice->created_at->format('d M Y - H:i') }}</p>
                    </div>
                    <button wire:click="closeDetail" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
                </div>

                <!-- Modal Message -->
                @if (session()->has('modal_message'))
                    <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-xs font-semibold rounded-xl flex items-center shadow-sm">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('modal_message') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Left: Metadata -->
                    <div class="md:col-span-2 space-y-4 text-xs">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                                <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider block mb-0.5">Billing Client</span>
                                <span class="font-bold text-slate-900 block text-sm">{{ $selectedInvoice->booking->client->name }}</span>
                                <span class="text-slate-500 block">{{ $selectedInvoice->booking->client->company ?? 'Personal' }}</span>
                            </div>
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                                <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider block mb-0.5">Rented Vehicle</span>
                                <span class="font-bold text-slate-900 block text-sm">{{ $selectedInvoice->booking->vehicle->brand }} ({{ $selectedInvoice->booking->vehicle->plate }})</span>
                            </div>
                        </div>

                        <!-- Payment History Log -->
                        <div class="pt-4 border-t border-slate-100 space-y-3">
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Payment History Log</h4>
                            @if($selectedInvoice->payments->count() > 0)
                                <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                                    @foreach($selectedInvoice->payments as $payment)
                                        <div class="flex justify-between items-center p-3 bg-slate-50 border border-slate-200 rounded-xl text-[11px]">
                                            <div>
                                                <p class="font-bold text-slate-900">Rp {{ number_format($payment->amount, 2) }}</p>
                                                <p class="text-slate-500 mt-0.5">{{ $payment->method }} | Ref: {{ $payment->reference ?? 'N/A' }}</p>
                                            </div>
                                            <span class="text-slate-400">{{ $payment->created_at->format('d M Y - H:i') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-slate-400 italic">No payments recorded yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Ledger Summary & Controls -->
                    <div class="space-y-4 bg-slate-50 p-4 border border-slate-200 rounded-xl max-h-72 text-xs">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Financial Overview</h4>
                        <div class="space-y-2 border-b border-slate-200 pb-2">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Total Invoice</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($selectedInvoice->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Total Paid</span>
                                <span class="font-bold text-green-600">Rp {{ number_format($selectedInvoice->total_amount - $selectedInvoice->remaining_balance, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-sm">
                                <span class="text-slate-600">Remaining</span>
                                <span class="text-red-600">Rp {{ number_format($selectedInvoice->remaining_balance, 2) }}</span>
                            </div>
                        </div>

                        <!-- Record Payment Button (only if balance > 0) -->
                        @if($selectedInvoice->remaining_balance > 0)
                            <button wire:click="openPaymentModal"
                                    class="w-full py-2.5 px-4 border border-transparent rounded-xl shadow-md font-semibold text-white bg-green-600 hover:bg-green-700 transition">
                                Record Payment
                            </button>
                        @else
                            <div class="p-3 bg-green-50 border border-green-200 rounded-xl text-center font-bold text-green-800">
                                Fully Paid (Lunas)
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Nested Record Payment Modal overlay -->
    @if($showPaymentModal && $selectedInvoice)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 px-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200 animate-fade-in">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-extrabold text-slate-900">Record Payment</h3>
                    <button wire:click="closePaymentModal" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
                </div>

                <form wire:submit.prevent="recordPayment" class="space-y-4 text-xs">
                    <!-- Amount -->
                    <div>
                        <label for="paymentAmount" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Amount (Rp)</label>
                        <input wire:model="paymentAmount" id="paymentAmount" type="number" step="0.01" required
                               class="block w-full px-3 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 text-xs shadow-sm">
                        @error('paymentAmount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Method -->
                    <div>
                        <label for="paymentMethod" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Method</label>
                        <select wire:model="paymentMethod" id="paymentMethod" required
                                class="block w-full px-3 py-2 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 text-xs shadow-sm cursor-pointer">
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cash">Cash</option>
                            <option value="CC">Credit Card</option>
                            <option value="Ovo">Ovo</option>
                            <option value="Gopay">Gopay</option>
                        </select>
                        @error('paymentMethod') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Reference -->
                    <div>
                        <label for="paymentReference" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Reference Code / Note</label>
                        <input wire:model="paymentReference" id="paymentReference" type="text"
                               class="block w-full px-3 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 text-xs shadow-sm"
                               placeholder="e.g. TXN-19283-AX">
                        @error('paymentReference') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex space-x-2 pt-4">
                        <button type="submit"
                                class="flex-1 py-2 px-4 border border-transparent rounded-xl shadow-md font-semibold text-white bg-green-600 hover:bg-green-700 transition">
                            Submit Payment
                        </button>
                        <button type="button" wire:click="closePaymentModal"
                                class="flex-1 py-2 px-4 border border-slate-300 rounded-xl font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>