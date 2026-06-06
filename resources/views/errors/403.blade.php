<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>403 — Akses Ditolak | Bluebird CRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@400,0&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css'])
    <style>
        body { background: #09090f; font-family: 'Inter', sans-serif; }
        body::before {
            content: '';
            position: fixed; inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 50% -10%, rgba(239,68,68,0.07) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 20% 110%, rgba(139,92,246,0.04) 0%, transparent 60%);
            pointer-events: none; z-index: 0;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 relative">
    <div class="relative z-10 text-center max-w-md w-full">

        {{-- Card --}}
        <div class="cc-card p-10 flex flex-col items-center gap-6">

            {{-- Icon --}}
            <div class="w-24 h-24 rounded-2xl flex items-center justify-center text-5xl"
                 style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15);">
                🚫
            </div>

            {{-- Error code --}}
            <div>
                <div class="text-[72px] font-black leading-none tracking-tight"
                     style="background: linear-gradient(135deg, #ef4444, #f97316); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    403
                </div>
                <div class="text-[18px] font-bold text-slate-200 mt-2">Akses Ditolak</div>
                <div class="text-[13px] text-slate-500 mt-2 leading-relaxed">
                    Kamu tidak memiliki izin untuk mengakses halaman ini.<br>
                    Hubungi administrator jika ini adalah kesalahan.
                </div>
            </div>

            {{-- Role info if authenticated --}}
            @auth
            <div class="w-full px-4 py-3 rounded-xl text-[12px] text-amber-400 flex items-center gap-2"
                 style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.15);">
                <span class="material-symbols-outlined text-[15px]">info</span>
                Role kamu: <span class="font-bold uppercase ml-1">{{ auth()->user()->role }}</span>
            </div>
            @endauth

            {{-- Divider --}}
            <div class="w-full h-px" style="background: rgba(255,255,255,0.06);"></div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full">
                @auth
                <a href="{{ route('dashboard') }}" class="btn-primary flex-1 justify-center">
                    <span class="material-symbols-outlined text-[16px]">space_dashboard</span>
                    Dashboard
                </a>
                @else
                <a href="{{ route('login') }}" class="btn-primary flex-1 justify-center">
                    <span class="material-symbols-outlined text-[16px]">login</span>
                    Login
                </a>
                @endauth
                <button onclick="history.back()" class="btn-secondary flex-1 justify-center">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    Kembali
                </button>
            </div>

        </div>

        {{-- Footer --}}
        <div class="mt-6 text-[11px] text-slate-600">
            Bluebird CRM · Error 403
        </div>

    </div>
</body>
</html>
