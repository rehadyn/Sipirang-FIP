<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function toggleActive(User $user): void
    {
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat mengubah status akun sendiri.');
            return;
        }
        $user->update(['is_active' => ! $user->is_active]);
        session()->flash('success', 'Status user berhasil diubah.');
    }

    public function delete(User $user): void
    {
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun sendiri.');
            return;
        }
        $user->delete();
        session()->flash('success', 'User berhasil dihapus.');
    }

    public function render()
    {
        $users = User::withTrashed()
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            }))
            ->when($this->roleFilter, fn($q) => $q->where('role', $this->roleFilter))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.admin.users.list', compact('users'))->layout('layouts.admin');
    }
}
