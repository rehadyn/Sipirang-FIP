<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900">Jadwal Penggunaan Ruangan</h1>
            <p class="text-sm text-zinc-500 mt-1">Kalender penggunaan ruangan yang telah disetujui.</p>
        </div>

        <div class="flex items-center gap-2 bg-white border border-zinc-200 rounded-lg p-1">
            <button wire:click="prevMonth" class="p-2 rounded-md hover:bg-zinc-100 transition text-zinc-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <span class="px-3 text-sm font-semibold text-zinc-700 min-w-[140px] text-center capitalize">
                {{ $monthLabel }}
            </span>
            <button wire:click="nextMonth" class="p-2 rounded-md hover:bg-zinc-100 transition text-zinc-600">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
        {{-- Calendar Grid --}}
        <div class="xl:col-span-3 space-y-4">
            <div class="bg-white border border-zinc-200 rounded-2xl overflow-hidden">
                {{-- Days Header --}}
                <div class="grid grid-cols-7 border-b border-zinc-200 bg-zinc-50">
                    @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                        <div class="py-2.5 text-center text-[10px] font-semibold uppercase tracking-wider text-zinc-500">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                {{-- Weeks --}}
                <div class="divide-y divide-zinc-100">
                    @foreach($weeks as $week)
                        <div class="grid grid-cols-7 divide-x divide-zinc-100">
                            @foreach($week as $day)
                                <div
                                    wire:click="selectDate('{{ $day['date'] }}')"
                                    class="min-h-[100px] md:min-h-[120px] p-2 transition cursor-pointer hover:bg-indigo-50/50
                                    {{ !$day['isCurrentMonth'] ? 'bg-zinc-50' : 'bg-white' }}
                                    {{ $selectedDate === $day['date'] ? 'ring-2 ring-inset ring-indigo-500 bg-indigo-50/50' : '' }}"
                                >
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xs font-semibold
                                            {{ $day['isToday'] ? 'bg-indigo-600 text-white' : ($day['isCurrentMonth'] ? 'text-zinc-700' : 'text-zinc-300') }}">
                                            {{ $day['day'] }}
                                        </span>

                                        @if($day['bookings']->count() > 0)
                                            <span class="flex h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                                        @endif
                                    </div>

                                    <div class="space-y-1">
                                        @foreach($day['bookings']->take(3) as $item)
                                            <div class="px-1.5 py-0.5 rounded text-[9px] font-semibold truncate {{ $roomColors[$item->room_id]['tag'] ?? 'bg-zinc-100 text-zinc-600' }}">
                                                {{ $item->room?->name }}
                                            </div>
                                        @endforeach

                                        @if($day['bookings']->count() > 3)
                                            <div class="text-[9px] font-semibold text-zinc-500 pl-1">
                                                + {{ $day['bookings']->count() - 3 }} lainnya
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Legend --}}
            <div class="bg-white border border-zinc-200 rounded-2xl p-4">
                <h3 class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-3">Legenda Ruangan</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($rooms as $room)
                        <div class="flex items-center gap-2 px-2.5 py-1 rounded-md text-xs font-medium {{ $roomColors[$room->id]['tag'] ?? 'bg-zinc-100 text-zinc-600' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $roomColors[$room->id]['dot'] ?? 'bg-zinc-400' }}"></span>
                            {{ $room->name }}
                        </div>
                    @empty
                        <p class="text-xs text-zinc-400 italic">Tidak ada penggunaan ruangan bulan ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Detail Panel --}}
        <div class="xl:col-span-1">
            <div class="sticky top-20 space-y-4">
                <div class="bg-white border border-zinc-200 rounded-2xl overflow-hidden min-h-[300px]">
                    <div class="p-4 border-b border-zinc-200 bg-zinc-50">
                        <h2 class="text-sm font-semibold text-zinc-900">
                            {{ $selectedDate ? \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') : 'Detail Jadwal' }}
                        </h2>
                        <p class="text-xs text-zinc-500 mt-0.5">
                            {{ $selectedDate ? $selectedItems->count() . ' booking terjadwal' : 'Klik tanggal untuk melihat detail' }}
                        </p>
                    </div>

                    <div class="p-3 space-y-3 max-h-[500px] overflow-y-auto">
                        @forelse($selectedItems as $item)
                            <div class="rounded-lg border border-zinc-200 bg-white p-3 transition hover:border-zinc-300">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="h-9 w-9 flex shrink-0 items-center justify-center rounded-lg {{ $roomColors[$item->room_id]['tag'] ?? 'bg-zinc-100 text-zinc-600' }}">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-zinc-900 truncate">{{ $item->room?->name }}</p>
                                            <p class="text-[11px] text-zinc-500 truncate">{{ $item->room?->building?->name }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex rounded px-1.5 py-0.5 text-[9px] font-semibold uppercase tracking-wider {{ $roomColors[$item->room_id]['badge'] ?? 'bg-zinc-100 text-zinc-600' }} shrink-0">
                                        {{ $item->session_label }}
                                    </span>
                                </div>

                                <div class="space-y-1.5 border-t border-zinc-100 pt-2.5">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 text-zinc-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span class="text-xs text-zinc-700 truncate">{{ $item->booking?->borrower_name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 text-zinc-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-xs text-zinc-700">{{ $item->time_range }}</span>
                                    </div>
                                </div>

                                @if($isAdmin)
                                    <a href="{{ route('admin.bookings.show', $item->booking) }}" class="mt-3 flex w-full items-center justify-center gap-1.5 rounded-md border border-zinc-200 py-1.5 text-xs font-semibold text-zinc-600 hover:bg-zinc-50 transition">
                                        Lihat Booking
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-10 text-center">
                                <div class="h-10 w-10 rounded-full bg-zinc-50 flex items-center justify-center mb-2">
                                    <svg class="h-5 w-5 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-xs font-semibold text-zinc-500">Kosong</p>
                                <p class="text-[11px] text-zinc-400 mt-0.5">Pilih tanggal di kalender.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Quick Stats Card --}}
                <div class="rounded-2xl bg-indigo-600 p-5">
                    <p class="text-[10px] font-semibold text-indigo-200 uppercase tracking-wider">Total Penggunaan</p>
                    <p class="text-3xl font-semibold text-white mt-1">{{ $totalBooked }}</p>
                    <p class="text-xs text-indigo-100 mt-1">Ruangan terpakai bulan ini.</p>
                </div>
            </div>
        </div>
    </div>
</div>
