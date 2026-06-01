<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

new #[Title('Fleet Detail - Golden Bird')] class extends Component
{
    public Vehicle $vehicle;
    public string $newStatus = '';
    public bool $isUpdating = false;

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
        $this->newStatus = $vehicle->status;
    }

    public function toggleUpdate()
    {
        if (!Auth::user()?->hasRole('ops')) {
            abort(403, 'Unauthorized. Only Operations Head can update status.');
        }
        $this->isUpdating = !$this->isUpdating;
    }

    public function saveStatus()
    {
        if (!Auth::user()?->hasRole('ops')) {
            abort(403, 'Unauthorized. Only Operations Head can update status.');
        }

        $this->validate([
            'newStatus' => 'required|in:available,po,maintenance,hold',
        ]);

        $this->vehicle->status = $this->newStatus;
        $this->vehicle->save();
        $this->isUpdating = false;

        session()->flash('message', 'Vehicle status updated successfully.');
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
                    <span class="ml-1 text-sm font-bold text-blue-600 md:ml-2">Vehicle Profile ({{ $vehicle->plate }})</span>
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
        <!-- Main Details Card -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 lg:col-span-2 space-y-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900">{{ $vehicle->brand }}</h3>
                    <p class="text-sm text-slate-500">{{ $vehicle->model ?? 'Model variant unspecified' }}</p>
                </div>
                <div>
                    @php
                        $statuses = [
                            'available' => 'bg-green-100 text-green-800 border-green-200',
                            'po' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'maintenance' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'hold' => 'bg-red-100 text-red-800 border-red-200',
                        ];
                        $color = $statuses[$vehicle->status] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                    @endphp
                    <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold border {{ $color }}">
                        {{ strtoupper($vehicle->status) }}
                    </span>
                </div>
            </div>

            <!-- Vehicle Metadata -->
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100 text-sm">
                <div>
                    <span class="text-slate-400 font-medium block">License Plate</span>
                    <span class="font-mono font-bold text-slate-950 text-base bg-slate-50 px-2 py-1 rounded border border-slate-200 inline-block mt-1">
                        {{ $vehicle->plate }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">Registration Status</span>
                    <span class="font-semibold text-slate-800 block mt-1">Registered & Valid</span>
                </div>
            </div>

            <!-- Booking list simple preview -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Booking History</h4>
                @php
                    $bookings = $vehicle->bookings()->with('client')->latest()->get();
                @endphp
                @if($bookings->count() > 0)
                    <div class="space-y-3">
                        @foreach($bookings as $b)
                            <div class="flex justify-between items-center p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                                <div>
                                    <p class="font-bold text-slate-900">Client: <a href="/clients/{{ $b->client->id }}" wire:navigate class="text-blue-600 hover:text-blue-800 hover:underline transition">{{ $b->client->name }}</a></p>
                                    <p class="text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($b->start_datetime)->format('d M Y') }} - {{ \Carbon\Carbon::parse($b->end_datetime)->format('d M Y') }}</p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full font-bold bg-blue-100 text-blue-800">
                                    {{ strtoupper($b->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">No bookings recorded yet for this vehicle.</p>
                @endif
            </div>
        </div>

        <!-- Operations Controls Card (Update status - ops only) -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-6">
            <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Control Panel</h4>

            @if(Auth::user()?->hasRole('ops'))
                <div class="space-y-4">
                    <p class="text-xs text-slate-600">As Operations Head, you can manually override the vehicle operational state here.</p>

                    @if($isUpdating)
                        <div class="space-y-3">
                            <label for="newStatus" class="block text-xs font-bold text-slate-500 uppercase">Select Target State</label>
                            <select wire:model="newStatus" id="newStatus"
                                    class="block w-full px-3 py-2 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-sm shadow-sm cursor-pointer">
                                <option value="available">Available</option>
                                <option value="po">Pre-Ordered (PO)</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="hold">On Hold</option>
                            </select>

                            <div class="flex space-x-2 pt-2">
                                <button wire:click="saveStatus"
                                        class="flex-1 py-2 px-3 border border-transparent rounded-xl shadow text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 transition">
                                    Save Changes
                                </button>
                                <button wire:click="toggleUpdate"
                                        class="flex-1 py-2 px-3 border border-slate-300 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 transition">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    @else
                        <button wire:click="toggleUpdate"
                                class="w-full flex justify-center py-2.5 px-4 border border-blue-600 rounded-xl text-xs font-bold text-blue-600 bg-white hover:bg-blue-50 transition">
                            Update Operational Status
                        </button>
                    @endif
                </div>
            @else
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-500">
                    <svg class="w-5 h-5 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Only users with the <span class="font-bold text-blue-700 bg-blue-50 px-1 py-0.5 rounded">Operations Head</span> role have access to manual override vehicle status controls.
                </div>
            @endif
        </div>
    </div>
</div>