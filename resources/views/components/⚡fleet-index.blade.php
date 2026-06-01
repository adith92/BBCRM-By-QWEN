<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

new #[Title('Fleet Management - Golden Bird')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';

    // Popup Modal states
    public ?int $selectedVehicleId = null;
    public ?Vehicle $selectedVehicle = null;
    public bool $showDetailModal = false;
    public string $newStatus = '';
    public bool $isUpdatingStatus = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openDetail(int $id)
    {
        return $this->redirect("/fleet/{$id}", navigate: true);
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedVehicle = null;
        $this->selectedVehicleId = null;
    }

    public function toggleUpdateStatus()
    {
        if (!Auth::user()?->hasRole('ops')) {
            abort(403, 'Unauthorized. Only Operations Head can update status.');
        }
        $this->isUpdatingStatus = !$this->isUpdatingStatus;
    }

    public function saveStatus()
    {
        if (!Auth::user()?->hasRole('ops')) {
            abort(403, 'Unauthorized. Only Operations Head can update status.');
        }

        $this->validate([
            'newStatus' => 'required|in:available,po,maintenance,hold',
        ]);

        $this->selectedVehicle->status = $this->newStatus;
        $this->selectedVehicle->save();
        $this->isUpdatingStatus = false;
        
        session()->flash('modal_message', 'Vehicle status updated successfully.');
    }

    public function with(): array
    {
        $query = Vehicle::query();

        if ($this->search !== '') {
            $query->where('plate', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        return [
            'vehicles' => $query->latest()->paginate(10)
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
                    <span class="ml-1 text-sm font-bold text-blue-600 md:ml-2">Fleet</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Fleet Operations Directory</h2>
            <p class="text-slate-500 text-sm">Real-time status tracking and dispatcher manager for Golden Bird fleet.</p>
        </div>
        <div class="flex items-center space-x-3">
            @can('booking.view')
                <a href="/bookings/create" class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Booking
                </a>
            @endcan
        </div>
    </div>

    <!-- Filter & Search Panel -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col md:flex-row gap-4 items-center">
        <!-- Search -->
        <div class="relative w-full md:flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                   class="block w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-sm shadow-sm"
                   placeholder="Search by plate number (e.g. B 1001 SBA)...">
        </div>

        <!-- Filter -->
        <div class="w-full md:w-64 flex items-center space-x-2">
            <label class="text-xs font-bold text-slate-500 uppercase whitespace-nowrap">Filter Status</label>
            <select wire:model.live="statusFilter"
                    class="block w-full px-3 py-2.5 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-sm shadow-sm cursor-pointer">
                <option value="all">All Vehicles</option>
                <option value="available">Available</option>
                <option value="po">Pre-Ordered (PO)</option>
                <option value="maintenance">Maintenance</option>
                <option value="hold">On Hold</option>
            </select>
        </div>
    </div>

    <!-- Vehicles Directory Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Brand & Model</th>
                        <th class="px-6 py-4 text-left">Plate Number</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($vehicles as $vehicle)
                        <tr class="hover:bg-slate-50 transition cursor-pointer" wire:click="openDetail({{ $vehicle->id }})">
                            <td class="px-6 py-4 font-semibold text-slate-900">
                                {{ $vehicle->brand }} <span class="font-normal text-slate-500">{{ $vehicle->model }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-800 border border-slate-200 font-mono font-bold text-xs">
                                    {{ $vehicle->plate }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statuses = [
                                        'available' => 'bg-green-100 text-green-800 border-green-200',
                                        'po' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'maintenance' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'hold' => 'bg-red-100 text-red-800 border-red-200',
                                    ];
                                    $color = $statuses[$vehicle->status] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $color }}">
                                    {{ strtoupper($vehicle->status) }}
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
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                No vehicles found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($vehicles->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $vehicles->links() }}
            </div>
        @endif
    </div>

    <!-- Fleet Detail Popup Modal -->
    @if($showDetailModal && $selectedVehicle)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-6 border border-slate-200 animate-fade-in max-h-[90vh] overflow-y-auto">
                
                <!-- Modal Header -->
                <div class="flex justify-between items-start border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-900">{{ $selectedVehicle->brand }}</h3>
                        <p class="text-xs text-slate-500">{{ $selectedVehicle->model ?? 'Model variant unspecified' }}</p>
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
                    <div class="md:col-span-2 space-y-4 text-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-slate-400 font-medium text-xs uppercase block">License Plate</span>
                                <span class="font-mono font-bold text-slate-950 text-sm bg-slate-50 px-2 py-1 rounded border border-slate-200 inline-block mt-1">
                                    {{ $selectedVehicle->plate }}
                                </span>
                            </div>
                            <div>
                                <span class="text-slate-400 font-medium text-xs uppercase block">Operational Status</span>
                                @php
                                    $statuses = [
                                        'available' => 'bg-green-100 text-green-800 border-green-200',
                                        'po' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'maintenance' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'hold' => 'bg-red-100 text-red-800 border-red-200',
                                    ];
                                    $color = $statuses[$selectedVehicle->status] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $color }} mt-1">
                                    {{ strtoupper($selectedVehicle->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Booking History -->
                        <div class="pt-4 border-t border-slate-100 space-y-3">
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Booking History</h4>
                            @php
                                $bookings = $selectedVehicle->bookings()->with('client')->latest()->get();
                            @endphp
                            @if($bookings->count() > 0)
                                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                                    @foreach($bookings as $b)
                                        <div class="flex justify-between items-center p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                                            <div>
                                                <p class="font-bold text-slate-900">Client: {{ $b->client->name }}</p>
                                                <p class="text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($b->start_datetime)->format('d M Y') }} - {{ \Carbon\Carbon::parse($b->end_datetime)->format('d M Y') }}</p>
                                            </div>
                                            <span class="px-2 py-0.5 rounded-full font-bold bg-blue-100 text-blue-800">
                                                {{ strtoupper($b->status) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-slate-400 italic">No bookings recorded yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Control override (Ops only) -->
                    <div class="space-y-4 bg-slate-50 p-4 border border-slate-200 rounded-xl max-h-64">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Control Panel</h4>

                        @if(Auth::user()?->hasRole('ops'))
                            <div class="space-y-3 text-xs">
                                <p class="text-slate-500">As Operations Head, you can override fleet states.</p>

                                @if($isUpdatingStatus)
                                    <div class="space-y-2">
                                        <select wire:model="newStatus"
                                                class="block w-full px-2 py-1.5 border border-slate-300 bg-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-xs shadow-sm cursor-pointer">
                                            <option value="available">Available</option>
                                            <option value="po">Pre-Ordered (PO)</option>
                                            <option value="maintenance">Maintenance</option>
                                            <option value="hold">On Hold</option>
                                        </select>
                                        <div class="flex space-x-1 pt-1">
                                            <button wire:click="saveStatus"
                                                    class="flex-1 py-1.5 border border-transparent rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition font-semibold">
                                                Save
                                            </button>
                                            <button wire:click="toggleUpdateStatus"
                                                    class="flex-1 py-1.5 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition font-semibold">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <button wire:click="toggleUpdateStatus"
                                            class="w-full py-2 border border-blue-600 rounded-lg text-xs font-bold text-blue-600 bg-white hover:bg-blue-50 transition">
                                        Override Status
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="text-[11px] text-slate-400">
                                <span class="material-symbols-outlined text-[16px] text-slate-400 mr-1">lock</span>
                                Only Ops Role has manual override permissions.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>