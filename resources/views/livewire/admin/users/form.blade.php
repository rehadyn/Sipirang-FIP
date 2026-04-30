<div>
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-900">{{ $isEditing ? 'Edit User' : 'Tambah User' }}</h1>
    </div>

    <div class="max-w-lg">
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6 space-y-5">

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input wire:model="name" type="text" placeholder="cth. Budi Santoso"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400 @error('name') border-red-300 @enderror">
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input wire:model="email" type="email" placeholder="user@sipirang.local"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400 @error('email') border-red-300 @enderror">
                @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Role <span class="text-red-500">*</span></label>
                <select wire:model="role" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-400">
                    @foreach($roles as $r)
                    <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">Sysadmin dapat mengelola users. Admin hanya untuk manajemen booking dan ruangan.</p>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">
                    Password {{ $isEditing ? '(kosongkan jika tidak diubah)' : '' }} <span class="text-red-500">*</span>
                </label>
                <input wire:model="password" type="password" placeholder="Minimal 8 karakter"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400 @error('password') border-red-300 @enderror">
                @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Konfirmasi Password</label>
                <input wire:model="password_confirmation" type="password" placeholder="Ulangi password"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400">
            </div>

            <label class="flex items-center gap-2 cursor-pointer">
                <input wire:model="is_active" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600">
                <span class="text-sm text-slate-700">Akun Aktif</span>
            </label>

            <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
                <button wire:click="save" wire:loading.attr="disabled"
                    class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                    <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Simpan Perubahan' : 'Tambah User' }}</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
                <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-200 px-6 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Batal</a>
            </div>
        </div>
    </div>
</div>
