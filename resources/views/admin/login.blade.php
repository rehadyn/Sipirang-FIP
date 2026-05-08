<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — SIPIRANG</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">

    <div class="w-full max-w-md">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <x-unm-logo class="h-16 w-16" />
            </div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">SIPIRANG</h1>
            <p class="text-sm text-slate-600">Sistem Peminjaman Ruangan FIP UNM</p>
        </div>

        {{-- Card --}}
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
            <div class="p-6 sm:p-8">

                {{-- Title --}}
                <h2 class="text-lg font-semibold text-slate-900 mb-1">Masuk ke Panel Admin</h2>
                <p class="text-sm text-slate-600 mb-6">Gunakan kredensial Anda untuk mengakses dashboard</p>

                {{-- Error --}}
                @if($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <svg class="inline-block h-4 w-4 mr-2 align-middle" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required
                            placeholder="admin@sipirang.local"
                            class="w-full px-4 py-2.5 text-sm border border-slate-300 rounded-lg text-slate-900 placeholder-slate-500 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-slate-50 hover:bg-white focus:bg-white"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            required
                            placeholder="••••••••"
                            class="w-full px-4 py-2.5 text-sm border border-slate-300 rounded-lg text-slate-900 placeholder-slate-500 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 bg-slate-50 hover:bg-white focus:bg-white"
                        >
                    </div>

                    <div class="flex items-center gap-2">
                        <input id="remember" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-100">
                        <label for="remember" class="text-sm text-slate-700">Ingat saya</label>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-lg text-sm transition duration-200 ease-in-out active:scale-95 shadow-sm hover:shadow-md">
                        Masuk ke Panel Admin
                    </button>
                </form>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-8 flex flex-col md:flex-row md:justify-between md:items-start gap-4 md:gap-0">
            <!-- Left -->
            <div>
                <p class="text-xs text-slate-600">
                    SIPIRANG &copy; {{ date('Y') }} —<br>
                    Sistem Peminjaman Ruangan
                </p>
            </div>
            
            <!-- Right -->
            <div class="md:text-right">
                <p class="text-xs text-slate-600">
                    Made with ❤️ & ☕ by <a href="https://edumc.id" target="_blank" rel="noopener noreferrer" class="inline-block bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition">Reza HD</a><br>
                    Clavis Ignoti Profundi Arcanorum
                </p>
            </div>
        </div>
    </div>

</body>
</html>
