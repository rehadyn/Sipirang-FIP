<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Users</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola akun admin dan sysadmin <span class="text-xs rounded-full bg-violet-100 text-violet-700 px-2 py-0.5 font-medium ml-1">Sysadmin Only</span></p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah User
        </a>
    </div>

    <div class="mb-4 flex flex-wrap items-center gap-3 rounded-2xl bg-white border border-slate-200 p-4 shadow-sm">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau email..."
            class="flex-1 min-w-48 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400">
        <select wire:model.live="roleFilter" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none">
            <option value="">Semua Role</option>
            <option value="admin">Admin</option>
            <option value="sysadmin">Sysadmin</option>
        </select>
    </div>

    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">User</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Role</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Bergabung</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50/70 transition {{ $user->trashed() ? 'opacity-60' : '' }}">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full
                                {{ $user->role === 'sysadmin' ? 'bg-violet-100 text-violet-700' : 'bg-indigo-100 text-indigo-700' }} text-sm font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-slate-800">{{ $user->name }}
                                    @if($user->id === auth()->id()) <span class="text-xs text-slate-400">(Anda)</span> @endif
                                </p>
                                <p class="text-xs text-slate-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold
                            {{ $user->role === 'sysadmin' ? 'bg-violet-100 text-violet-700' : 'bg-indigo-100 text-indigo-700' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        @if($user->trashed())
                        <span class="rounded-full bg-slate-100 text-slate-500 px-2.5 py-1 text-xs font-semibold">Dihapus</span>
                        @else
                        <button wire:click="toggleActive({{ $user->id }})"
                            class="rounded-full px-2.5 py-1 text-xs font-semibold transition
                                {{ $user->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </button>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-xs text-slate-400">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                    <td class="px-5 py-4 text-right">
                        @if(!$user->trashed())
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">Edit</a>
                            @if($user->id !== auth()->id())
                            <button wire:click="delete({{ $user->id }})" wire:confirm="Yakin hapus user ini?"
                                class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100 transition">Hapus</button>
                            @endif
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400 text-sm">Tidak ada user ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
        <div class="border-t border-slate-100 px-5 py-4">{{ $users->links() }}</div>
        @endif
    </div>
</div>
