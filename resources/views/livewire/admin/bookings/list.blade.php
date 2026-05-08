<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Booking</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola semua pengajuan peminjaman ruangan</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-4 rounded-2xl bg-white border border-slate-200 p-4 shadow-sm space-y-3">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-48">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, tiket, WhatsApp..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 px-4 text-sm outline-none transition focus:border-indigo-400 focus:bg-white">
            </div>
            <select wire:model.live="statusFilter" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none">
                <option value="">Semua Status</option>
                @foreach($statusOptions as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            <select wire:model.live="typeFilter" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none">
                <option value="">Semua Tipe</option>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="dosen">Dosen</option>
                <option value="organisasi">Organisasi</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
        <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 pt-3">
            <span class="text-xs font-medium text-slate-500 shrink-0">Tanggal dibuat:</span>
            <div class="flex items-center gap-2">
                <input wire:model.live="dateFrom" type="date" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-indigo-400">
                <span class="text-slate-400 text-xs">–</span>
                <input wire:model.live="dateTo" type="date" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm outline-none focus:border-indigo-400">
            </div>
            @if($dateFrom || $dateTo || $search || $statusFilter || $typeFilter)
            <button wire:click="$set('dateFrom',''); $set('dateTo',''); $set('search',''); $set('statusFilter',''); $set('typeFilter','')"
                class="text-xs font-medium text-red-600 hover:text-red-700 transition flex items-center gap-1">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Reset Filter
            </button>
            @endif
        </div>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">No. Tiket</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Peminjam</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Ruangan</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Tanggal</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase text-slate-500">Dibuat</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php
                    $statusColors = ['pending_upload'=>'bg-slate-100 text-slate-600','pending_review'=>'bg-amber-100 text-amber-700','approved'=>'bg-emerald-100 text-emerald-700','rejected'=>'bg-red-100 text-red-700','expired'=>'bg-slate-100 text-slate-500','cancelled'=>'bg-slate-100 text-slate-500','checked_in'=>'bg-blue-100 text-blue-700','completed'=>'bg-indigo-100 text-indigo-700'];
                    $statusLabels = ['pending_upload'=>'Menunggu Upload','pending_review'=>'Menunggu Review','approved'=>'Disetujui','rejected'=>'Ditolak','expired'=>'Kedaluwarsa','cancelled'=>'Dibatalkan','checked_in'=>'Check-in','completed'=>'Selesai'];
                    $typeLabels = ['mahasiswa'=>'Mahasiswa','dosen'=>'Dosen','organisasi'=>'Organisasi','lainnya'=>'Lainnya'];
                    @endphp
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-5 py-4"><span class="font-mono text-xs font-semibold text-indigo-700">{{ $booking->ticket_number }}</span></td>
                        <td class="px-5 py-4">
                            <p class="font-medium text-slate-800">{{ $booking->borrower_name }}</p>
                            <p class="text-xs text-slate-400">{{ $typeLabels[$booking->borrower_type] ?? $booking->borrower_type }}</p>
                        </td>
                        <td class="px-5 py-4 text-slate-700">
                            @php $roomNames = $booking->items->pluck('room.name')->filter()->unique() @endphp
                            @if($roomNames->isEmpty()) <span class="text-slate-400">—</span>
                            @elseif($roomNames->count() === 1) {{ $roomNames->first() }}
                            @else
                                <span>{{ $roomNames->first() }}</span>
                                <span class="ml-1 rounded-full bg-slate-100 px-1.5 py-0.5 text-xs font-medium text-slate-500">+{{ $roomNames->count() - 1 }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-600">{{ $booking->items->first()?->booking_date?->translatedFormat('d M Y') ?? '—' }}</td>
                        <td class="px-5 py-4">
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-600' }}">{{ $statusLabels[$booking->status] ?? $booking->status }}</span>
                        </td>
                        <td class="px-5 py-4 text-xs text-slate-400">{{ $booking->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-12 text-center text-slate-400 text-sm">Tidak ada data booking ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
        <div class="border-t border-slate-100 px-5 py-4">{{ $bookings->links() }}</div>
        @endif
    </div>
</div>
