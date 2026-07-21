<?php

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

new class extends Component
{
    public $username = '';
    public $password = '';
    public $password_confirmation = '';

    protected function rules()
    {
        return [
            'username' => 'required|string|alpha_dash|min:3|max:50|unique:users,username',
            'password' => ['required', 'confirmed', 'min:6'],
        ];
    }

    protected $messages = [
        'username.unique' => 'Questo username è già in uso.',
        'username.alpha_dash' => 'L\'username può contenere solo lettere, numeri, trattini e underscore.',
        'password.confirmed' => 'La conferma della password non corrisponde.',
        'password.min' => 'La password deve essere di almeno 6 caratteri.',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'role' => 'user', // Registrati di default come utenti normali
        ]);

        Auth::login($user);

        return redirect()->to('/area-riservata');
    }
};
?>

<div class="flex items-center justify-center min-h-[65vh] w-full py-4">
    <div class="bg-white p-2.5 border border-[#D1B280]/40 rounded-sm shadow-2xl relative w-full max-w-md mx-auto transition-all duration-300">
        <div class="border border-[#D1B280]/60 p-8 sm:p-10 text-center relative paper-texture flex flex-col justify-between h-full overflow-hidden">
            
            <div class="space-y-4 pt-2 relative z-10">
                <span class="font-script text-gold-dark text-4xl select-none">Registrazione</span>
                <p class="font-serif text-[10px] uppercase tracking-[0.2em] text-charcoal-light font-semibold">
                    Crea un nuovo account invitato
                </p>
            </div>

            <div class="w-full h-px bg-[#D1B280]/30 my-5 relative z-10"></div>

            <form wire:submit="register" class="space-y-4 text-left relative z-10">
                <div>
                    <label for="username" class="block font-serif text-[11px] uppercase tracking-wider text-charcoal font-semibold mb-1.5">
                        Username
                    </label>
                    <input type="text" id="username" wire:model="username" 
                           class="w-full px-4 py-2 bg-[#FAF6F0] border border-[#D1B280]/30 rounded-md focus:outline-none focus:ring-1 focus:ring-sage-medium focus:border-sage-medium text-sm text-charcoal"
                           placeholder="Scegli un username" required>
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
                           placeholder="Crea una password" required>
                    @error('password')
                        <span class="text-red-500 text-xs mt-1 block font-serif">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block font-serif text-[11px] uppercase tracking-wider text-charcoal font-semibold mb-1.5">
                        Conferma Password
                    </label>
                    <input type="password" id="password_confirmation" wire:model="password_confirmation" 
                           class="w-full px-4 py-2 bg-[#FAF6F0] border border-[#D1B280]/30 rounded-md focus:outline-none focus:ring-1 focus:ring-sage-medium focus:border-sage-medium text-sm text-charcoal"
                           placeholder="Ripeti la password" required>
                    @error('password_confirmation')
                        <span class="text-red-500 text-xs mt-1 block font-serif">{{ $message }}</span>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-2.5 bg-sage-dark hover:bg-sage-medium text-white font-serif text-sm uppercase tracking-wider font-semibold rounded-md shadow-md active:scale-98 transition-all duration-200 cursor-pointer text-center">
                        Registrati
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center z-10">
                <p class="text-xs text-charcoal-light font-serif">
                    Hai già un account? 
                    <a href="/login" class="text-gold-dark hover:text-sage-dark font-semibold transition-colors duration-200">
                        Accedi qui
                    </a>
                </p>
            </div>
            
        </div>
    </div>
</div>
