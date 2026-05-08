<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\BlockedDate;
use App\Models\Building;
use App\Models\Room;
use Livewire\Component;

class RoomForm extends Component
{
    public ?Room $room = null;
    public bool $isEditing = false;

    public int|string $building_id = '';
    public string $name = '';
    public string $code = '';
    public string $floor = '';
    public int|string $capacity = '';
    public string $room_type = 'kelas';
    public bool $requires_ktp = false;
    public string $description = '';
    public string $rules = '';
    public bool $is_active = true;
    public int|string $sort_order = 0;

    // Blocked Dates
    public string $newBlockedDate = '';
    public string $newBlockedReason = '';

    public function mount(?Room $room = null): void
    {
        if ($room && $room->exists) {
            $this->isEditing = true;
            $this->room = $room;
            $this->fill($room->only([
                'building_id', 'name', 'code', 'floor', 'capacity',
                'room_type', 'requires_ktp', 'description', 'rules', 'is_active', 'sort_order'
            ]));
        }
    }

    public function save(): void
    {
        $data = $this->validate([
            'building_id'  => ['required', 'exists:buildings,id'],
            'name'         => ['required', 'string', 'max:255'],
            'code'         => ['required', 'string', 'max:20'],
            'floor'        => ['nullable', 'string', 'max:10'],
            'capacity'     => ['nullable', 'integer', 'min:1'],
            'room_type'    => ['required', 'in:' . implode(',', Room::TYPES)],
            'requires_ktp' => ['boolean'],
            'description'  => ['nullable', 'string'],
            'rules'        => ['nullable', 'string'],
            'is_active'    => ['boolean'],
            'sort_order'   => ['integer', 'min:0'],
        ]);

        if ($this->isEditing) {
            $this->room->update($data);
            session()->flash('success', 'Ruangan berhasil diperbarui.');
        } else {
            Room::create($data);
            session()->flash('success', 'Ruangan berhasil ditambahkan.');
        }

        $this->redirectRoute('admin.rooms.index');
    }

    public function addBlockedDate(): void
    {
        $this->validate([
            'newBlockedDate'   => ['required', 'date', 'after_or_equal:today'],
            'newBlockedReason' => ['nullable', 'string', 'max:255'],
        ], [], [
            'newBlockedDate'   => 'tanggal blokir',
            'newBlockedReason' => 'keterangan',
        ]);

        abort_unless($this->isEditing && $this->room, 403);

        BlockedDate::firstOrCreate(
            ['room_id' => $this->room->id, 'blocked_date' => $this->newBlockedDate],
            ['reason' => $this->newBlockedReason ?: null]
        );

        $this->newBlockedDate = '';
        $this->newBlockedReason = '';
        $this->room->refresh();
    }

    public function removeBlockedDate(int $id): void
    {
        abort_unless($this->isEditing && $this->room, 403);

        BlockedDate::where('id', $id)->where('room_id', $this->room->id)->delete();
        $this->room->refresh();
    }

    public function render()
    {
        $buildings  = Building::orderBy('name')->get();
        $roomTypes  = Room::TYPES;
        $blockedDates = $this->isEditing
            ? $this->room->blockedDates()->orderBy('blocked_date')->get()
            : collect();

        return view('livewire.admin.rooms.form', compact('buildings', 'roomTypes', 'blockedDates'))
            ->layout('layouts.admin');
    }
}
