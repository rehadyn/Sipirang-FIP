<div x-data="{
        toastShow: false,
        toastMessage: '',
        toastType: 'success',
        showToast(msg, type) {
            this.toastMessage = msg;
            this.toastType = type || 'success';
            this.toastShow = true;
            clearTimeout(this._t);
            this._t = setTimeout(() => this.toastShow = false, 2800);
        }
    }"
    @toast.window="showToast($event.detail.message, $event.detail.type)">

    {{-- Header --}}
    <section class="mb-6 grid gap-4 lg:grid-cols-[1.5fr_0.9fr]">
        <div class="rounded-2xl border border-zinc-200 bg-white p-6 sm:p-8">
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 sm:text-3xl">Pilih Ruangan & Jadwal</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-zinc-500">
                Tentukan tanggal peminjaman, lalu pilih sesi yang tersedia pada ruangan.
            </p>

            <div class="mt-5 rounded-lg bg-zinc-50 border border-zinc-200 p-4 sm:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xs uppercase tracking-wider font-semibold text-zinc-500">Tanggal Booking</h2>
                        <div class="mt-1 text-base font-semibold text-zinc-900">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}</div>
                    </div>
                    <div class="w-full sm:w-auto relative">
                        <input type="date" wire:model.live="selectedDate" min="{{ now()->format('Y-m-d') }}" class="w-full sm:w-auto min-w-[200px] cursor-pointer rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-medium text-zinc-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        <div wire:loading wire:target="selectedDate" class="absolute right-3 top-1/2 -translate-y-1/2">
                            <svg class="animate-spin h-4 w-4 text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Quick date chips --}}
                <div class="mt-3 flex flex-wrap gap-1.5">
                    @php
                        $today = now()->format('Y-m-d');
                        $tomorrow = now()->addDay()->format('Y-m-d');
                        $nextWeek = now()->addWeek()->format('Y-m-d');
                    @endphp
                    <button wire:click="$set('selectedDate', '{{ $today }}')" class="text-xs font-medium px-3 py-1 rounded-full border transition {{ $selectedDate === $today ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-zinc-600 border-zinc-300 hover:bg-zinc-50' }}">Hari Ini</button>
                    <button wire:click="$set('selectedDate', '{{ $tomorrow }}')" class="text-xs font-medium px-3 py-1 rounded-full border transition {{ $selectedDate === $tomorrow ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-zinc-600 border-zinc-300 hover:bg-zinc-50' }}">Besok</button>
                    <button wire:click="$set('selectedDate', '{{ $nextWeek }}')" class="text-xs font-medium px-3 py-1 rounded-full border transition {{ $selectedDate === $nextWeek ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-zinc-600 border-zinc-300 hover:bg-zinc-50' }}">+ 7 Hari</button>
                </div>
            </div>
            @error('date') <span class="mt-2 block text-xs text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 flex flex-col justify-center">
                <div class="text-xs uppercase tracking-wider font-semibold text-zinc-500">Keranjang</div>
                <div class="mt-1 text-3xl font-semibold text-zinc-900" aria-live="polite" aria-atomic="true">{{ count($cartItems) }} <span class="text-sm font-normal text-zinc-500">item</span></div>
                <a href="{{ route('guest.bookings.checkout.show') }}" @if(count($cartItems) === 0) aria-disabled="true" tabindex="-1" @endif class="mt-4 inline-block text-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:ring-offset-2 {{ count($cartItems) === 0 ? 'pointer-events-none opacity-50' : '' }}">Lanjut Checkout</a>
            </div>
        </div>
    </section>

    {{-- Filter & Search Bar --}}
    <div class="mb-5 space-y-3">
        <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
            <label for="search-rooms" class="sr-only">Cari ruangan</label>
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input
                    id="search-rooms"
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari ruangan, kode, atau gedung..."
                    aria-label="Cari ruangan berdasarkan nama, kode, atau gedung"
                    class="w-full rounded-lg border border-zinc-300 bg-white pl-9 pr-9 py-2 text-sm text-zinc-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                @if (trim($search) !== '')
                    <button wire:click="$set('search', '')" aria-label="Hapus pencarian" class="absolute right-2 top-1/2 -translate-y-1/2 p-1 text-zinc-400 hover:text-zinc-700 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
                <div wire:loading wire:target="search" class="absolute right-2 top-1/2 -translate-y-1/2">
                    <svg class="animate-spin h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-zinc-500" role="status" aria-live="polite">
                Tersedia pada <strong class="text-zinc-900 font-semibold">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d M Y') }}</strong>
                · <span class="text-indigo-600 font-semibold">{{ $availableCount }}</span> dari {{ $totalRooms }} ruangan masih ada slot
            </p>

            <div class="flex flex-wrap gap-1.5 items-center" role="group" aria-label="Filter berdasarkan gedung">
                <button wire:click="$set('buildingFilter', '')" aria-pressed="{{ $buildingFilter === '' ? 'true' : 'false' }}" class="text-xs font-medium px-3 py-1.5 rounded-full border transition {{ $buildingFilter === '' ? 'bg-zinc-900 text-white border-zinc-900' : 'bg-white text-zinc-600 border-zinc-300 hover:bg-zinc-50' }}">Semua</button>
                @foreach ($buildings as $bld)
                    <button wire:click="$set('buildingFilter', '{{ $bld->id }}')" aria-pressed="{{ (string)$buildingFilter === (string)$bld->id ? 'true' : 'false' }}" class="text-xs font-medium px-3 py-1.5 rounded-full border transition {{ (string)$buildingFilter === (string)$bld->id ? 'bg-zinc-900 text-white border-zinc-900' : 'bg-white text-zinc-600 border-zinc-300 hover:bg-zinc-50' }}">{{ $bld->code ?? $bld->name }}</button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.45fr_0.85fr]">
        <section aria-label="Daftar ruangan">
            {{-- Skeleton Loader --}}
            <div wire:loading.flex wire:target="selectedDate,buildingFilter,search" class="grid gap-4 md:grid-cols-2 xl:grid-cols-2 items-start" aria-hidden="true">
                @for ($i = 0; $i < 4; $i++)
                    <article class="rounded-xl border border-zinc-200 bg-white animate-pulse">
                        <div class="p-5 border-b border-zinc-100">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="h-2.5 w-12 rounded bg-zinc-200"></div>
                                <div class="h-2.5 w-10 rounded bg-zinc-200"></div>
                            </div>
                            <div class="h-4 w-3/4 rounded bg-zinc-200 mb-2"></div>
                            <div class="h-3 w-1/2 rounded bg-zinc-100"></div>
                        </div>
                        <div class="p-3 space-y-1.5">
                            @for ($s = 0; $s < 3; $s++)
                                <div class="flex items-center justify-between gap-3 p-2.5 rounded-lg border border-zinc-100 bg-zinc-50">
                                    <div class="flex items-center gap-2.5 flex-1">
                                        <div class="h-5 w-5 rounded-full bg-zinc-200"></div>
                                        <div class="space-y-1.5 flex-1">
                                            <div class="h-3 w-16 rounded bg-zinc-200"></div>
                                            <div class="h-2 w-20 rounded bg-zinc-100"></div>
                                        </div>
                                    </div>
                                    <div class="h-7 w-14 rounded bg-zinc-200"></div>
                                </div>
                            @endfor
                        </div>
                    </article>
                @endfor
            </div>

            <div wire:loading.remove wire:target="selectedDate,buildingFilter,search" class="grid gap-4 md:grid-cols-2 xl:grid-cols-2 items-start">
                @forelse ($rooms as $room)
                    @php
                        $roomBooked = $bookedSessions[$room->id] ?? [];
                        $isBlocked = in_array((int) $room->id, $blockedRoomIds, true);
                        $isFull = ! $isBlocked && (
                            count(array_intersect($roomBooked, [\App\Models\Booking::SESSION_PAGI, \App\Models\Booking::SESSION_SIANG])) >= 2
                            || in_array(\App\Models\Booking::SESSION_FULLDAY, $roomBooked)
                        );
                    @endphp

                    <article class="rounded-xl border border-zinc-200 bg-white flex flex-col transition {{ $isFull || $isBlocked ? 'opacity-70' : '' }}">
                        {{-- Card Header --}}
                        <div class="p-5 border-b border-zinc-100">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] uppercase tracking-wider font-semibold text-indigo-600">{{ $room->building->code }}</span>
                                        <span class="text-zinc-300">·</span>
                                        <span class="text-[10px] uppercase tracking-wider font-semibold text-zinc-500">Lt. {{ $room->floor }}</span>
                                        @if ($isBlocked)
                                            <span class="ml-auto inline-flex items-center gap-1 rounded bg-zinc-100 px-2 py-0.5 text-[10px] font-semibold text-zinc-600 uppercase tracking-wider border border-zinc-200" aria-label="Ruangan diblokir pada tanggal ini">Diblokir</span>
                                        @elseif ($isFull)
                                            <span class="ml-auto inline-flex items-center gap-1 rounded bg-red-50 px-2 py-0.5 text-[10px] font-semibold text-red-700 uppercase tracking-wider border border-red-200">Penuh</span>
                                        @endif
                                    </div>
                                    <div class="text-base font-semibold text-zinc-900 leading-tight">{{ $room->name }}</div>
                                    <div class="mt-1 flex items-center gap-2 text-xs text-zinc-500">
                                        <span class="capitalize">{{ $room->room_type }}</span>
                                        <span class="text-zinc-300">·</span>
                                        <span>{{ $room->getCapacityLabel() }}</span>
                                    </div>
                                </div>
                                @if ($room->requires_ktp)
                                    <span class="shrink-0 inline-flex items-center gap-1 rounded bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700 uppercase tracking-wider border border-amber-200">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"/></svg>
                                        KTP
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Sessions Always Visible --}}
                        <div class="p-3 space-y-1.5">
                            @if ($isBlocked)
                                <div class="rounded-lg border border-dashed border-zinc-200 bg-zinc-50 p-4 text-center" role="status">
                                    <svg class="mx-auto h-6 w-6 text-zinc-400 mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <p class="text-xs font-semibold text-zinc-700">Diblokir pada tanggal ini</p>
                                    <p class="text-[11px] text-zinc-500 mt-0.5">Coba pilih tanggal lain.</p>
                                </div>
                            @else
                            @foreach ($sessionTypes as $key => $sessionInfo)
                                @php
                                    $isBooked = in_array($key, $roomBooked);
                                    $inCart = collect($cartItems)->contains(function ($item) use ($room, $key, $selectedDate) {
                                        return $item['room_id'] == $room->id && $item['session'] == $key && $item['booking_date'] == $selectedDate;
                                    });
                                    $shortLabel = match($key) {
                                        'pagi' => 'Pagi',
                                        'siang' => 'Siang',
                                        'fullday' => 'Fullday',
                                        default => $sessionInfo['label']
                                    };
                                @endphp

                                <div class="flex items-center justify-between gap-3 p-2.5 rounded-lg border {{ $inCart ? 'bg-indigo-50 border-indigo-200' : ($isBooked ? 'bg-red-50 border-red-100' : 'bg-zinc-50 border-zinc-200') }}">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        {{-- Status Icon --}}
                                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full {{ $inCart ? 'bg-indigo-600 text-white' : ($isBooked ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700') }}">
                                            @if ($inCart)
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            @elseif ($isBooked)
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                            @else
                                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="6"/></svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-semibold {{ $inCart ? 'text-indigo-700' : ($isBooked ? 'text-red-700' : 'text-zinc-900') }}">{{ $shortLabel }}</div>
                                            <div class="text-[10px] text-zinc-500">{{ substr($sessionInfo['start'], 0, 5) }} – {{ substr($sessionInfo['end'], 0, 5) }}</div>
                                        </div>
                                    </div>

                                    @if ($inCart)
                                        <span class="text-[10px] font-semibold text-indigo-700 px-2 py-1 uppercase tracking-wider whitespace-nowrap" aria-label="Sesi {{ $shortLabel }} sudah ada di keranjang">Di Keranjang</span>
                                    @elseif ($isBooked)
                                        <span class="text-[10px] font-semibold text-red-600 px-2 py-1 uppercase tracking-wider whitespace-nowrap" aria-label="Sesi {{ $shortLabel }} sudah terpakai">Terpakai</span>
                                    @else
                                        <button wire:click="addToCart({{ $room->id }}, '{{ $key }}')" wire:loading.attr="disabled" wire:target="addToCart({{ $room->id }}, '{{ $key }}')" class="text-[11px] font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:ring-offset-1 px-3 py-1.5 rounded transition whitespace-nowrap" aria-label="Tambahkan {{ $room->name }} sesi {{ $shortLabel }} ke keranjang">
                                            <span wire:loading.remove wire:target="addToCart({{ $room->id }}, '{{ $key }}')">+ Pilih</span>
                                            <span wire:loading wire:target="addToCart({{ $room->id }}, '{{ $key }}')" aria-hidden="true">...</span>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="md:col-span-2 rounded-xl border border-dashed border-zinc-300 bg-white p-10 text-center" role="status">
                        <svg class="mx-auto h-10 w-10 text-zinc-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <p class="text-sm font-semibold text-zinc-700">Tidak ada ruangan ditemukan</p>
                        <p class="mt-1 text-xs text-zinc-500">
                            @if (trim($search) !== '')
                                Coba kata kunci lain atau hapus pencarian.
                            @else
                                Coba ganti filter gedung atau pilih tanggal lain.
                            @endif
                        </p>
                        @if (trim($search) !== '' || $buildingFilter !== '')
                            <button wire:click="$set('search', ''); $set('buildingFilter', '')" class="mt-4 inline-flex items-center gap-1 rounded-lg border border-zinc-200 bg-white px-3 py-1.5 text-xs font-medium text-zinc-600 transition hover:bg-zinc-50">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Reset Filter
                            </button>
                        @endif
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Sidebar Cart --}}
        <aside class="space-y-4">
            <div class="lg:sticky lg:top-20 rounded-2xl border border-zinc-200 bg-white p-6">
                <h2 class="text-base font-semibold text-zinc-900 mb-4 flex items-center gap-2">
                    <svg class="h-4 w-4 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Keranjang <span class="text-zinc-500 font-normal">· {{ count($cartItems) }} item</span>
                </h2>

                <div class="space-y-2">
                    @forelse ($cartItems as $index => $item)
                        <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-zinc-900 truncate">{{ $item['room_name'] }}</div>
                                    <div class="mt-1 text-[10px] font-semibold text-indigo-600 uppercase tracking-wider">{{ \Carbon\Carbon::parse($item['booking_date'])->format('d M') }} · {{ substr($item['start_time'], 0, 5) }}</div>
                                </div>
                                <button wire:click="removeFromCart({{ $index }})" class="text-zinc-400 hover:text-red-600 transition-colors p-1" aria-label="Hapus dari keranjang">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-zinc-300 bg-zinc-50 p-6 text-center">
                            <svg class="mx-auto h-7 w-7 text-zinc-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <p class="text-xs font-medium text-zinc-500">Keranjang masih kosong</p>
                            <p class="mt-0.5 text-[11px] text-zinc-400">Klik tombol <span class="font-semibold text-zinc-600">+ Pilih</span> pada sesi yang tersedia.</p>
                        </div>
                    @endforelse
                </div>

                @if(count($cartItems) > 0)
                    <a href="{{ route('guest.bookings.checkout.show') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                        Lanjut Checkout
                        <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @endif
            </div>

            {{-- SLA Info --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h3 class="text-xs font-semibold text-zinc-500 uppercase tracking-wider mb-4">Ketentuan Booking</h3>
                <ul class="space-y-3">
                    <li class="flex gap-3">
                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-amber-50 text-amber-600 mt-0.5">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-zinc-900">Upload Dokumen (5 Jam)</p>
                            <p class="mt-0.5 text-[11px] leading-relaxed text-zinc-500">Wajib upload surat WD 2 maksimal 5 jam setelah booking dibuat.</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 mt-0.5">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-zinc-900">Verifikasi Admin (2 Hari)</p>
                            <p class="mt-0.5 text-[11px] leading-relaxed text-zinc-500">Estimasi validasi dokumen maksimal 2 hari kerja.</p>
                        </div>
                    </li>
                </ul>
                <p class="mt-4 pt-4 border-t border-zinc-100 text-[11px] text-zinc-500 leading-relaxed italic">*Melewati batas waktu upload akan membatalkan booking secara otomatis.</p>
            </div>
        </aside>
    </div>

    {{-- Toast --}}
    <div x-show="toastShow"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 max-w-sm w-[calc(100%-2rem)]"
        role="status"
        aria-live="polite"
        aria-atomic="true"
        x-cloak>
        <div class="rounded-lg shadow-lg border px-4 py-3 flex items-center gap-3"
            :class="toastType === 'success' ? 'bg-zinc-900 border-zinc-800 text-white' : 'bg-red-600 border-red-700 text-white'">
            <svg x-show="toastType === 'success'" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <svg x-show="toastType === 'error'" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span class="text-sm font-medium" x-text="toastMessage"></span>
            <button @click="toastShow = false" class="ml-auto p-1 rounded hover:bg-white/10 transition" aria-label="Tutup notifikasi">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile FAB Cart --}}
    <a href="{{ route('guest.bookings.checkout.show') }}"
       @if(count($cartItems) === 0) aria-hidden="true" tabindex="-1" @endif
       aria-label="Lanjut ke checkout dengan {{ count($cartItems) }} item di keranjang"
       class="fixed bottom-6 right-6 lg:hidden z-40 flex items-center gap-2 rounded-full bg-indigo-600 text-white shadow-lg pl-4 pr-3 py-3 transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:ring-offset-2 {{ count($cartItems) === 0 ? 'opacity-0 pointer-events-none' : '' }}">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        <span class="text-sm font-semibold">Checkout</span>
        <span class="bg-white text-indigo-600 text-xs font-bold rounded-full h-5 min-w-5 px-1.5 flex items-center justify-center" aria-hidden="true">{{ count($cartItems) }}</span>
    </a>
</div>
