@php
    $guestName = request('nome') ?? request('guest');

    if (!$guestName && Auth::check()) {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            $record = \App\Models\PersonalRecord::where('user_id', $user->id)->first();
            if ($record) {
                $guestName = $record->full_name;
            }
        }
    }
@endphp
<x-layout title="Stampa Invito - Monica & Erasmo">
    <style>
        .print-card-wrapper {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 10px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(209, 178, 128, 0.4);
            border-radius: 2px;
            box-sizing: border-box;
        }
        
        .print-card-content {
            border: 1px solid rgba(209, 178, 128, 0.6);
            padding: 4rem 3rem;
            position: relative;
            background-color: var(--color-paper-ivory);
            min-height: 70vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .bg-couple-watermark-print {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.09;
            filter: blur(1.5px);
            z-index: 0;
        }

        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        @media print {
            header, footer, .no-print {
                display: none !important;
            }
            
            body, main {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            .print-card-wrapper {
                max-width: none !important;
                width: 100% !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }

            .print-card-content {
                border: 2px solid #D1B280 !important;
                padding: 20mm !important;
                min-height: 270mm !important;
                height: 270mm !important;
                box-sizing: border-box !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>

    <div class="w-full py-4 space-y-6">
        <!-- Floating instruction / action panel -->
        <div class="no-print bg-white p-2.5 border border-[#D1B280]/40 rounded-sm shadow-xl max-w-4xl mx-auto">
            <div class="border border-[#D1B280]/60 p-6 sm:p-8 paper-texture flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-left">
                    <span class="font-script text-gold-dark text-3xl select-none">Stampa il tuo Invito</span>
                    <p class="font-serif text-xs text-charcoal-light mt-1">
                        Questo layout riproduce l'invito della Home Page pronto per essere stampato su un singolo foglio verticale (A4).
                    </p>
                    <p class="font-serif text-[10px] text-zinc-400 mt-0.5">
                        * Consiglio: nelle impostazioni di stampa imposta l'orientamento su <strong>Verticale (Portrait)</strong>, attiva <strong>"Grafica di sfondo"</strong> e imposta i margini a <strong>"Nessuno"</strong>.
                    </p>
                </div>
                
                <div class="flex gap-3">
                    <button onclick="window.print()" class="px-5 py-2 bg-sage-dark hover:bg-sage-medium text-white font-serif text-xs uppercase tracking-wider font-semibold rounded-md shadow-md active:scale-95 transition-all cursor-pointer flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 022 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Stampa
                    </button>
                    <a href="/" class="px-5 py-2 bg-white hover:bg-zinc-50 border border-zinc-200 text-zinc-700 font-serif text-xs uppercase tracking-wider font-semibold rounded-md shadow-md active:scale-95 transition-all cursor-pointer">
                        Home
                    </a>
                </div>
            </div>
        </div>

        <!-- Invitation Card -->
        <div class="print-card-wrapper">
            <div class="print-card-content paper-texture">
                
                <!-- Background watermark -->
                <div class="bg-couple-watermark-print" style="background-image: url('{{ asset('images/bg-sposi-processed.png') }}');"></div>

                <!-- Corner Foliage Top-Right -->
                <div class="absolute top-0 right-0 w-28 h-24 lg:w-56 lg:h-48 pointer-events-none opacity-90 overflow-hidden">
                    <svg viewBox="0 0 150 120" fill="none" class="w-full h-full text-sage-medium">
                        <path d="M150,0 Q120,8 90,32" stroke="currentColor" stroke-width="1.2" />
                        <path d="M130,0 Q95,15 70,45" stroke="currentColor" stroke-width="0.9" />
                        <path d="M110,0 Q75,22 55,58" stroke="currentColor" stroke-width="0.7" />
                        <path d="M115,14 Q105,18 108,24 Q116,25 119,18 Z" fill="currentColor" />
                        <path d="M92,24 Q82,28 85,34 Q93,35 96,28 Z" fill="currentColor" />
                        <path d="M72,38 Q62,42 65,48 Q73,49 76,42 Z" fill="currentColor" />
                        <path d="M54,52 Q44,56 47,62 Q55,63 58,56 Z" fill="currentColor" />
                        <path d="M104,18 Q94,12 91,17 Q93,24 100,22 Z" fill="var(--color-sage-dark)" opacity="0.95" />
                        <path d="M82,28 Q72,22 69,27 Q71,34 78,32 Z" fill="var(--color-sage-dark)" opacity="0.95" />
                        <path d="M62,42 Q52,36 49,41 Q51,48 58,46 Z" fill="var(--color-sage-dark)" opacity="0.95" />
                        <path d="M128,8 Q120,6 122,12 Q128,14 130,10 Z" fill="var(--color-gold-medium)" opacity="0.7" />
                        <path d="M85,36 Q78,32 76,37 Q80,42 86,39 Z" fill="var(--color-gold-medium)" opacity="0.7" />
                    </svg>
                </div>

                <!-- Corner Foliage Bottom-Left -->
                <div class="absolute bottom-0 left-0 w-28 h-24 lg:w-56 lg:h-48 pointer-events-none opacity-90 overflow-hidden">
                    <svg viewBox="0 0 150 120" fill="none" class="w-full h-full text-sage-medium">
                        <path d="M0,120 Q30,112 60,88" stroke="currentColor" stroke-width="1.2" />
                        <path d="M20,120 Q55,105 80,75" stroke="currentColor" stroke-width="0.9" />
                        <path d="M40,120 Q75,98 95,62" stroke="currentColor" stroke-width="0.7" />
                        <path d="M35,106 Q45,102 42,96 Q34,95 31,102 Z" fill="currentColor" />
                        <path d="M58,96 Q68,92 65,86 Q57,85 54,92 Z" fill="currentColor" />
                        <path d="M78,82 Q88,78 85,72 Q77,71 74,78 Z" fill="currentColor" />
                        <path d="M96,68 Q106,64 103,58 Q95,57 92,64 Z" fill="currentColor" />
                        <path d="M46,102 Q56,108 59,103 Q57,96 50,98 Z" fill="var(--color-sage-dark)" opacity="0.95" />
                        <path d="M68,92 Q78,98 81,93 Q79,86 72,88 Z" fill="var(--color-sage-dark)" opacity="0.95" />
                        <path d="M88,78 Q98,84 101,79 Q99,72 92,74 Z" fill="var(--color-sage-dark)" opacity="0.95" />
                        <path d="M22,112 Q30,114 28,108 Q22,106 20,110 Z" fill="var(--color-gold-medium)" opacity="0.7" />
                        <path d="M65,84 Q72,88 74,83 Q70,78 64,81 Z" fill="var(--color-gold-medium)" opacity="0.7" />
                    </svg>
                </div>

                <!-- Top Section: Monogram & Names -->
                <div class="space-y-6 pt-0 relative z-10 text-center">
                    @if($guestName)
                        <div class="mb-6 select-none">
                            <span class="font-script text-gold-dark text-3xl">Gentilissimo/a</span>
                            <h2 class="font-serif text-sm uppercase tracking-[0.25em] text-charcoal font-bold mt-1">
                                {{ $guestName }}
                            </h2>
                            <div class="w-16 h-px bg-[#D1B280]/30 mx-auto mt-2"></div>
                        </div>
                    @endif
                    <!-- Geometric decagon monogram -->
                    <div class="relative w-16 h-16 lg:w-32 lg:h-32 mt-4 lg:-mt-4 mb-4 lg:mb-6 mx-auto flex items-center justify-center select-none">
                        <div class="absolute inset-0 pointer-events-none text-sage-medium/40">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                                <path d="M32,78 C18,65 14,40 28,22" stroke="currentColor" stroke-width="0.8" />
                                <path d="M22,60 Q12,58 16,53 C20,48 24,53 22,60 Z" fill="currentColor" />
                                <path d="M18,42 Q10,38 15,33 C20,28 22,35 18,42 Z" fill="var(--color-sage-dark)" opacity="0.8" />
                                <path d="M26,28 Q18,22 23,18 C28,14 30,22 26,28 Z" fill="currentColor" />
                            </svg>
                        </div>
                        
                        <div class="absolute inset-0 pointer-events-none text-sage-medium/40">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                                <path d="M68,22 C82,35 86,60 72,78" stroke="currentColor" stroke-width="0.8" />
                                <path d="M78,40 Q88,42 84,47 C80,52 76,47 78,40 Z" fill="currentColor" />
                                <path d="M82,58 Q90,62 85,67 C80,72 78,65 82,58 Z" fill="var(--color-sage-dark)" opacity="0.8" />
                                <path d="M74,72 Q82,78 77,82 C72,86 70,78 74,72 Z" fill="currentColor" />
                            </svg>
                        </div>

                        <svg class="absolute inset-[7.8%] w-[84.4%] h-[84.4%] text-gold-medium/80" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="0.8">
                            <polygon points="50,2 79,11 98,38 98,72 79,98 50,89 21,98 2,72 2,38 21,11" />
                            <polygon points="50,6 76,14 94,39 94,70 76,94 50,85 24,94 6,70 6,39 24,14" stroke-width="0.5" stroke-dasharray="2 2" opacity="0.7"/>
                        </svg>
                        <span class="font-script text-gold-dark text-2xl lg:text-4xl relative top-[1.5%] -left-[3%]" style="text-shadow: 0 0 12px #FAF6F0, 0 0 8px #FAF6F0, 0 0 4px #FAF6F0;">M<span class="ampersand text-lg lg:text-3xl align-middle mx-1">&amp;</span>E</span>
                    </div>

                    <div class="space-y-2 mt-4">
                        <h1 class="font-script text-4xl sm:text-4xl md:text-5xl lg:text-6xl text-charcoal font-medium select-none whitespace-normal leading-normal py-2">
                            Monica Amendolara <span class="ampersand text-2xl sm:text-3xl md:text-3xl lg:text-5xl font-normal select-none mx-2">&amp;</span> Erasmo Porfido
                        </h1>
                    </div>
                </div>

                <!-- Divider -->
                <div class="w-full h-px bg-[#D1B280]/30 my-6 relative z-10"></div>

                <!-- Middle Section: Announcement -->
                <div class="space-y-6 my-2 relative z-10 text-center">
                    <div class="space-y-3">
                        <span class="font-script text-gold-dark text-[26px] sm:text-[28px] lg:text-4xl select-none">annunciano il loro matrimonio</span>
                        <p class="font-serif text-xs sm:text-sm lg:text-base uppercase tracking-[0.25em] text-black font-extrabold mt-3">
                            Venerdì 9 Ottobre 2026
                        </p>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto pt-6 text-center">
                        <!-- Ceremony details -->
                        <div class="space-y-2 border-b md:border-b-0 md:border-r border-[#D1B280]/20 pb-4 md:pb-0 md:pr-6">
                            <p class="font-serif text-sm uppercase tracking-[0.2em] text-gold-dark font-bold">
                                La Cerimonia
                            </p>
                            <p class="font-serif text-lg text-charcoal leading-relaxed font-normal italic">
                                sarà celebrata alle ore <span class="font-serif font-extrabold text-black not-italic text-xl">11:00</span> presso la
                            </p>
                            <p class="font-serif text-lg sm:text-xl lg:text-2xl text-charcoal font-bold tracking-wide italic">
                                Parrocchia «Gesù Buon Pastore»
                            </p>
                            <p class="font-serif text-sm uppercase tracking-[0.2em] text-charcoal-light font-bold">
                                Via Guardialto, 76 - Gravina
                            </p>
                        </div>

                        <!-- Reception details -->
                        <div class="space-y-2 md:pl-6">
                            <p class="font-serif text-sm uppercase tracking-[0.2em] text-gold-dark font-bold">
                                Il Ricevimento
                            </p>
                            <p class="font-serif text-lg text-charcoal leading-relaxed font-normal italic">
                                dopo la cerimonia saremo lieti di festeggiare insieme presso
                            </p>
                            <p class="font-serif text-lg sm:text-xl lg:text-2xl text-charcoal font-bold tracking-wide italic">
                                «Masseria del Parco»
                            </p>
                            <p class="font-serif text-sm uppercase tracking-[0.2em] text-charcoal-light font-bold">
                                S.P. 8 Matera-Grassano, Km 5.420 - Matera
                            </p>
                        </div>
                    </div>

                    <!-- Address cards -->
                    <div class="grid grid-cols-2 gap-4 pt-8 max-w-3xl mx-auto">
                        <!-- Left: Bride's side -->
                        <div class="space-y-0.5 font-serif text-sm text-charcoal-light border-r border-[#D1B280]/20 pr-4 text-left">
                            <p class="font-semibold text-charcoal text-base">Monica Amendolara</p>
                            <p>Via Guardialto 93 - <span class="uppercase tracking-wider text-[11px]">Gravina</span></p>
                        </div>

                        <!-- Right: Groom's side -->
                        <div class="space-y-0.5 font-serif text-sm text-charcoal-light pl-4 text-right">
                            <p class="font-semibold text-charcoal text-base">Erasmo Porfido</p>
                            <p>Via Trieste 68/B - <span class="uppercase tracking-wider text-[11px]">Gravina</span></p>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="w-full h-px bg-[#D1B280]/30 my-6 relative z-10"></div>

                <!-- Bottom Section: Info text instead of button -->
                <div class="space-y-3 pb-2 relative z-10 text-center">
                    <p class="font-serif italic text-[9px] lg:text-sm text-charcoal-light">
                        È gradita gentile conferma entro il 10 settembre 2026
                    </p>
                </div>

            </div>
        </div>
    </div>
</x-layout>
