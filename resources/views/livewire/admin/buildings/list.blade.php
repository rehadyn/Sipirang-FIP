<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Gedung</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data gedung / bangunan</p>
        </div>
        <a href="{{ route('admin.buildings.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Gedung
        </a>
    </div>

    <div class="mb-4 rounded-2xl bg-white border border-slate-200 p-4 shadow-sm">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau kode gedung..."
            class="w-full max-w-sm rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400">
    </div>

    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Gedung</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Kode</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Lantai</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Ruangan</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($buildings as $building)
                <tr class="hover:bg-slate-50/70 transition">
                    <td class="px-5 py-4">
                        <p class="font-medium text-slate-800">{{ $building->name }}</p>
                        @if($building->address)<p class="text-xs text-slate-400">{{ $building->address }}</p>@endif
                    </td>
                    <td class="px-5 py-4 text-slate-600">{{ $building->code }}</td>
                    <td class="px-5 py-4 text-slate-600">{{ $building->floor_count ? $building->floor_count . ' lantai' : '—' }}</td>
                    <td class="px-5 py-4">
                        <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700">{{ $building->rooms_count }} ruangan</span>
                    </td>
                    <td class="px-5 py-4">
                        <button wire:click="toggleActive({{ $building->id }})"
                            class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $building->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }} transition hover:opacity-80">
                            {{ $building->is_active ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.buildings.edit', $building) }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">Edit</a>
                            <button wire:click="delete({{ $building->id }})" wire:confirm="Yakin hapus gedung ini?"
                                class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100 transition">Hapus</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400 text-sm">Tidak ada gedung ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($buildings->hasPages())
        <div class="border-t border-slate-100 px-5 py-4">{{ $buildings->links() }}</div>
        @endif
    </div>
</div>
