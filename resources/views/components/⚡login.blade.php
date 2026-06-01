<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

new #[Title('Masuk | Golden Bird CRM')] class extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public string $loginError = '';

    public function login()
    {
        $this->loginError = '';

        $this->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        // Konversi email lama (backward compatibility)
        $emailToAttempt = trim($this->email);
        if (str_contains($emailToAttempt, '@bluebird.co.id')) {
            $emailToAttempt = str_replace('@bluebird.co.id', '@goldenbird.com', $emailToAttempt);
        }
        if (str_contains($emailToAttempt, '@golden-bird-crm.test')) {
            $emailToAttempt = str_replace('@golden-bird-crm.test', '@goldenbird.com', $emailToAttempt);
        }

        if (Auth::attempt(['email' => $emailToAttempt, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            $user = Auth::user();
            if ($user->hasRole('gm')) {
                return $this->redirect('/dashboard/gm', navigate: true);
            } elseif ($user->hasRole('sales')) {
                return $this->redirect('/fleet', navigate: true);
            } elseif ($user->hasRole('finance')) {
                return $this->redirect('/invoices', navigate: true);
            } elseif ($user->hasRole('ops')) {
                return $this->redirect('/bookings', navigate: true);
            }

            return $this->redirect('/', navigate: true);
        }

        $this->loginError = 'Email atau password salah. Pastikan kredensial sudah benar.';
        $this->password = '';
    }
};
?>

<div class="min-h-screen flex flex-col items-center justify-center p-6 font-sans">
    <main class="w-full max-w-[420px] space-y-5">
        <!-- Login Card -->
        <div class="backdrop-blur-md bg-white/95 p-8 rounded-2xl shadow-xl border border-slate-200">
            <!-- Header -->
            <header class="flex flex-col items-center text-center mb-8">
                <div class="w-16 h-16 bg-[#003887] rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-blue-900/20">
                    <img alt="Golden Bird Logo" class="w-12 h-12 object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuASuq06-1xVvqhoVwDDnrdQEoiykTkdHDwicF19QYttv9OFftJzhhEGgSzK2RfuAt03wtyCKqFz8Y5wCsn0fxmpZmEK7gE1fHmRi_lumLbB4LM7iQJSEgJZBVuuSRi_qxCYPAIRbgL24XijylXWw8SPvFlWiQEVcTJmDbgEpP0qBxIUKqf-HzwxT_Pl5Wn0wlVIXMc9E6_FNbPW6gSliobwq5ffvNWBWQ7B-7ZGy_KKwaOWkGJejrtjiYS51pqYKtCgNMx4W4sfDIoY"/>
                </div>
                <h1 class="text-xl font-extrabold text-slate-900 mb-0.5">Golden Bird</h1>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">B2B Fleet Management Portal</p>
            </header>

            <!-- Error Banner — muncul saat login gagal -->
            @if($loginError)
            <div class="mb-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm animate-pulse" role="alert">
                <span class="material-symbols-outlined text-[20px] mt-0.5 shrink-0">error</span>
                <span>{{ $loginError }}</span>
            </div>
            @endif

            <!-- Form -->
            <form wire:submit="login" class="space-y-4">
                <!-- Email Field -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase px-1" for="email-input">Email</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">mail</span>
                        <input wire:model.live="email"
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#003887] transition-all outline-none text-sm @error('email') border-red-400 bg-red-50 @enderror"
                               id="email-input"
                               placeholder="email@goldenbird.com"
                               type="email"
                               autocomplete="email"/>
                    </div>
                    @error('email')
                        <span class="text-red-500 text-xs mt-1 flex items-center gap-1 px-1">
                            <span class="material-symbols-outlined text-[14px]">warning</span>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Password Field — toggle pakai pure JavaScript -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase px-1" for="password-input">Password</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">lock</span>
                        <input wire:model.live="password"
                               class="w-full pl-11 pr-11 py-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#003887] transition-all outline-none text-sm @error('password') border-red-400 bg-red-50 @enderror"
                               id="password-input"
                               placeholder="••••••••"
                               type="password"
                               autocomplete="current-password"/>
                        <!-- Toggle password: pure JavaScript, tidak butuh Livewire round-trip -->
                        <button type="button"
                                id="toggle-pwd-btn"
                                onclick="togglePasswordVisibility()"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#003887] transition-colors focus:outline-none"
                                aria-label="Tampilkan/sembunyikan password">
                            <span class="material-symbols-outlined text-[20px]" id="toggle-pwd-icon">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-red-500 text-xs mt-1 flex items-center gap-1 px-1">
                            <span class="material-symbols-outlined text-[14px]">warning</span>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between py-1">
                    <label class="flex items-center space-x-2 cursor-pointer group">
                        <input wire:model="remember" class="w-4 h-4 rounded border-slate-300 text-[#003887] focus:ring-[#003887] cursor-pointer" type="checkbox"/>
                        <span class="text-xs text-slate-600 group-hover:text-slate-800 transition-colors">Ingat saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button class="w-full bg-[#003887] text-white font-semibold text-sm py-3.5 rounded-xl hover:bg-[#1e4fa8] hover:shadow-lg active:scale-[0.98] transition-all duration-200 disabled:opacity-60" type="submit">
                    <span wire:loading.remove wire:target="login">Masuk</span>
                    <span wire:loading wire:target="login" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memverifikasi...
                    </span>
                </button>
            </form>
        </div>

        <!-- Demo Credentials Box -->
        <div class="w-full bg-white/80 backdrop-blur border border-slate-200 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center space-x-2 mb-3 text-[#003887]">
                <span class="material-symbols-outlined text-[18px]">badge</span>
                <h2 class="text-xs uppercase tracking-wider font-bold">Demo Credentials — Klik untuk mengisi otomatis</h2>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 hover:bg-blue-50 hover:border-blue-300 transition cursor-pointer" onclick="fillCredentials('gm@goldenbird.com')">
                    <p class="text-[9px] text-[#003887] uppercase font-bold mb-1">General Manager</p>
                    <p class="font-mono text-[10px] text-slate-800 truncate">gm@goldenbird.com</p>
                    <p class="font-mono text-[9px] text-slate-500 mt-0.5">🔑 demo1234</p>
                </div>
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 hover:bg-blue-50 hover:border-blue-300 transition cursor-pointer" onclick="fillCredentials('sales@goldenbird.com')">
                    <p class="text-[9px] text-[#003887] uppercase font-bold mb-1">Sales Officer</p>
                    <p class="font-mono text-[10px] text-slate-800 truncate">sales@goldenbird.com</p>
                    <p class="font-mono text-[9px] text-slate-500 mt-0.5">🔑 demo1234</p>
                </div>
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 hover:bg-blue-50 hover:border-blue-300 transition cursor-pointer" onclick="fillCredentials('finance@goldenbird.com')">
                    <p class="text-[9px] text-[#003887] uppercase font-bold mb-1">Finance Admin</p>
                    <p class="font-mono text-[10px] text-slate-800 truncate">finance@goldenbird.com</p>
                    <p class="font-mono text-[9px] text-slate-500 mt-0.5">🔑 demo1234</p>
                </div>
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 hover:bg-blue-50 hover:border-blue-300 transition cursor-pointer" onclick="fillCredentials('ops@goldenbird.com')">
                    <p class="text-[9px] text-[#003887] uppercase font-bold mb-1">Operations Head</p>
                    <p class="font-mono text-[10px] text-slate-800 truncate">ops@goldenbird.com</p>
                    <p class="font-mono text-[9px] text-slate-500 mt-0.5">🔑 demo1234</p>
                </div>
            </div>
        </div>
    </main>
    <footer class="mt-6 text-center">
        <p class="text-xs text-slate-400">© 2026 Golden Bird B2B Fleet Management. Seluruh hak cipta dilindungi.</p>
    </footer>
</div>

<script>
// Toggle show/hide password — pure JavaScript, tanpa Livewire round-trip
function togglePasswordVisibility() {
    var input = document.getElementById('password-input');
    var icon  = document.getElementById('toggle-pwd-icon');
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}

// Isi email & password otomatis, sync ke Livewire via native input event
function fillCredentials(email) {
    var emailInput    = document.getElementById('email-input');
    var passwordInput = document.getElementById('password-input');

    if (emailInput) {
        emailInput.value = email;
        emailInput.dispatchEvent(new Event('input', { bubbles: true }));
    }
    if (passwordInput) {
        // Pastikan password terlihat dan terisi
        passwordInput.type = 'password';
        document.getElementById('toggle-pwd-icon').textContent = 'visibility';
        passwordInput.value = 'demo1234';
        passwordInput.dispatchEvent(new Event('input', { bubbles: true }));
    }
}
</script>