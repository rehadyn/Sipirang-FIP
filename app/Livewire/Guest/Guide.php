<?php

namespace App\Livewire\Guest;

use Livewire\Component;

class Guide extends Component
{
    public function render()
    {
        return view('livewire.guest.guide')->layout('layouts.guest');
    }
}
