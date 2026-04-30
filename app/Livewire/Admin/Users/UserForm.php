<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class UserForm extends Component
{
    public ?User $user = null;
    public bool $isEditing = false;

    public string $name = '';
    public string $email = '';
    public string $role = 'admin';
    public bool $is_active = true;
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(?User $user = null): void
    {
        if ($user && $user->exists) {
            $this->isEditing = true;
            $this->user = $user;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
            $this->is_active = $user->is_active;
        }
    }

    public function save(): void
    {
        $rules = [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255'],
            'role'      => ['required', 'in:admin,sysadmin'],
            'is_active' => ['boolean'],
        ];

        if ($this->isEditing) {
            $rules['email'][] = 'unique:users,email,' . $this->user->id;
            if ($this->password) {
                $rules['password'] = ['min:8', 'confirmed'];
            }
        } else {
            $rules['email'][] = 'unique:users,email';
            $rules['password'] = ['required', 'min:8', 'confirmed'];
        }

        $data = $this->validate($rules);

        $payload = [
            'name'      => $this->name,
            'email'     => $this->email,
            'role'      => $this->role,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $payload['password'] = Hash::make($this->password);
        }

        if ($this->isEditing) {
            $this->user->update($payload);
            session()->flash('success', 'User berhasil diperbarui.');
        } else {
            User::create($payload);
            session()->flash('success', 'User berhasil ditambahkan.');
        }

        $this->redirectRoute('admin.users.index');
    }

    public function render()
    {
        $roles = User::ROLES;
        return view('livewire.admin.users.form', compact('roles'))->layout('layouts.admin');
    }
}
