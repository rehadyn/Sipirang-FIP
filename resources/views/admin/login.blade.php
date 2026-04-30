<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — SIPIRANG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 min-h-screen flex items-center justify-center p-4">

    {{-- Background decorative --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-violet-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">

        {{-- Card --}}
        <div class="rounded-3xl bg-white/5 backdrop-blur-xl border border-white/10 shadow-2xl p-8">

            {{-- Logo --}}
            <div class="flex flex-col items-center mb-8">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 shadow-xl mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">SIPIRANG</h1>
                <p class="text-sm text-slate-400 mt-1">Panel Administrator</p>
            </div>

            {{-- Error --}}
            @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                {{ $errors->first() }}
            </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        placeholder="admin@sipirang.local"
                        class="w-full rounded-xl bg-white/10 border border-white/20 px-4 py-3 text-sm text-white placeholder-slate-500 outline-none transition focus:border-indigo-500 focus:bg-white/15 focus:ring-2 focus:ring-indigo-500/30"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        required
                        placeholder="••••••••"
                        class="w-full rounded-xl bg-white/10 border border-white/20 px-4 py-3 text-sm text-white placeholder-slate-500 outline-none transition focus:border-indigo-500 focus:bg-white/15 focus:ring-2 focus:ring-indigo-500/30"
                    >
                </div>

                <div class="flex items-center gap-2">
                    <input id="remember" type="checkbox" name="remember" class="h-4 w-4 rounded border-white/20 bg-white/10 text-indigo-500">
                    <label for="remember" class="text-sm text-slate-400">Ingat saya</label>
                </div>

                <button type="submit"
                    class="w-full rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:from-indigo-500 hover:to-violet-500 hover:shadow-indigo-500/25 hover:-translate-y-0.5 active:scale-95">
                    Masuk ke Panel Admin
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-slate-500 mt-6">
            SIPIRANG &copy; {{ date('Y') }} &mdash; Sistem Peminjaman Ruangan
        </p>
    </div>

</body>
</html>
