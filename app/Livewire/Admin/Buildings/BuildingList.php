<?php

namespace App\Livewire\Admin\Buildings;

use App\Models\Building;
use Livewire\Component;
use Livewire\WithPagination;

class BuildingList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function toggleActive(Building $building): void
    {
        $building->update(['is_active' => ! $building->is_active]);
        session()->flash('success', 'Status gedung berhasil diubah.');
    }

    public function delete(Building $building): void
    {
        $building->delete();
        session()->flash('success', 'Gedung berhasil dihapus.');
    }

    public function render()
    {
        $buildings = Building::withCount('rooms')
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            }))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.buildings.list', compact('buildings'))->layout('layouts.admin');
    }
}
