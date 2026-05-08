<div>
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.rooms.index') }}" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-900">{{ $isEditing ? 'Edit Ruangan' : 'Tambah Ruangan' }}</h1>
    </div>

    <div class="grid grid-cols-1 gap-6 {{ $isEditing ? 'lg:grid-cols-3' : '' }}">
    <div class="{{ $isEditing ? 'lg:col-span-2' : 'max-w-3xl' }}">
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

    {{-- Sidebar: Blocked Dates (hanya tampil saat edit) --}}
    @if($isEditing)
    <div class="lg:col-span-1">
    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6 lg:sticky lg:top-6">
        <div class="flex items-center gap-2 mb-5">
            <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            <h2 class="text-sm font-semibold text-slate-700">Tanggal Diblokir</h2>
            <span class="text-xs text-slate-400 font-normal">— Ruangan tidak bisa dipesan pada tanggal berikut</span>
        </div>

        {{-- Daftar tanggal yang sudah diblokir --}}
        <div class="mb-5 space-y-2">
            @forelse($blockedDates as $bd)
            <div class="flex items-center gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-2.5">
                <div class="flex-1">
                    <span class="text-sm font-semibold text-red-800">{{ \Carbon\Carbon::parse($bd->blocked_date)->translatedFormat('l, d F Y') }}</span>
                    @if($bd->reason)
                    <span class="ml-2 text-xs text-red-500">— {{ $bd->reason }}</span>
                    @endif
                </div>
                <button wire:click="removeBlockedDate({{ $bd->id }})"
                    wire:confirm="Hapus tanggal blokir ini?"
                    class="rounded-lg p-1.5 text-red-400 hover:bg-red-100 hover:text-red-700 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
            @empty
            <p class="text-sm text-slate-400 italic">Belum ada tanggal yang diblokir.</p>
            @endforelse
        </div>

        {{-- Form tambah tanggal blokir --}}
        <div class="border-t border-slate-100 pt-5">
            <p class="text-xs font-medium text-slate-500 mb-3">Tambah Tanggal Blokir</p>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-shrink-0">
                    <input wire:model="newBlockedDate" type="date" min="{{ now()->format('Y-m-d') }}"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-red-400 @error('newBlockedDate') border-red-300 @enderror">
                    @error('newBlockedDate')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex-1">
                    <input wire:model="newBlockedReason" type="text" placeholder="Keterangan (opsional, cth: Hari Libur, Acara Fakultas)"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-red-400">
                </div>
                <button wire:click="addBlockedDate" wire:loading.attr="disabled"
                    class="flex-shrink-0 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-500 transition flex items-center gap-2">
                    <svg class="h-4 w-4" wire:loading.remove wire:target="addBlockedDate" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <svg class="h-4 w-4 animate-spin" wire:loading wire:target="addBlockedDate" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span wire:loading.remove wire:target="addBlockedDate">Blokir Tanggal</span>
                    <span wire:loading wire:target="addBlockedDate">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
    </div>
    @endif
    </div>
</div>
