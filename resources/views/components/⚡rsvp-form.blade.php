<?php

use Livewire\Component;
use App\Models\Rsvp;

new class extends Component
{
    // Primary guest fields
    public $first_name = '';
    public $last_name = '';
    public $will_attend = true;
    public $allergies = '';
    public $notes = '';

    public $success = false;

    protected function rules()
    {
        return [
            'first_name' => 'required|string|min:2|max:100',
            'last_name' => 'required|string|min:2|max:100',
            'will_attend' => 'required|boolean',
            'allergies' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    protected $messages = [
        'first_name.required' => 'Il nome è obbligatorio.',
        'first_name.min' => 'Il nome deve contenere almeno 2 caratteri.',
        'last_name.required' => 'Il cognome è obbligatorio.',
        'last_name.min' => 'Il cognome deve contenere almeno 2 caratteri.',
        'will_attend.required' => 'La scelta della presenza è obbligatoria.',
    ];

    public function submit()
    {
        $this->validate();

        \Illuminate\Support\Facades\DB::transaction(function () {
            // 1. Create main RSVP
            Rsvp::create([
                'first_name' => trim($this->first_name),
                'last_name' => trim($this->last_name),
                'will_attend' => (bool)$this->will_attend,
                'is_pregnant' => false,
                'allergies' => trim($this->allergies) ?: null,
                'dietary_requirements' => null,
                'notes' => trim($this->notes) ?: null,
                'rsvp_type' => 'single',
            ]);

            // 2. Parse notes for additional guests if notes are not empty
            if (!empty($this->notes)) {
                // Normalize spaces and remove punctuation
                $text = preg_replace('/[.,\/#!$%\^&\*;:{}=\-_`~()?"\']/u', ' ', $this->notes);
                $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
                
                $stopWords = [
                    'ciao', 'grazie', 'auguri', 'congratulazioni', 'felicitazioni', 'sposi', 'sposo', 'sposa', 'matrimonio',
                    'villa', 'chiesa', 'festa', 'giorno', 'tavolo', 'menu', 'buon', 'buongiorno', 'buonasera', 'saluti',
                    'abbraccio', 'abbracci', 'baci', 'bacio', 'un', 'una', 'uno', 'il', 'la', 'lo', 'i', 'gli', 'le', 'di', 'a', 'da',
                    'in', 'con', 'su', 'per', 'tra', 'fra', 'e', 'o', 'ma', 'se', 'che', 'non', 'si', 'no', 'anche', 'ci', 'vi',
                    'io', 'noi', 'voi', 'loro', 'mio', 'mia', 'miei', 'mie', 'tuo', 'tua', 'suo', 'sua', 'nostro', 'nostra',
                    'vostro', 'vostra', 'caro', 'cara', 'cari', 'care', 'monica', 'erasmo', 'vengo', 'verrò', 'verremo',
                    'saremo', 'siamo', 'sono', 'è', 'presente', 'presenti', 'confermo', 'confermiamo', 'parteciperò', 'parteciperemo', 'invito',
                    'bellissimo', 'felici', 'contenti', 'piacere', 'dettagli', 'conferma', 'partecipazione', 'all', 'alla',
                    'della', 'dello', 'degli', 'delle', 'nei', 'negli', 'nelle', 'ai', 'agli', 'alle', 'coi', 'nei', 'sui', 'purtroppo',
                    'speriamo', 'presto', 'grande', 'grandissimi', 'felicità', 'vita', 'insieme', 'meraviglioso'
                ];

                $primaryFirstLower = mb_strtolower(trim($this->first_name));
                $primaryLastLower = mb_strtolower(trim($this->last_name));

                $i = 0;
                $len = count($words);
                while ($i < $len) {
                    $word = $words[$i];
                    $wordLower = mb_strtolower($word);

                    // Check if word starts with a capital letter, is not a stop word, and not the primary guest name
                    if (preg_match('/^[A-Z][a-zàèìòù]/u', $word) 
                        && !in_array($wordLower, $stopWords) 
                        && $wordLower !== $primaryFirstLower 
                        && $wordLower !== $primaryLastLower) {
                        
                        $firstName = $word;
                        $lastName = trim($this->last_name); // default to primary guest last name

                        // Check if the next word is also capitalized and not a stop word/primary guest name
                        if ($i + 1 < $len) {
                            $nextWord = $words[$i + 1];
                            $nextWordLower = mb_strtolower($nextWord);

                            if (preg_match('/^[A-Z][a-zàèìòù]/u', $nextWord) 
                                && !in_array($nextWordLower, $stopWords) 
                                && $nextWordLower !== $primaryFirstLower 
                                && $nextWordLower !== $primaryLastLower) {
                                
                                $lastName = $nextWord;
                                $i++; // consume next word as last name
                            }
                        }

                        // Create RSVP for this guest
                        Rsvp::create([
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'will_attend' => true,
                            'is_pregnant' => false,
                            'allergies' => null,
                            'dietary_requirements' => null,
                            'notes' => 'Inserito automaticamente dalle note di ' . trim($this->first_name) . ' ' . trim($this->last_name),
                            'rsvp_type' => 'single',
                        ]);
                    }
                    $i++;
                }
            }
        });

        $this->success = true;
    }

    public function resetForm()
    {
        $this->first_name = '';
        $this->last_name = '';
        $this->will_attend = true;
        $this->allergies = '';
        $this->notes = '';
        $this->success = false;
    }
};
?>

<div class="flex items-center justify-center min-h-[70vh] w-full py-4">
    <div class="bg-white p-2.5 border border-[#D1B280]/40 rounded-sm shadow-2xl relative w-full max-w-3xl mx-auto transition-all duration-300">
        <div class="border border-[#D1B280]/60 p-4 sm:p-8 relative paper-texture flex flex-col justify-between h-full overflow-hidden">
            
            <!-- Floral decor top-right -->
            <div class="absolute top-0 right-0 w-32 h-32 pointer-events-none opacity-20 overflow-hidden">
                <svg viewBox="0 0 100 100" fill="none" class="w-full h-full text-sage-medium">
                    <path d="M100,0 C70,10 50,30 50,50" stroke="currentColor" stroke-width="0.8" />
                    <path d="M80,10 Q70,5 72,12 Q80,15 80,10 Z" fill="currentColor" />
                    <path d="M65,25 Q55,20 57,27 Q65,30 65,25 Z" fill="currentColor" />
                </svg>
            </div>

            @if ($success)
                <!-- Success View -->
                <div class="text-center py-12 px-4 space-y-6 relative z-10 flex flex-col items-center">
                    <div class="w-16 h-16 bg-emerald-50 rounded-full border border-emerald-200 flex items-center justify-center shadow-md animate-bounce">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <div class="space-y-2">
                        <span class="font-script text-gold-dark text-4xl select-none">Grazie di cuore</span>
                        <h3 class="font-serif text-lg text-charcoal font-semibold mt-2">
                            La vostra risposta è stata registrata con successo!
                        </h3>
                        <p class="font-serif text-sm text-charcoal-light max-w-md mx-auto leading-relaxed mt-2">
                            Monica <span class="ampersand text-lg font-normal">&amp;</span> Erasmo vi ringraziano calorosamente per aver confermato. Non vedono l'ora di festeggiare con voi!
                        </p>
                    </div>

                    <div class="w-full h-px bg-[#D1B280]/30 my-4"></div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full max-w-sm justify-center pt-2">
                        <button type="button" wire:click="resetForm" 
                                class="px-4 py-2 border border-[#D1B280]/40 text-charcoal font-serif text-xs uppercase tracking-wider rounded-md hover:bg-[#FAF6F0] active:scale-95 transition-all cursor-pointer">
                            Invia un'altra risposta
                        </button>
                        <a href="/" 
                           class="px-4 py-2 bg-sage-dark hover:bg-sage-medium text-white font-serif text-xs uppercase tracking-wider font-semibold rounded-md shadow-md active:scale-95 transition-all text-center">
                            Torna alla Home
                        </a>
                    </div>
                </div>
            @else
                <!-- Form View -->
                <div class="space-y-4 pt-2 relative z-10">
                    <span class="font-script text-gold-dark text-4xl select-none">Conferma la tua presenza</span>
                    <p class="font-serif text-[10px] uppercase tracking-[0.2em] text-charcoal-light font-semibold">
                        Conferma la partecipazione al matrimonio di Monica <span class="ampersand text-xs font-normal">&amp;</span> Erasmo
                    </p>
                </div>

                <div class="w-full h-px bg-[#D1B280]/30 my-5 relative z-10"></div>

                <form wire:submit="submit" class="space-y-6 text-left relative z-10">
                    
                    <div class="bg-zinc-50/50 p-4 border border-[#D1B280]/20 rounded-md space-y-5">
                        <h4 class="font-serif text-[12px] uppercase tracking-wider text-gold-dark font-bold border-b border-[#D1B280]/20 pb-1.5 flex justify-between items-center">
                            <span>Dati Invitato</span>
                            <span class="text-[9px] text-zinc-400 font-sans font-normal normal-case">Compila con le tue info</span>
                        </h4>

                        <!-- Name, Surname, Attendance row -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label for="first_name" class="block font-serif text-[10px] uppercase tracking-wider text-charcoal font-semibold mb-1">
                                    Nome
                                </label>
                                <input type="text" id="first_name" wire:model="first_name" 
                                       class="w-full px-4 py-2 bg-white border border-[#D1B280]/30 rounded-md focus:outline-none focus:ring-1 focus:ring-sage-medium focus:border-sage-medium text-sm text-charcoal shadow-sm" required>
                                @error('first_name')
                                    <span class="text-red-500 text-xs mt-1 block font-serif">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block font-serif text-[10px] uppercase tracking-wider text-charcoal font-semibold mb-1">
                                    Cognome
                                </label>
                                <input type="text" id="last_name" wire:model="last_name" 
                                       class="w-full px-4 py-2 bg-white border border-[#D1B280]/30 rounded-md focus:outline-none focus:ring-1 focus:ring-sage-medium focus:border-sage-medium text-sm text-charcoal shadow-sm" required>
                                @error('last_name')
                                    <span class="text-red-500 text-xs mt-1 block font-serif">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex items-center h-full pb-2 md:pl-2">
                                <label class="flex items-center space-x-3 cursor-pointer select-none">
                                    <input type="checkbox" wire:model.live="will_attend" 
                                           class="w-5 h-5 text-sage-dark border-[#D1B280]/40 rounded focus:ring-sage-medium focus:border-sage-medium transition duration-200">
                                    <div class="flex flex-col">
                                        <span class="font-serif text-xs font-semibold text-charcoal">Parteciperai (Sì/No)</span>
                                        <span class="text-[9px] text-charcoal-light font-sans">Seleziona se sarai presente</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Textareas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label for="allergies" class="block font-serif text-[10px] uppercase tracking-wider text-charcoal font-semibold mb-1">
                                    Eventuali allergeni o intolleranze
                                </label>
                                <textarea id="allergies" wire:model="allergies" rows="5" 
                                          class="w-full px-3 py-2 bg-white border border-[#D1B280]/30 rounded-md focus:outline-none focus:ring-1 focus:ring-sage-medium focus:border-sage-medium text-xs text-charcoal resize-none shadow-sm" 
                                          placeholder="Es: celiachia, lattosio..."></textarea>
                                @error('allergies')
                                    <span class="text-red-500 text-xs mt-1 block font-serif">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="notes" class="block font-serif text-[10px] uppercase tracking-wider text-charcoal font-semibold mb-1">
                                    Eventuali altre note o messaggi
                                </label>
                                <textarea id="notes" wire:model="notes" rows="5" 
                                          class="w-full px-3 py-2 bg-white border border-[#D1B280]/30 rounded-md focus:outline-none focus:ring-1 focus:ring-sage-medium focus:border-sage-medium text-xs text-charcoal resize-none shadow-sm" 
                                          placeholder="Es. particolari esigenze, orari, messaggi per gli sposi, eventuali figli (per i figli chiediamo di esplicitare nome e cognome)..."></textarea>
                                @error('notes')
                                    <span class="text-red-500 text-xs mt-1 block font-serif">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit & Back Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-zinc-100">
                        <a href="/" class="w-full sm:w-1/3 py-2.5 border border-zinc-300 text-zinc-700 font-serif text-xs uppercase tracking-wider rounded-md hover:bg-zinc-50 active:scale-95 transition-all text-center flex items-center justify-center">
                            Annulla
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-2/3 py-2.5 bg-sage-dark hover:bg-sage-medium text-white font-serif text-xs uppercase tracking-wider font-semibold rounded-md shadow-md active:scale-95 transition-all cursor-pointer">
                            Invia Risposta
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
