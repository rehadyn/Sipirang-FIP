<div>
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.buildings.index') }}" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-900">{{ $isEditing ? 'Edit Gedung' : 'Tambah Gedung' }}</h1>
    </div>

    <div class="max-w-lg">
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6 space-y-5">

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Nama Gedung <span class="text-red-500">*</span></label>
                <input wire:model="name" type="text" placeholder="cth. Gedung A FIP"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400 @error('name') border-red-300 @enderror">
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Kode <span class="text-red-500">*</span></label>
                <input wire:model="code" type="text" placeholder="cth. GED-A"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400 @error('code') border-red-300 @enderror">
                @error('code')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Alamat</label>
                <textarea wire:model="address" rows="2" placeholder="Alamat lengkap gedung..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400 resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Jumlah Lantai</label>
                <input wire:model="floor_count" type="number" min="1" placeholder="cth. 4"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Deskripsi</label>
                <textarea wire:model="description" rows="2" placeholder="Deskripsi singkat gedung..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400 resize-none"></textarea>
            </div>

            <label class="flex items-center gap-2 cursor-pointer">
                <input wire:model="is_active" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600">
                <span class="text-sm text-slate-700">Gedung Aktif</span>
            </label>

            <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
                <button wire:click="save" wire:loading.attr="disabled"
                    class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                    <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Simpan Perubahan' : 'Tambah Gedung' }}</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
                <a href="{{ route('admin.buildings.index') }}" class="rounded-xl border border-slate-200 px-6 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Batal</a>
            </div>
        </div>
    </div>
</div>
