<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

new class extends Component
{
    public $username = '';
    public $password = '';

    protected $rules = [
        'username' => 'required|string|alpha_dash|min:3|max:50',
        'password' => 'required|string|min:6',
    ];

    public function login()
    {
        $this->validate();

        $throttleKey = Str::transliterate(Str::lower($this->username) . '|' . request()->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('username', "Troppi tentativi di accesso. Riprova tra {$seconds} secondi.");
            return;
        }

        if (!Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            RateLimiter::hit($throttleKey, 60); // Blocco per 60 secondi
            $this->addError('username', 'Credenziali non valide.');
            return;
        }

        RateLimiter::clear($throttleKey);
        session()->regenerate();

        return redirect()->to('/area-riservata');
    }
};
?>

<div class="flex items-center justify-center min-h-[60vh] w-full py-4">
    <div class="bg-white p-2.5 border border-[#D1B280]/40 rounded-sm shadow-2xl relative w-full max-w-md mx-auto transition-all duration-300">
        <div class="border border-[#D1B280]/60 p-8 sm:p-10 text-center relative paper-texture flex flex-col justify-between h-full overflow-hidden">
            
            <div class="space-y-4 pt-2 relative z-10">
                <span class="font-script text-gold-dark text-4xl select-none">Accesso</span>
                <p class="font-serif text-[10px] uppercase tracking-[0.2em] text-charcoal-light font-semibold">
                    Area Riservata Invito Matrimonio
                </p>
            </div>

            <div class="w-full h-px bg-[#D1B280]/30 my-5 relative z-10"></div>

            <form wire:submit="login" class="space-y-4 text-left relative z-10">
                <div>
                    <label for="username" class="block font-serif text-[11px] uppercase tracking-wider text-charcoal font-semibold mb-1.5">
                        Username
                    </label>
                    <input type="text" id="username" wire:model="username" 
                           class="w-full px-4 py-2 bg-[#FAF6F0] border border-[#D1B280]/30 rounded-md focus:outline-none focus:ring-1 focus:ring-sage-medium focus:border-sage-medium text-sm text-charcoal"
                           placeholder="Inserisci il tuo username" required>
                    @error('username')
                        <span class="text-red-500 text-xs mt-1 block font-serif">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block font-serif text-[11px] uppercase tracking-wider text-charcoal font-semibold mb-1.5">
                        Password
                    </label>
                    <input type="password" id="password" wire:model="password" 
                           class="w-full px-4 py-2 bg-[#FAF6F0] border border-[#D1B280]/30 rounded-md focus:outline-none focus:ring-1 focus:ring-sage-medium focus:border-sage-medium text-sm text-charcoal"
                           placeholder="Inserisci la tua password" required>
                    @error('password')
                        <span class="text-red-500 text-xs mt-1 block font-serif">{{ $message }}</span>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-2.5 bg-sage-dark hover:bg-sage-medium text-white font-serif text-sm uppercase tracking-wider font-semibold rounded-md shadow-md active:scale-98 transition-all duration-200 cursor-pointer text-center">
                        Accedi
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center z-10">
                <p class="text-xs text-charcoal-light font-serif">
                    Non hai un account? 
                    <a href="/register" class="text-gold-dark hover:text-sage-dark font-semibold transition-colors duration-200">
                        Registrati qui
                    </a>
                </p>
            </div>
            
        </div>
    </div>
</div>
