<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Laporan Bulanan</h1>
        <p class="text-sm text-slate-500 mt-1">Generate dan unduh laporan booking dalam format Excel</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Filter Panel --}}
        <div class="lg:col-span-1">
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-slate-700 mb-5">Pilih Periode</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Bulan</label>
                        <select wire:model="month" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-400">
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $mn)
                            <option value="{{ $i + 1 }}">{{ $mn }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Tahun</label>
                        <select wire:model="year" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-indigo-400">
                            @foreach($years as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button wire:click="preview" wire:loading.attr="disabled"
                        class="w-full rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition flex items-center justify-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span wire:loading.remove wire:target="preview">Preview Data</span>
                        <span wire:loading wire:target="preview">Memuat...</span>
                    </button>
                </div>

                @if($previewed)
                <div class="mt-5 border-t border-slate-100 pt-5">
                    <p class="text-xs font-semibold text-slate-500 uppercase mb-3">Ringkasan</p>
                    <div class="space-y-2">
                        @foreach([
                            ['label' => 'Total Booking', 'key' => 'total', 'color' => 'text-slate-700'],
                            ['label' => 'Disetujui', 'key' => 'approved', 'color' => 'text-emerald-600'],
                            ['label' => 'Ditolak', 'key' => 'rejected', 'color' => 'text-red-600'],
                            ['label' => 'Menunggu Proses', 'key' => 'pending', 'color' => 'text-amber-600'],
                            ['label' => 'Kedaluwarsa', 'key' => 'expired', 'color' => 'text-slate-400'],
                            ['label' => 'Check-in', 'key' => 'checked_in', 'color' => 'text-blue-600'],
                        ] as $stat)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500">{{ $stat['label'] }}</span>
                            <span class="font-bold {{ $stat['color'] }}">{{ $summary[$stat['key']] ?? 0 }}</span>
                        </div>
                        @endforeach
                    </div>

                    @if(($summary['total'] ?? 0) > 0)
                    <a href="{{ route('admin.reports.download', ['month' => $month, 'year' => $year]) }}"
                       class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-500 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Unduh Excel (.xlsx)
                    </a>
                    @else
                    <p class="mt-4 text-center text-xs text-slate-400">Tidak ada data untuk periode ini</p>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- Info Panel --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Format Laporan Excel</h2>
                <div class="space-y-4">
                    <div class="flex gap-4 rounded-xl border border-indigo-100 bg-indigo-50 p-4">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-indigo-100">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-indigo-800">Header — Kop Surat</p>
                            <p class="text-xs text-indigo-600 mt-1">Nama instansi, judul laporan, dan periode laporan</p>
                        </div>
                    </div>
                    <div class="flex gap-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-slate-100">
                            <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M3 6h18M3 18h18"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Isi — Tabel Data</p>
                            <p class="text-xs text-slate-500 mt-1">No, Tiket, Nama, Tipe, Ruangan, Gedung, Tanggal, Sesi, Status, Tgl. Review, Reviewer</p>
                        </div>
                    </div>
                    <div class="flex gap-4 rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-100">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-emerald-800">Footer — Rekapitulasi & TTD</p>
                            <p class="text-xs text-emerald-600 mt-1">Total per status, tanggal cetak, dan kolom tanda tangan penanggung jawab</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
