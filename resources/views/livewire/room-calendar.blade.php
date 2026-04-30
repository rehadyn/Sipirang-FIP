<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Jadwal Penggunaan Ruangan</h1>
            <p class="text-sm text-slate-500 mt-1">Kalender penggunaan ruangan yang telah disetujui.</p>
        </div>

        <div class="flex items-center gap-3 bg-white border border-slate-200 rounded-2xl p-1.5 shadow-sm">
            <button wire:click="prevMonth" class="p-2 rounded-xl hover:bg-slate-50 transition text-slate-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <span class="px-4 text-sm font-bold text-slate-700 min-w-[140px] text-center capitalize">
                {{ $monthLabel }}
            </span>
            <button wire:click="nextMonth" class="p-2 rounded-xl hover:bg-slate-50 transition text-slate-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
        {{-- Calendar Grid --}}
        <div class="xl:col-span-3 space-y-6">
            <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
                {{-- Days Header --}}
                <div class="grid grid-cols-7 border-b border-slate-100 bg-slate-50/50">
                    @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                        <div class="py-3 text-center text-[10px] font-bold uppercase tracking-widest text-slate-400">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                {{-- Weeks --}}
                <div class="divide-y divide-slate-100">
                    @foreach($weeks as $week)
                        <div class="grid grid-cols-7 divide-x divide-slate-100">
                            @foreach($week as $day)
                                <div 
                                    wire:click="selectDate('{{ $day['date'] }}')"
                                    class="min-h-[100px] md:min-h-[120px] p-2 transition-all cursor-pointer group hover:bg-indigo-50/30 
                                    {{ !$day['isCurrentMonth'] ? 'bg-slate-50/50' : 'bg-white' }}
                                    {{ $selectedDate === $day['date'] ? 'ring-2 ring-inset ring-indigo-500 bg-indigo-50/50' : '' }}"
                                >
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold
                                            {{ $day['isToday'] ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : ($day['isCurrentMonth'] ? 'text-slate-700' : 'text-slate-300') }}">
                                            {{ $day['day'] }}
                                        </span>
                                        
                                        @if($day['bookings']->count() > 0)
                                            <span class="flex h-1.5 w-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                        @endif
                                    </div>

                                    <div class="space-y-1">
                                        @foreach($day['bookings']->take(3) as $item)
                                            <div class="px-1.5 py-0.5 rounded text-[9px] font-bold truncate {{ $roomColors[$item->room_id]['tag'] ?? 'bg-slate-100 text-slate-600' }}">
                                                {{ $item->room?->name }}
                                            </div>
                                        @endforeach
                                        
                                        @if($day['bookings']->count() > 3)
                                            <div class="text-[8px] font-bold text-slate-400 pl-1">
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
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Legenda Ruangan</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($rooms as $room)
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-medium {{ $roomColors[$room->id]['tag'] ?? 'bg-slate-100 text-slate-600' }}">
                            <span class="h-2 w-2 rounded-full {{ $roomColors[$room->id]['dot'] ?? 'bg-slate-400' }}"></span>
                            {{ $room->name }}
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Tidak ada penggunaan ruangan bulan ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Detail Panel --}}
        <div class="xl:col-span-1">
            <div class="sticky top-6 space-y-6">
                <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden min-h-[300px]">
                    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                        <h2 class="text-sm font-bold text-slate-900">
                            {{ $selectedDate ? \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') : 'Detail Jadwal' }}
                        </h2>
                        <p class="text-[11px] text-slate-500 mt-0.5">
                            {{ $selectedDate ? $selectedItems->count() . ' booking terjadwal' : 'Klik tanggal untuk melihat detail' }}
                        </p>
                    </div>

                    <div class="p-4 space-y-4 max-h-[500px] overflow-y-auto">
                        @forelse($selectedItems as $item)
                            <div class="group relative rounded-2xl border border-slate-100 bg-white p-4 shadow-sm hover:shadow-md transition-all">
                                <div class="absolute top-4 right-4">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ $roomColors[$item->room_id]['badge'] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ $item->session_label }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-3 mb-3">
                                    <div class="h-10 w-10 flex shrink-0 items-center justify-center rounded-xl {{ $roomColors[$item->room_id]['tag'] ?? 'bg-slate-100 text-slate-600' }}">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900 truncate">{{ $item->room?->name }}</p>
                                        <p class="text-[11px] text-slate-500 truncate">{{ $item->room?->building?->name }}</p>
                                    </div>
                                </div>

                                <div class="space-y-2 border-t border-slate-50 pt-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span class="text-xs font-medium text-slate-700 truncate">{{ $item->booking?->borrower_name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-xs font-medium text-slate-700">{{ $item->time_range }}</span>
                                    </div>
                                </div>

                                @if($isAdmin)
                                    <a href="{{ route('admin.bookings.show', $item->booking) }}" class="mt-4 flex w-full items-center justify-center gap-1.5 rounded-xl border border-slate-200 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                                        Lihat Booking
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <div class="h-12 w-12 rounded-full bg-slate-50 flex items-center justify-center mb-3">
                                    <svg class="h-6 w-6 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kosong</p>
                                <p class="text-[10px] text-slate-400 mt-1">Pilih tanggal di kalender.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Quick Stats Card --}}
                <div class="rounded-3xl bg-indigo-600 p-5 shadow-lg shadow-indigo-200">
                    <p class="text-[10px] font-bold text-indigo-200 uppercase tracking-widest">Total Penggunaan</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalBooked }}</p>
                    <p class="text-[11px] text-indigo-100 mt-1">Ruangan terpakai bulan ini.</p>
                </div>
            </div>
        </div>
    </div>
</div>
