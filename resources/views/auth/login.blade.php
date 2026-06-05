<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Golden Bird CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 to-blue-950 flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-400 rounded-2xl mb-4 shadow-lg">
                <span class="text-3xl">🐦</span>
            </div>
            <h1 class="text-2xl font-bold text-white">Golden Bird CRM</h1>
            <p class="text-slate-400 text-sm mt-1">B2B Fleet Management System</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Masuk ke Akun</h2>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-4 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm"
                        placeholder="email@goldenbird.co.id"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm"
                        placeholder="••••••••"
                    >
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600">
                        Ingat saya
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition duration-200 text-sm"
                >
                    Masuk
                </button>
            </form>

            {{-- Demo accounts hint --}}
            <div class="mt-6 pt-5 border-t border-gray-100">
                <p class="text-xs text-gray-500 font-medium mb-2">Demo Accounts (password: <code class="bg-gray-100 px-1 rounded">password123</code>)</p>
                <div class="grid grid-cols-2 gap-1 text-xs text-gray-500">
                    <span>🔑 director@goldenbird.co.id</span>
                    <span>🔑 gm@goldenbird.co.id</span>
                    <span>🔑 manager@goldenbird.co.id</span>
                    <span>🔑 sales1@goldenbird.co.id</span>
                    <span>🔑 ops@goldenbird.co.id</span>
                    <span>🔑 finance@goldenbird.co.id</span>
                </div>
            </div>
        </div>

        <p class="text-center text-slate-500 text-xs mt-6">
            © 2026 Golden Bird CRM — V7.2
        </p>
    </div>

</body>
</html>
