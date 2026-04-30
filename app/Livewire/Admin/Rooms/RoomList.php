<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\Room;
use App\Models\Building;
use Livewire\Component;
use Livewire\WithPagination;

class RoomList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $buildingFilter = '';
    public string $statusFilter = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function toggleActive(Room $room): void
    {
        $room->update(['is_active' => ! $room->is_active]);
        session()->flash('success', 'Status ruangan berhasil diubah.');
    }

    public function delete(Room $room): void
    {
        $room->delete();
        session()->flash('success', 'Ruangan berhasil dihapus.');
    }

    public function render()
    {
        $rooms = Room::with('building')
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            }))
            ->when($this->buildingFilter, fn($q) => $q->where('building_id', $this->buildingFilter))
            ->when($this->statusFilter !== '', fn($q) => $q->where('is_active', $this->statusFilter === '1'))
            ->orderBy('sort_order')->orderBy('name')
            ->paginate(15);

        $buildings = Building::orderBy('name')->get();

        return view('livewire.admin.rooms.list', compact('rooms', 'buildings'))->layout('layouts.admin');
    }
}
