<div x-data="{ modalOpen: false, selectedRoomName: '', selectedSession: '', selectedRoomId: null, selectedSessionLabel: '' }">
    <section class="mb-8 grid gap-4 lg:grid-cols-[1.5fr_0.9fr]">
        <div class="rounded-3xl border border-white bg-white/40 backdrop-blur-xl p-6 shadow-sm sm:p-8">
            <h1 class="text-3xl font-semibold tracking-tight text-zinc-900 sm:text-4xl">Pilih Ruangan & Jadwal</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-zinc-500 sm:text-base font-medium">
                Tentukan tanggal peminjaman, lalu pilih ruangan yang tersedia.
            </p>

            <div class="mt-8 rounded-2xl bg-white border border-zinc-100 p-6 flex flex-col sm:flex-row items-center justify-between gap-6 relative shadow-sm">
                <div>
                    <h2 class="text-xs uppercase tracking-widest font-semibold text-zinc-400 mb-1">Tanggal Booking</h2>
                    <div class="text-lg font-semibold text-zinc-900">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}</div>
                </div>
                <div class="w-full sm:w-auto relative group">
                    <input type="date" wire:model.live="selectedDate" class="w-full sm:w-auto min-w-[200px] cursor-pointer rounded-xl border border-zinc-200 bg-zinc-50 px-5 py-3 text-sm font-medium text-zinc-900 outline-none transition focus:border-indigo-500 focus:bg-white">
                    <!-- Loading indicator -->
                    <div wire:loading wire:target="selectedDate" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            @error('date') <span class="mt-2 block text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
            <div class="rounded-3xl border border-white bg-white/40 backdrop-blur-xl p-6 shadow-sm flex flex-col justify-center">
                <div class="text-xs uppercase tracking-[0.2em] font-semibold text-zinc-400">Keranjang</div>
                <div class="mt-1 text-4xl font-semibold text-zinc-900">{{ count($cartItems) }}</div>
                <a href="{{ route('guest.bookings.checkout.show') }}" class="mt-5 inline-block text-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">Lanjut Checkout</a>
            </div>
        </div>
    </section>

    <!-- Error & Success messages -->
    @if (session()->has('status'))
        <div class="mb-6 rounded-2xl border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700 font-medium">
            {{ session('status') }}
        </div>
    @endif
    
    @error('cart')
        <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700 font-medium">
            {{ $message }}
        </div>
    @enderror
    @error('session')
        <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700 font-medium">
            {{ $message }}
        </div>
    @enderror

    <div class="grid gap-6 lg:grid-cols-[1.45fr_0.85fr]">
        <section>
            <div class="mb-6">
                <h2 class="text-xl font-semibold tracking-tight text-zinc-900">Live Status Ruangan</h2>
                <p class="mt-1 text-sm text-zinc-400 font-medium">Data tersedia pada <strong class="text-indigo-600 font-semibold">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d M Y') }}</strong>.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3 items-start relative" wire:loading.class="opacity-50 pointer-events-none" wire:target="selectedDate">
                @foreach ($rooms as $room)
                    <article class="overflow-hidden rounded-2xl border border-zinc-100 bg-white shadow-sm flex flex-col justify-between transition hover:shadow-md group/card" x-data="{ expanded: false }">
                        <!-- Card Header -->
                        <button @click="expanded = !expanded" class="w-full text-left focus:outline-none">
                            <div class="p-5">
                                <div class="text-[10px] uppercase tracking-[0.2em] font-bold text-indigo-500 mb-1 opacity-80">{{ $room->building->code }}</div>
                                <div class="text-base font-semibold text-zinc-900 leading-tight group-hover/card:text-indigo-600 transition-colors">{{ $room->name }}</div>
                                
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex gap-2">
                                        @if ($room->requires_ktp)
                                            <span class="rounded-md bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700 uppercase tracking-wider">KTP</span>
                                        @endif
                                        <span class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">Lt. {{ $room->floor }}</span>
                                    </div>
                                    <div class="text-zinc-300 transition-transform duration-300" :class="{'rotate-180': expanded}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </button>

                        <!-- Collapsible Content -->
                        <div x-show="expanded" x-transition.opacity x-cloak>
                            <div class="px-5 pb-5 pt-2 border-t border-zinc-50">
                                <div class="flex flex-wrap gap-1 text-[11px] font-medium text-zinc-400 mb-4 uppercase tracking-tight">
                                    <span>{{ $room->room_type }}</span>
                                    <span>·</span>
                                    <span>{{ $room->getCapacityLabel() }}</span>
                                </div>

                                <!-- Status Grid -->
                                <div class="space-y-1.5">
                                    @foreach ($sessionTypes as $key => $sessionInfo)
                                        @php
                                            $isBooked = in_array($key, $bookedSessions[$room->id] ?? []);
                                            $inCart = collect($cartItems)->contains(function($item) use ($room, $key, $selectedDate) {
                                                return $item['room_id'] == $room->id && $item['session'] == $key && $item['booking_date'] == $selectedDate;
                                            });
                                        @endphp
                                        <div class="flex items-center justify-between p-2 rounded-lg border {{ $inCart ? 'bg-zinc-50 border-zinc-100' : ($isBooked ? 'bg-red-50/50 border-red-100/50' : 'bg-white border-zinc-100') }}">
                                            <div class="pl-1">
                                                <div class="text-[11px] font-bold {{ $inCart ? 'text-zinc-500' : ($isBooked ? 'text-red-800' : 'text-zinc-700') }}">{{ $sessionInfo['label'] }}</div>
                                                <div class="text-[10px] font-medium {{ $inCart ? 'text-zinc-400' : ($isBooked ? 'text-red-400' : 'text-zinc-400') }}">{{ substr($sessionInfo['start'], 0, 5) }} - {{ substr($sessionInfo['end'], 0, 5) }}</div>
                                            </div>
                                            @if($inCart)
                                                <button disabled class="text-[10px] font-bold text-zinc-400 px-3 py-1 bg-zinc-100 rounded-md uppercase tracking-wider cursor-not-allowed">Di Keranjang</button>
                                            @elseif($isBooked)
                                                <button disabled class="text-[10px] font-bold text-zinc-400 px-3 py-1 bg-zinc-100 rounded-md uppercase tracking-wider cursor-not-allowed">Terpakai</button>
                                            @else
                                                <button @click="modalOpen = true; selectedRoomId = {{ $room->id }}; selectedRoomName = '{{ $room->name }}'; selectedSession = '{{ $key }}'; selectedSessionLabel = '{{ $sessionInfo['label'] }}'" class="text-[10px] font-bold text-indigo-600 px-3 py-1 bg-indigo-50 hover:bg-indigo-600 hover:text-white transition-all rounded-md uppercase tracking-wider">Pilih</button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <!-- Sidebar Cart -->
        <aside class="space-y-4">
            <div class="sticky top-24 rounded-2xl border border-zinc-100 bg-white p-6 shadow-sm">
                <h2 class="text-base font-semibold text-zinc-900 mb-5">Item Keranjang <span class="text-indigo-600 font-medium ml-1">({{ count($cartItems) }})</span></h2>

                <div class="space-y-2">
                    @forelse ($cartItems as $index => $item)
                        <div class="rounded-xl border border-zinc-50 bg-zinc-50/50 p-4 transition hover:bg-white hover:border-zinc-200 group">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-zinc-900 truncate">{{ $item['room_name'] }}</div>
                                    <div class="mt-1 text-[10px] font-bold text-indigo-500 uppercase tracking-widest">{{ \Carbon\Carbon::parse($item['booking_date'])->format('d M') }} · {{ substr($item['start_time'], 0, 5) }}</div>
                                </div>
                                <button wire:click="removeFromCart({{ $index }})" class="text-zinc-300 hover:text-red-500 transition-colors">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border-2 border-dashed border-zinc-100 p-8 text-xs font-medium text-zinc-300 text-center flex flex-col items-center justify-center gap-2">
                            <svg class="w-6 h-6 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            Kosong
                        </div>
                    @endforelse
                </div>

                @if(count($cartItems) > 0)
                    <a href="{{ route('guest.bookings.checkout.show') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-3.5 text-sm font-semibold text-white transition hover:bg-indigo-700 shadow-sm">
                        Checkout Sekarang
                    </a>
                @endif
            </div>

            <!-- SLA Info Box -->
            <div class="rounded-2xl border border-zinc-100 bg-white p-6 shadow-sm">
                <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-widest mb-4">Informasi SLA Booking</h3>
                <ul class="space-y-4">
                    <li class="flex gap-3">
                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-zinc-900">Upload Dokumen (5 Jam)</p>
                            <p class="mt-0.5 text-[10px] leading-relaxed text-zinc-400">Wajib upload surat WD 2 maksimal 5 jam setelah booking dibuat.</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-zinc-900">Verifikasi Admin (2 Hari)</p>
                            <p class="mt-0.5 text-[10px] leading-relaxed text-zinc-400">Estimasi validasi dokumen maksimal 2 hari kerja.</p>
                        </div>
                    </li>
                </ul>
                <div class="mt-5 pt-5 border-t border-zinc-50">
                    <p class="text-[10px] font-medium text-zinc-400 leading-relaxed italic">*Melewati batas waktu upload akan membatalkan booking secara otomatis.</p>
                </div>
            </div>
        </aside>
    </div>

    <!-- Confirmation Modal -->
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[100] grid place-items-center p-4" x-cloak>
            <!-- Backdrop -->
            <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-zinc-900/40 backdrop-blur-sm"></div>
            
            <!-- Modal Panel -->
            <div x-show="modalOpen" @click.away="modalOpen = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" class="relative w-full max-w-xs rounded-2xl bg-white p-6 shadow-2xl border border-zinc-100 text-center">
                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-indigo-50 mb-4">
                    <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                </div>
                <h3 class="text-lg font-semibold text-zinc-900 mb-2">Konfirmasi Pilih</h3>
                <p class="text-xs text-zinc-500 mb-6 leading-relaxed px-2">
                    Tambahkan <span class="font-bold text-zinc-900" x-text="selectedRoomName"></span> sesi <span class="text-indigo-600 font-bold" x-text="selectedSessionLabel"></span> ke keranjang?
                </p>
                
                <div class="flex flex-col gap-2">
                    <button @click="$wire.addToCart(selectedRoomId, selectedSession); modalOpen = false" type="button" class="w-full rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">Ya, Tambahkan</button>
                    <button @click="modalOpen = false" type="button" class="w-full rounded-xl border border-zinc-200 bg-white px-5 py-3 text-sm font-medium text-zinc-600 transition hover:bg-zinc-50">Batal</button>
                </div>
            </div>
        </div>
    </template>

    <!-- FAB mobile -->
    <div class="fixed bottom-6 right-6 lg:hidden z-40">
        <a href="{{ route('guest.bookings.checkout.show') }}" class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-600 text-white shadow-lg transition hover:bg-indigo-700">
            <span class="text-sm font-bold">{{ count($cartItems) }}</span>
        </a>
    </div>
</div>
