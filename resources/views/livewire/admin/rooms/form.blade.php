<div>
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.rooms.index') }}" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-900">{{ $isEditing ? 'Edit Ruangan' : 'Tambah Ruangan' }}</h1>
    </div>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Gedung <span class="text-red-500">*</span></label>
                    <select wire:model="building_id" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-400 @error('building_id') border-red-300 @enderror">
                        <option value="">Pilih Gedung</option>
                        @foreach($buildings as $building)
                        <option value="{{ $building->id }}">{{ $building->name }}</option>
                        @endforeach
                    </select>
                    @error('building_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Nama Ruangan <span class="text-red-500">*</span></label>
                    <input wire:model="name" type="text" placeholder="cth. Ruang 101"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400 @error('name') border-red-300 @enderror">
                    @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Kode <span class="text-red-500">*</span></label>
                    <input wire:model="code" type="text" placeholder="cth. R101"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400 @error('code') border-red-300 @enderror">
                    @error('code')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Lantai</label>
                    <input wire:model="floor" type="text" placeholder="cth. 1 / Dasar"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Kapasitas (orang)</label>
                    <input wire:model="capacity" type="number" min="1" placeholder="cth. 40"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Tipe Ruangan <span class="text-red-500">*</span></label>
                    <select wire:model="room_type" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-400">
                        @foreach($roomTypes as $type)
                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Urutan Tampil</label>
                    <input wire:model="sort_order" type="number" min="0" placeholder="0"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Deskripsi</label>
                    <textarea wire:model="description" rows="3" placeholder="Deskripsi singkat ruangan..."
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400 resize-none"></textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Aturan Penggunaan</label>
                    <textarea wire:model="rules" rows="3" placeholder="Aturan penggunaan ruangan..."
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400 resize-none"></textarea>
                </div>

                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model="requires_ktp" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600">
                        <span class="text-sm text-slate-700">Wajib KTP</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model="is_active" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600">
                        <span class="text-sm text-slate-700">Aktif</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-5">
                <button wire:click="save" wire:loading.attr="disabled"
                    class="rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                    <span wire:loading.remove wire:target="save">{{ $isEditing ? 'Simpan Perubahan' : 'Tambah Ruangan' }}</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
                <a href="{{ route('admin.rooms.index') }}" class="rounded-xl border border-slate-200 px-6 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Batal</a>
            </div>
        </div>
    </div>
</div>
