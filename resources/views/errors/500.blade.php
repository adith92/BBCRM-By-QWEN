<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>500 — Server Error | Bluebird CRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@400,0&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css'])
    <style>
        body { background: #09090f; font-family: 'Inter', sans-serif; }
        body::before {
            content: '';
            position: fixed; inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 50% -10%, rgba(245,158,11,0.07) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 80% 110%, rgba(239,68,68,0.04) 0%, transparent 60%);
            pointer-events: none; z-index: 0;
        }
        @keyframes pulse-ring {
            0%   { transform: scale(1);   opacity: 0.4; }
            50%  { transform: scale(1.08); opacity: 0.15; }
            100% { transform: scale(1);   opacity: 0.4; }
        }
        .pulse-ring { animation: pulse-ring 2.5s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 relative">
    <div class="relative z-10 text-center max-w-md w-full">

        {{-- Card --}}
        <div class="cc-card p-10 flex flex-col items-center gap-6">

            {{-- Icon with pulse --}}
            <div class="relative">
                <div class="pulse-ring absolute inset-0 rounded-2xl"
                     style="background: rgba(245,158,11,0.2);"></div>
                <div class="relative w-24 h-24 rounded-2xl flex items-center justify-center text-5xl"
                     style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2);">
                    ⚠️
                </div>
            </div>

            {{-- Error code --}}
            <div>
                <div class="text-[72px] font-black leading-none tracking-tight"
                     style="background: linear-gradient(135deg, #f59e0b, #ef4444); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    500
                </div>
                <div class="text-[18px] font-bold text-slate-200 mt-2">Server Error</div>
                <div class="text-[13px] text-slate-500 mt-2 leading-relaxed">
                    Terjadi kesalahan di server kami.<br>
                    Tim teknis sudah dinotifikasi. Coba lagi dalam beberapa menit.
                </div>
            </div>

            {{-- Status info --}}
            <div class="w-full px-4 py-3 rounded-xl text-[12px] text-amber-400 flex items-center gap-2"
                 style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.15);">
                <span class="material-symbols-outlined text-[15px]">engineering</span>
                <span>Tim teknis sedang menangani masalah ini</span>
            </div>

            {{-- Divider --}}
            <div class="w-full h-px" style="background: rgba(255,255,255,0.06);"></div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full">
                <a href="mailto:support@bluebirdcrm.com" class="btn-primary flex-1 justify-center">
                    <span class="material-symbols-outlined text-[16px]">mail</span>
                    Contact Support
                </a>
                <button onclick="window.location.reload()" class="btn-secondary flex-1 justify-center">
                    <span class="material-symbols-outlined text-[16px]">refresh</span>
                    Coba Lagi
                </button>
            </div>

        </div>

        {{-- Footer --}}
        <div class="mt-6 text-[11px] text-slate-600">
            Bluebird CRM · Error 500
        </div>

    </div>
</body>
</html>
