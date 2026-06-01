<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

new #[Title('New Booking - Golden Bird')] class extends Component
{
    public $clients = [];
    public $vehicles = [];

    public ?int $clientId = null;
    public ?int $vehicleId = null;
    public string $startDate = '';
    public string $endDate = '';
    
    public function mount()
    {
        $this->clients = Client::all();
        // Only fetch available vehicles for the booking drop-down!
        $this->vehicles = Vehicle::where('status', 'available')->get();
    }

    public function createBooking()
    {
        $this->validate([
            'clientId' => 'required|exists:clients,id',
            'vehicleId' => 'required|exists:vehicles,id',
            'startDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after_or_equal:startDate',
        ], [
            'clientId.required' => 'Client wajib dipilih.',
            'vehicleId.required' => 'Kendaraan wajib dipilih.',
            'startDate.required' => 'Tanggal mulai wajib diisi.',
            'endDate.required' => 'Tanggal selesai wajib diisi.',
        ]);

        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        try {
            DB::transaction(function () use ($start, $end) {
                // Lock the vehicle row using pessimistic locking to prevent race conditions
                $vehicle = Vehicle::where('id', $this->vehicleId)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Re-verify that the vehicle is still available
                if ($vehicle->status !== 'available') {
                    throw new \Exception('Kendaraan sudah tidak tersedia (status berubah).');
                }

                // Check for overlapping bookings in the database
                $overlapCount = Booking::where('vehicle_id', $this->vehicleId)
                    ->where(function ($query) use ($start, $end) {
                        $query->whereBetween('start_datetime', [$start, $end])
                              ->orWhereBetween('end_datetime', [$start, $end])
                              ->orWhere(function ($q) use ($start, $end) {
                                  $q->where('start_datetime', '<=', $start)
                                    ->where('end_datetime', '>=', $end);
                              });
                    })
                    ->count();

                if ($overlapCount > 0) {
                    throw new \Exception('Kendaraan sudah dibooking pada tanggal tersebut (overlap).');
                }

                // Create the booking record
                $booking = Booking::create([
                    'client_id' => $this->clientId,
                    'vehicle_id' => $this->vehicleId,
                    'start_datetime' => $start,
                    'end_datetime' => $end,
                    'status' => 'confirmed',
                ]);

                // Update vehicle status to PO
                $vehicle->status = 'po';
                $vehicle->save();

                // Automatically generate Invoice for this booking (Phase 3 flow)
                // Cost calculation: Rp 1,500,000 per day
                $days = max(1, $start->diffInDays($end) + 1);
                $totalAmount = $days * 1500000;

                Invoice::create([
                    'booking_id' => $booking->id,
                    'total_amount' => $totalAmount,
                    'remaining_balance' => $totalAmount,
                    'status' => 'unpaid',
                ]);
            });

            session()->flash('message', 'Booking successfully created! Vehicle status is now PO.');
            return $this->redirect('/fleet', navigate: true);

        } catch (\Exception $e) {
            $this->addError('vehicleId', $e->getMessage());
        }
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
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="/fleet" class="ml-1 text-sm font-semibold text-slate-700 hover:text-blue-600 md:ml-2 transition">Fleet</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-bold text-blue-600 md:ml-2">Booking</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl border border-slate-200 shadow-xl space-y-6">
        <div>
            <h3 class="text-xl font-extrabold text-slate-900">Create New Vehicle Booking</h3>
            <p class="text-sm text-slate-500">Book available luxury vehicles and automatically generate invoices for processing.</p>
        </div>

        <form wire:submit="createBooking" class="space-y-6">
            <!-- Client Select -->
            <div>
                <label for="clientId" class="block text-sm font-bold text-slate-700 mb-1">Select Client</label>
                <select wire:model="clientId" id="clientId" required
                        class="block w-full px-4 py-3 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-sm shadow-sm cursor-pointer">
                    <option value="">-- Select Client --</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->company ?? 'Personal' }})</option>
                    @endforeach
                </select>
                @error('clientId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Vehicle Select (Only available) -->
            <div>
                <label for="vehicleId" class="block text-sm font-bold text-slate-700 mb-1">Select Vehicle (Only Available)</label>
                <select wire:model="vehicleId" id="vehicleId" required
                        class="block w-full px-4 py-3 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-sm shadow-sm cursor-pointer">
                    <option value="">-- Select Vehicle --</option>
                    @foreach($vehicles as $v)
                        <option value="{{ $v->id }}">{{ $v->brand }} {{ $v->model }} - {{ $v->plate }}</option>
                    @endforeach
                </select>
                @error('vehicleId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="startDate" class="block text-sm font-bold text-slate-700 mb-1">Start Date</label>
                    <input wire:model="startDate" id="startDate" type="date" required
                           class="block w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-sm shadow-sm">
                    @error('startDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="endDate" class="block text-sm font-bold text-slate-700 mb-1">End Date</label>
                    <input wire:model="endDate" id="endDate" type="date" required
                           class="block w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-sm shadow-sm">
                    @error('endDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Pricing Preview Note -->
            <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl text-xs text-blue-800">
                <span class="font-bold">Operational Note:</span> Luxury rentals are calculated at a standard price of Rp 1,500,000 per day. Invoices will be instantly generated upon booking completion.
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                        class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition">
                    Confirm Booking & Generate Invoice
                </button>
            </div>
        </form>
    </div>
</div>