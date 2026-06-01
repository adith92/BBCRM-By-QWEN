<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

new #[Title('Invoice Detail - Golden Bird')] class extends Component
{
    public Invoice $invoice;
    
    // Modal states
    public bool $showPaymentModal = false;
    public string $paymentAmount = '';
    public string $paymentMethod = 'Bank Transfer';
    public string $paymentReference = '';

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function togglePaymentModal()
    {
        $this->paymentAmount = $this->invoice->remaining_balance;
        $this->paymentReference = '';
        $this->showPaymentModal = !$this->showPaymentModal;
    }

    public function recordPayment()
    {
        $maxAmount = $this->invoice->remaining_balance;
        
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
                    'invoice_id' => $this->invoice->id,
                    'amount' => $this->paymentAmount,
                    'method' => $this->paymentMethod,
                    'reference' => $this->paymentReference,
                ]);

                // Update the remaining balance
                $newRemaining = $this->invoice->remaining_balance - $this->paymentAmount;
                $this->invoice->remaining_balance = $newRemaining;
                
                // Status calculation
                if ($newRemaining == 0) {
                    $this->invoice->status = 'paid';
                } else {
                    $this->invoice->status = 'partially_paid';
                }

                $this->invoice->save();
            });

            session()->flash('message', 'Payment successfully recorded!');
            $this->showPaymentModal = false;
            $this->invoice->refresh();

        } catch (\Exception $e) {
            $this->addError('paymentAmount', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
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
                    <a href="/invoices" class="ml-1 text-sm font-semibold text-slate-700 hover:text-blue-600 md:ml-2 transition">Invoices</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-bold text-blue-600 md:ml-2">#INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-sm font-medium rounded-xl flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Invoice Summary & Metadata -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 lg:col-span-2 space-y-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900">#INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Created on {{ $invoice->created_at->format('d M Y - H:i') }}</p>
                </div>
                <div>
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
                    <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold border {{ $color }}">
                        {{ $label }}
                    </span>
                </div>
            </div>

            <!-- Inter-connected Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-100 text-sm">
                <!-- Client Box (Clickable link to client summary/demo metadata) -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Billing Client</span>
                    <a href="/clients/{{ $invoice->booking->client->id }}" wire:navigate class="font-bold text-blue-600 hover:text-blue-800 hover:underline block text-base transition">
                        {{ $invoice->booking->client->name }}
                    </a>
                    <span class="text-slate-600 text-xs block mt-0.5">{{ $invoice->booking->client->company ?? 'Personal' }}</span>
                    <span class="text-slate-500 text-xs block mt-1">📞 {{ $invoice->booking->client->phone ?? '-' }}</span>
                </div>

                <!-- Vehicle Box -->
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block mb-1">Rented Vehicle</span>
                    <a href="/fleet/{{ $invoice->booking->vehicle->id }}" wire:navigate class="font-bold text-blue-600 hover:text-blue-800 hover:underline block text-base transition">
                        {{ $invoice->booking->vehicle->brand }} {{ $invoice->booking->vehicle->model }}
                    </a>
                    <span class="font-mono text-xs font-bold text-slate-700 bg-slate-200 border border-slate-300 px-1.5 py-0.5 rounded inline-block mt-1">
                        {{ $invoice->booking->vehicle->plate }}
                    </span>
                </div>
            </div>

            <!-- Payment History list -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Payment History Log</h4>
                @if($invoice->payments->count() > 0)
                    <div class="space-y-3">
                        @foreach($invoice->payments as $payment)
                            <div class="flex justify-between items-center p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                                <div>
                                    <p class="font-bold text-slate-900">Rp {{ number_format($payment->amount, 2) }}</p>
                                    <p class="text-slate-500 mt-0.5">Method: <span class="font-semibold text-slate-800">{{ $payment->method }}</span> | Ref: {{ $payment->reference ?? 'N/A' }}</p>
                                </div>
                                <span class="text-slate-400 font-semibold">{{ $payment->created_at->format('d M Y - H:i') }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">No payments have been recorded for this invoice yet.</p>
                @endif
            </div>
        </div>

        <!-- Ledger Financial controls Card -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-6">
            <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Financial Overview</h4>

            <div class="space-y-4 text-sm">
                <div class="flex justify-between pb-2 border-b border-slate-100">
                    <span class="text-slate-500">Invoice Total</span>
                    <span class="font-bold text-slate-800">Rp {{ number_format($invoice->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between pb-2 border-b border-slate-100">
                    <span class="text-slate-500">Total Paid</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($invoice->total_amount - $invoice->remaining_balance, 2) }}</span>
                </div>
                <div class="flex justify-between pb-2 border-b border-slate-100">
                    <span class="text-slate-500">Remaining Balance</span>
                    <span class="font-extrabold text-red-600">Rp {{ number_format($invoice->remaining_balance, 2) }}</span>
                </div>
            </div>

            <!-- Record Payment Button (only if balance > 0) -->
            @if($invoice->remaining_balance > 0)
                <button wire:click="togglePaymentModal"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-green-600 hover:bg-green-700 transition">
                    Record Client Payment
                </button>
            @else
                <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-center text-xs font-bold text-green-800">
                    Tagihan Lunas (Fully Paid)
                </div>
            @endif
        </div>
    </div>

    <!-- Record Payment Modal (Custom Livewire Modal overlay) -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200 animate-fade-in">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <h3 class="text-base font-extrabold text-slate-900">Record Client Payment</h3>
                    <button wire:click="togglePaymentModal" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form wire:submit.prevent="recordPayment" class="space-y-4 text-sm">
                    <!-- Amount -->
                    <div>
                        <label for="paymentAmount" class="block text-xs font-bold text-slate-500 uppercase mb-1">Payment Amount (Rp)</label>
                        <input wire:model="paymentAmount" id="paymentAmount" type="number" step="0.01" required
                               class="block w-full px-3 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 text-sm shadow-sm">
                        @error('paymentAmount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Method -->
                    <div>
                        <label for="paymentMethod" class="block text-xs font-bold text-slate-500 uppercase mb-1">Payment Method</label>
                        <select wire:model="paymentMethod" id="paymentMethod" required
                                class="block w-full px-3 py-2 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 text-sm shadow-sm cursor-pointer">
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
                        <label for="paymentReference" class="block text-xs font-bold text-slate-500 uppercase mb-1">Reference Code / Note</label>
                        <input wire:model="paymentReference" id="paymentReference" type="text"
                               class="block w-full px-3 py-2 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 text-sm shadow-sm"
                               placeholder="e.g. TXN-98218-AX">
                        @error('paymentReference') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex space-x-2 pt-4">
                        <button type="submit"
                                class="flex-1 py-2.5 px-4 border border-transparent rounded-xl shadow-md text-xs font-semibold text-white bg-green-600 hover:bg-green-700 transition">
                            Submit Payment
                        </button>
                        <button type="button" wire:click="togglePaymentModal"
                                class="flex-1 py-2.5 px-4 border border-slate-300 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>