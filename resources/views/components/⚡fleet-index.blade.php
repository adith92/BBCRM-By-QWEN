<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Models\Vehicle;

new #[Title('Fleet Management - Golden Bird')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
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

    <!-- Vehicles Grid/List -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Brand & Model</th>
                        <th class="px-6 py-4 text-left">Plate Number</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 class-right px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($vehicles as $vehicle)
                        <tr class="hover:bg-slate-50 transition">
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
                                <a href="/fleet/{{ $vehicle->id }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-800 transition">
                                    View Details &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                No vehicles found matching your criteria.
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
</div>