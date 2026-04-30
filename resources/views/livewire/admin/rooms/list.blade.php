<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Ruangan</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data ruangan peminjaman</p>
        </div>
        <a href="{{ route('admin.rooms.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Ruangan
        </a>
    </div>

    {{-- Filters --}}
    <div class="mb-4 flex flex-wrap items-center gap-3 rounded-2xl bg-white border border-slate-200 p-4 shadow-sm">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau kode ruangan..."
            class="flex-1 min-w-48 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400">
        <select wire:model.live="buildingFilter" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none">
            <option value="">Semua Gedung</option>
            @foreach($buildings as $building)
            <option value="{{ $building->id }}">{{ $building->name }}</option>
            @endforeach
        </select>
        <select wire:model.live="statusFilter" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none">
            <option value="">Semua Status</option>
            <option value="1">Aktif</option>
            <option value="0">Nonaktif</option>
        </select>
    </div>

    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Ruangan</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Gedung</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Tipe</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Kapasitas</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rooms as $room)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-5 py-4">
                            <p class="font-medium text-slate-800">{{ $room->name }}</p>
                            <p class="text-xs text-slate-400">{{ $room->code }} @if($room->floor)&bull; Lantai {{ $room->floor }}@endif</p>
                        </td>
                        <td class="px-5 py-4 text-slate-600">{{ $room->building?->name ?? '—' }}</td>
                        <td class="px-5 py-4"><span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 capitalize">{{ $room->room_type }}</span></td>
                        <td class="px-5 py-4 text-slate-600">{{ $room->capacity ? $room->capacity . ' orang' : '—' }}</td>
                        <td class="px-5 py-4">
                            <button wire:click="toggleActive({{ $room->id }})"
                                class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold transition
                                    {{ $room->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $room->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ $room->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.rooms.edit', $room) }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">Edit</a>
                                <button wire:click="delete({{ $room->id }})" wire:confirm="Yakin hapus ruangan ini?"
                                    class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100 transition">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400 text-sm">Tidak ada ruangan ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rooms->hasPages())
        <div class="border-t border-slate-100 px-5 py-4">{{ $rooms->links() }}</div>
        @endif
    </div>
</div>
