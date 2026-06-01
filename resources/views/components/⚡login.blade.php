<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

new #[Title('Masuk | Golden Bird CRM')] class extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public bool $showPassword = false;

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
        ]);

        // Map domain credentials for flexibility if user types gm@goldenbird.com or gm@bluebird.co.id
        $emailToAttempt = $this->email;
        if (str_contains($emailToAttempt, '@bluebird.co.id')) {
            $emailToAttempt = str_replace('@bluebird.co.id', '@goldenbird.com', $emailToAttempt);
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

        $this->addError('email', 'Email atau password salah.');
    }
};
?>

<div class="min-h-screen flex flex-col items-center justify-center p-6 font-sans">
    <main class="w-full max-w-[420px] space-y-6">
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

            <!-- Form -->
            <form wire:submit="login" class="space-y-4">
                <!-- Email Field -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase px-1" for="email">Email</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">mail</span>
                        <input wire:model="email" 
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#003887] transition-all outline-none text-sm @error('email') border-red-500 @enderror" 
                               id="email" 
                               placeholder="email@bluebird.co.id" 
                               type="email" required/>
                    </div>
                    @error('email')
                        <span class="text-red-500 text-xs mt-1 block px-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase px-1" for="password">Password</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">lock</span>
                        <input wire:model="password" 
                               class="w-full pl-11 pr-11 py-3 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-[#003887] transition-all outline-none text-sm @error('password') border-red-500 @enderror" 
                               id="password" 
                               placeholder="••••••••" 
                               type="{{ $showPassword ? 'text' : 'password' }}" required/>
                        <button wire:click="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#003887] transition-colors" type="button">
                            <span class="material-symbols-outlined text-[20px]">{{ $showPassword ? 'visibility_off' : 'visibility' }}</span>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-red-500 text-xs mt-1 block px-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between py-1">
                    <label class="flex items-center space-x-2 cursor-pointer group">
                        <input wire:model="remember" class="w-4 h-4 rounded border-slate-300 text-[#003887] focus:ring-[#003887] cursor-pointer" type="checkbox"/>
                        <span class="text-xs text-slate-600 group-hover:text-slate-800 transition-colors">Ingat saya</span>
                    </label>
                    <a class="text-xs text-[#003887] font-semibold hover:underline" href="#">Lupa sandi?</a>
                </div>

                <!-- Submit Button -->
                <button class="w-full bg-[#003887] text-white font-semibold text-sm py-3.5 rounded-xl hover:bg-[#1e4fa8] hover:shadow-lg active:scale-[0.98] transition-all duration-200" type="submit">
                    <span wire:loading.remove wire:target="login">Masuk</span>
                    <span wire:loading wire:target="login" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </form>
        </div>

        <!-- Demo Credentials Box -->
        <div class="w-full bg-blue-50/50 border border-slate-200 rounded-xl p-4">
            <div class="flex items-center space-x-2 mb-3 text-[#003887]">
                <span class="material-symbols-outlined text-[20px]">info</span>
                <h2 class="text-xs uppercase tracking-wider font-bold">Demo Credentials</h2>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-white p-2.5 rounded-lg border border-slate-200 hover:bg-blue-50/80 transition cursor-pointer select-all" onclick="document.getElementById('email').value='gm@bluebird.co.id'; document.getElementById('email').dispatchEvent(new Event('input'))">
                    <p class="text-[9px] text-slate-400 uppercase font-bold mb-0.5">General Manager</p>
                    <p class="font-mono text-[10px] text-slate-800">gm@bluebird.co.id</p>
                    <p class="font-mono text-[9px] text-slate-500">pwd: password</p>
                </div>
                <div class="bg-white p-2.5 rounded-lg border border-slate-200 hover:bg-blue-50/80 transition cursor-pointer select-all" onclick="document.getElementById('email').value='sales@bluebird.co.id'; document.getElementById('email').dispatchEvent(new Event('input'))">
                    <p class="text-[9px] text-slate-400 uppercase font-bold mb-0.5">Sales Officer</p>
                    <p class="font-mono text-[10px] text-slate-800">sales@bluebird.co.id</p>
                    <p class="font-mono text-[9px] text-slate-500">pwd: password</p>
                </div>
                <div class="bg-white p-2.5 rounded-lg border border-slate-200 hover:bg-blue-50/80 transition cursor-pointer select-all" onclick="document.getElementById('email').value='finance@bluebird.co.id'; document.getElementById('email').dispatchEvent(new Event('input'))">
                    <p class="text-[9px] text-slate-400 uppercase font-bold mb-0.5">Finance Admin</p>
                    <p class="font-mono text-[10px] text-slate-800">finance@bluebird.co.id</p>
                    <p class="font-mono text-[9px] text-slate-500">pwd: password</p>
                </div>
                <div class="bg-white p-2.5 rounded-lg border border-slate-200 hover:bg-blue-50/80 transition cursor-pointer select-all" onclick="document.getElementById('email').value='ops@bluebird.co.id'; document.getElementById('email').dispatchEvent(new Event('input'))">
                    <p class="text-[9px] text-slate-400 uppercase font-bold mb-0.5">Operations Head</p>
                    <p class="font-mono text-[10px] text-slate-800">ops@bluebird.co.id</p>
                    <p class="font-mono text-[9px] text-slate-500">pwd: password</p>
                </div>
            </div>
        </div>
    </main>
    <footer class="mt-8 text-center">
        <p class="text-xs text-slate-400">
            © 2026 Golden Bird B2B Fleet Management. Seluruh hak cipta dilindungi.
        </p>
    </footer>
</div>