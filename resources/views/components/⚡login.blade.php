<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

new #[Title('Login - Golden Bird CRM')] class extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

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

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
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

<div class="min-h-screen bg-slate-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <!-- Brand Logo / Name -->
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
            GOLDEN <span class="text-[#1E4FA8]">BIRD</span>
        </h2>
        <p class="mt-2 text-sm text-slate-600">
            CRM Demo MVP Portal
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl rounded-2xl sm:px-10 border border-slate-200">
            <div class="mb-6 text-center">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-50 text-[#1E4FA8] border border-blue-100">
                    Secure Login Gate
                </span>
            </div>

            <form wire:submit="login" class="space-y-6">
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">
                        Email Address
                    </label>
                    <div class="mt-1 relative">
                        <input wire:model="email" id="email" type="email" autocomplete="email" required
                               class="appearance-none block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E4FA8] focus:border-[#1E4FA8] text-sm transition duration-150 ease-in-out @error('email') border-red-500 @enderror"
                               placeholder="e.g. gm@goldenbird.com">
                        @error('email')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">
                        Password
                    </label>
                    <div class="mt-1 relative">
                        <input wire:model="password" id="password" type="password" autocomplete="current-password" required
                               class="appearance-none block w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1E4FA8] focus:border-[#1E4FA8] text-sm transition duration-150 ease-in-out @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                        @error('password')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input wire:model="remember" id="remember" type="checkbox"
                               class="h-4 w-4 text-[#1E4FA8] focus:ring-[#1E4FA8] border-slate-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-slate-900 select-none cursor-pointer">
                            Remember me
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-[#1E4FA8] hover:bg-[#153A80] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1E4FA8] transition duration-150 ease-in-out">
                        <span wire:loading.remove wire:target="login">Sign In</span>
                        <span wire:loading wire:target="login" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Signing in...
                        </span>
                    </button>
                </div>
            </form>

            <!-- Quick Demo Credentials Helper Card -->
            <div class="mt-6 border-t border-slate-100 pt-6">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider text-center mb-3">
                    Demo Credentials (Password: password)
                </p>
                <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-600">
                    <div class="p-2 bg-slate-50 rounded-lg hover:bg-blue-50 hover:text-[#1E4FA8] transition cursor-pointer select-all" onclick="document.getElementById('email').value='gm@goldenbird.com'; document.getElementById('email').dispatchEvent(new Event('input'))">
                        <span class="font-bold">GM:</span> gm@goldenbird.com
                    </div>
                    <div class="p-2 bg-slate-50 rounded-lg hover:bg-blue-50 hover:text-[#1E4FA8] transition cursor-pointer select-all" onclick="document.getElementById('email').value='sales@goldenbird.com'; document.getElementById('email').dispatchEvent(new Event('input'))">
                        <span class="font-bold">Sales:</span> sales@goldenbird.com
                    </div>
                    <div class="p-2 bg-slate-50 rounded-lg hover:bg-blue-50 hover:text-[#1E4FA8] transition cursor-pointer select-all" onclick="document.getElementById('email').value='finance@goldenbird.com'; document.getElementById('email').dispatchEvent(new Event('input'))">
                        <span class="font-bold">Finance:</span> finance@goldenbird.com
                    </div>
                    <div class="p-2 bg-slate-50 rounded-lg hover:bg-blue-50 hover:text-[#1E4FA8] transition cursor-pointer select-all" onclick="document.getElementById('email').value='ops@goldenbird.com'; document.getElementById('email').dispatchEvent(new Event('input'))">
                        <span class="font-bold">Ops:</span> ops@goldenbird.com
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>