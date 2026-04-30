<div>
    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
        <p class="text-sm text-slate-500 mt-1">Selamat datang, {{ auth()->user()->name }}. Ringkasan aktivitas booking hari ini.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-6">
        @php
        $statCards = [
            ['label' => 'Total Booking', 'value' => $stats['total'], 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'bg' => 'bg-indigo-50', 'icon_c' => 'text-indigo-600', 'val_c' => 'text-indigo-700'],
            ['label' => 'Menunggu Review', 'value' => $stats['pending_review'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-amber-50', 'icon_c' => 'text-amber-600', 'val_c' => 'text-amber-700'],
            ['label' => 'Disetujui', 'value' => $stats['approved'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-emerald-50', 'icon_c' => 'text-emerald-600', 'val_c' => 'text-emerald-700'],
            ['label' => 'Ditolak', 'value' => $stats['rejected'], 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-red-50', 'icon_c' => 'text-red-600', 'val_c' => 'text-red-700'],
        ];
        @endphp

        @foreach($statCards as $card)
        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">{{ $card['label'] }}</p>
                    <p class="text-3xl font-bold {{ $card['val_c'] }} mt-1">{{ $card['value'] }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $card['bg'] }}">
                    <svg class="h-5 w-5 {{ $card['icon_c'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Secondary Stats --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-8">
        <div class="rounded-2xl bg-white border border-slate-200 p-4 shadow-sm">
            <p class="text-xs text-slate-500">Booking Hari Ini</p>
            <p class="text-2xl font-bold text-slate-700 mt-1">{{ $stats['today'] }}</p>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 p-4 shadow-sm">
            <p class="text-xs text-slate-500">Bulan Ini</p>
            <p class="text-2xl font-bold text-slate-700 mt-1">{{ $stats['this_month'] }}</p>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 p-4 shadow-sm">
            <p class="text-xs text-slate-500">Menunggu Upload</p>
            <p class="text-2xl font-bold text-slate-700 mt-1">{{ $stats['pending_upload'] }}</p>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 p-4 shadow-sm">
            <p class="text-xs text-slate-500">Ruangan Aktif</p>
            <p class="text-2xl font-bold text-slate-700 mt-1">{{ $activeRooms }} / {{ $totalRooms }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Pending Review --}}
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div class="flex items-center gap-2">
                    <div class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></div>
                    <h2 class="text-sm font-semibold text-slate-800">Menunggu Review</h2>
                    @if($pendingReviews->count() > 0)
                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700">{{ $pendingReviews->count() }}</span>
                    @endif
                </div>
                <a href="{{ route('admin.bookings.index') }}?status=pending_review" class="text-xs text-indigo-600 hover:underline">Lihat semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($pendingReviews as $booking)
                <a href="{{ route('admin.bookings.show', $booking) }}" class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 transition group">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700 text-xs font-bold">
                        {{ strtoupper(substr($booking->borrower_name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-slate-800 group-hover:text-indigo-600">{{ $booking->borrower_name }}</p>
                        <p class="text-xs text-slate-500">{{ $booking->ticket_number }} &bull; {{ $booking->items->first()?->room?->name ?? '—' }}</p>
                    </div>
                    <div class="text-xs text-slate-400 flex-shrink-0">
                        {{ $booking->approval_letter_uploaded_at?->diffForHumans() ?? '—' }}
                    </div>
                </a>
                @empty
                <div class="px-5 py-8 text-center text-sm text-slate-400">
                    <svg class="h-10 w-10 mx-auto text-slate-200 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tidak ada booking yang menunggu review
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Bookings --}}
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-800">Booking Terbaru</h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentBookings as $booking)
                @php
                    $statusColors = [
                        'pending_upload'  => 'bg-slate-100 text-slate-600',
                        'pending_review'  => 'bg-amber-100 text-amber-700',
                        'approved'        => 'bg-emerald-100 text-emerald-700',
                        'rejected'        => 'bg-red-100 text-red-700',
                        'expired'         => 'bg-slate-100 text-slate-500',
                        'cancelled'       => 'bg-slate-100 text-slate-500',
                        'checked_in'      => 'bg-blue-100 text-blue-700',
                        'completed'       => 'bg-indigo-100 text-indigo-700',
                    ];
                    $statusLabels = [
                        'pending_upload'  => 'Upload',
                        'pending_review'  => 'Review',
                        'approved'        => 'Disetujui',
                        'rejected'        => 'Ditolak',
                        'expired'         => 'Expired',
                        'cancelled'       => 'Batal',
                        'checked_in'      => 'Check-in',
                        'completed'       => 'Selesai',
                    ];
                @endphp
                <a href="{{ route('admin.bookings.show', $booking) }}" class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 transition group">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-slate-800 group-hover:text-indigo-600">{{ $booking->borrower_name }}</p>
                        <p class="text-xs text-slate-500">{{ $booking->ticket_number }}</p>
                    </div>
                    <span class="flex-shrink-0 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-600' }}">
                        {{ $statusLabels[$booking->status] ?? $booking->status }}
                    </span>
                </a>
                @empty
                <div class="px-5 py-8 text-center text-sm text-slate-400">Belum ada booking</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Overdue Alert --}}
    @if($overdueBookings->count() > 0)
    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-5">
        <div class="flex items-center gap-2 mb-3">
            <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h3 class="text-sm font-semibold text-red-800">{{ $overdueBookings->count() }} Booking Melewati SLA 2 Hari</h3>
        </div>
        <div class="space-y-2">
            @foreach($overdueBookings as $booking)
            <a href="{{ route('admin.bookings.show', $booking) }}"
               class="flex items-center justify-between rounded-lg bg-white border border-red-200 px-4 py-2.5 hover:bg-red-50 transition">
                <span class="text-sm font-medium text-red-800">{{ $booking->borrower_name }}</span>
                <span class="text-xs text-red-500">{{ $booking->approval_letter_uploaded_at?->diffForHumans() ?? '—' }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>
