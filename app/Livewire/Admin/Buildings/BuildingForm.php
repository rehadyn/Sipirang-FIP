<?php

namespace App\Livewire\Admin\Buildings;

use App\Models\Building;
use Livewire\Component;

class BuildingForm extends Component
{
    public ?Building $building = null;
    public bool $isEditing = false;

    public string $name = '';
    public string $code = '';
    public string $address = '';
    public int|string $floor_count = '';
    public string $description = '';
    public bool $is_active = true;

    public function mount(?Building $building = null): void
    {
        if ($building && $building->exists) {
            $this->isEditing = true;
            $this->building = $building;
            $this->fill($building->only(['name', 'code', 'address', 'floor_count', 'description', 'is_active']));
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'name'        => ['required', 'string', 'max:255'],
            'code'        => ['required', 'string', 'max:20'],
            'address'     => ['nullable', 'string', 'max:500'],
            'floor_count' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        if ($this->isEditing) {
            $this->building->update($data);
            session()->flash('success', 'Gedung berhasil diperbarui.');
        } else {
            Building::create($data);
            session()->flash('success', 'Gedung berhasil ditambahkan.');
        }

        $this->redirectRoute('admin.buildings.index');
    }

    public function render()
    {
        return view('livewire.admin.buildings.form')->layout('layouts.admin');
    }
}
