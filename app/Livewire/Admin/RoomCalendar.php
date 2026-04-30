<?php

namespace App\Livewire\Admin;

use App\Livewire\Guest\RoomCalendar as BaseCalendar;

class RoomCalendar extends BaseCalendar
{
    public function render()
    {
        return view('livewire.room-calendar', $this->buildViewData(true))
            ->layout('layouts.admin');
    }
}
