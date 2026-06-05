<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Golden Bird CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 flex items-center justify-center p-4">

    <div class="w-full max-w-lg">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-400 rounded-2xl mb-4 shadow-lg shadow-yellow-500/30">
                <span class="text-3xl">🐦</span>
            </div>
            <h1 class="text-2xl font-bold text-white">Golden Bird CRM</h1>
            <p class="text-slate-400 text-sm mt-1">B2B Fleet Management System — V7.2</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">

            <h2 class="text-lg font-semibold text-gray-800 mb-1">Masuk sebagai</h2>
            <p class="text-sm text-gray-400 mb-6">Pilih role untuk langsung masuk — 1 klik</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-5 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="grid grid-cols-2 gap-3">

                {{-- Director --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="email" value="director@goldenbird.co.id">
                    <input type="hidden" name="password" value="password123">
                    <button type="submit" class="w-full flex items-center gap-3 p-3.5 rounded-xl border-2 border-purple-200 hover:border-purple-500 hover:bg-purple-50 active:scale-95 transition-all group text-left cursor-pointer">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 group-hover:bg-purple-200 flex items-center justify-center text-xl flex-shrink-0 transition-colors">👔</div>
                        <div>
                            <div class="text-sm font-bold text-gray-800">Director</div>
                            <div class="text-xs text-gray-400">Full access</div>
                        </div>
                    </button>
                </form>

                {{-- GM --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="email" value="gm@goldenbird.co.id">
                    <input type="hidden" name="password" value="password123">
                    <button type="submit" class="w-full flex items-center gap-3 p-3.5 rounded-xl border-2 border-blue-200 hover:border-blue-500 hover:bg-blue-50 active:scale-95 transition-all group text-left cursor-pointer">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 group-hover:bg-blue-200 flex items-center justify-center text-xl flex-shrink-0 transition-colors">🏢</div>
                        <div>
                            <div class="text-sm font-bold text-gray-800">GM</div>
                            <div class="text-xs text-gray-400">General Manager</div>
                        </div>
                    </button>
                </form>

                {{-- Manager --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="email" value="manager@goldenbird.co.id">
                    <input type="hidden" name="password" value="password123">
                    <button type="submit" class="w-full flex items-center gap-3 p-3.5 rounded-xl border-2 border-green-200 hover:border-green-500 hover:bg-green-50 active:scale-95 transition-all group text-left cursor-pointer">
                        <div class="w-10 h-10 rounded-xl bg-green-100 group-hover:bg-green-200 flex items-center justify-center text-xl flex-shrink-0 transition-colors">📊</div>
                        <div>
                            <div class="text-sm font-bold text-gray-800">Manager</div>
                            <div class="text-xs text-gray-400">Sales Manager</div>
                        </div>
                    </button>
                </form>

                {{-- Sales --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="email" value="sales1@goldenbird.co.id">
                    <input type="hidden" name="password" value="password123">
                    <button type="submit" class="w-full flex items-center gap-3 p-3.5 rounded-xl border-2 border-yellow-200 hover:border-yellow-500 hover:bg-yellow-50 active:scale-95 transition-all group text-left cursor-pointer">
                        <div class="w-10 h-10 rounded-xl bg-yellow-100 group-hover:bg-yellow-200 flex items-center justify-center text-xl flex-shrink-0 transition-colors">💼</div>
                        <div>
                            <div class="text-sm font-bold text-gray-800">Sales</div>
                            <div class="text-xs text-gray-400">Account Executive</div>
                        </div>
                    </button>
                </form>

                {{-- Operational --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="email" value="ops@goldenbird.co.id">
                    <input type="hidden" name="password" value="password123">
                    <button type="submit" class="w-full flex items-center gap-3 p-3.5 rounded-xl border-2 border-orange-200 hover:border-orange-500 hover:bg-orange-50 active:scale-95 transition-all group text-left cursor-pointer">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 group-hover:bg-orange-200 flex items-center justify-center text-xl flex-shrink-0 transition-colors">🚗</div>
                        <div>
                            <div class="text-sm font-bold text-gray-800">Operational</div>
                            <div class="text-xs text-gray-400">Fleet Ops</div>
                        </div>
                    </button>
                </form>

                {{-- Finance --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="email" value="finance@goldenbird.co.id">
                    <input type="hidden" name="password" value="password123">
                    <button type="submit" class="w-full flex items-center gap-3 p-3.5 rounded-xl border-2 border-emerald-200 hover:border-emerald-500 hover:bg-emerald-50 active:scale-95 transition-all group text-left cursor-pointer">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 group-hover:bg-emerald-200 flex items-center justify-center text-xl flex-shrink-0 transition-colors">💰</div>
                        <div>
                            <div class="text-sm font-bold text-gray-800">Finance</div>
                            <div class="text-xs text-gray-400">Finance Team</div>
                        </div>
                    </button>
                </form>

            </div>

        </div>

        <p class="text-center text-slate-500 text-xs mt-6">
            © 2026 Golden Bird CRM — V7.2 · Bluebird Group
        </p>
    </div>

</body>
</html>
