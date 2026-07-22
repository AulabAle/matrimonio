<x-layout title="Invito di Matrimonio - Monica Amendolara & Erasmo Porfido">
    <div class="flex items-center justify-center min-h-[75vh] w-full py-4">
        
        <!-- Unified Full-Page Invitation Card -->
        <div class="bg-white p-2.5 border border-[#D1B280]/40 rounded-sm shadow-2xl relative w-full max-w-4xl lg:max-w-5xl mx-auto transition-all duration-300">
            <div class="border border-[#D1B280]/60 p-4 sm:p-12 md:p-16 text-center relative paper-texture flex flex-col justify-between h-full min-h-[70vh] overflow-hidden">
                
                <!-- Background couple image watermark -->
                <div class="bg-couple-watermark" style="background-image: url('{{ asset('images/bg-sposi-processed.png') }}');"></div>

                <!-- Dense Foliage cascading from top-right corner (matches reference image) -->
                <div class="absolute top-0 right-0 w-28 h-24 lg:w-56 lg:h-48 pointer-events-none opacity-90 overflow-hidden">
                    <svg viewBox="0 0 150 120" fill="none" class="w-full h-full text-sage-medium">
                        <!-- Main branches -->
                        <path d="M150,0 Q120,8 90,32" stroke="currentColor" stroke-width="1.2" />
                        <path d="M130,0 Q95,15 70,45" stroke="currentColor" stroke-width="0.9" />
                        <path d="M110,0 Q75,22 55,58" stroke="currentColor" stroke-width="0.7" />
                        <!-- Sage Green Leaves -->
                        <path d="M115,14 Q105,18 108,24 Q116,25 119,18 Z" fill="currentColor" />
                        <path d="M92,24 Q82,28 85,34 Q93,35 96,28 Z" fill="currentColor" />
                        <path d="M72,38 Q62,42 65,48 Q73,49 76,42 Z" fill="currentColor" />
                        <path d="M54,52 Q44,56 47,62 Q55,63 58,56 Z" fill="currentColor" />
                        
                        <!-- Dark Sage Green Leaves -->
                        <path d="M104,18 Q94,12 91,17 Q93,24 100,22 Z" fill="var(--color-sage-dark)" opacity="0.95" />
                        <path d="M82,28 Q72,22 69,27 Q71,34 78,32 Z" fill="var(--color-sage-dark)" opacity="0.95" />
                        <path d="M62,42 Q52,36 49,41 Q51,48 58,46 Z" fill="var(--color-sage-dark)" opacity="0.95" />
                        
                        <!-- Accent Gold Leaf touches -->
                        <path d="M128,8 Q120,6 122,12 Q128,14 130,10 Z" fill="var(--color-gold-medium)" opacity="0.7" />
                        <path d="M85,36 Q78,32 76,37 Q80,42 86,39 Z" fill="var(--color-gold-medium)" opacity="0.7" />
                    </svg>
                </div>

                <!-- Dense Foliage rising from bottom-left corner (matches reference image) -->
                <div class="absolute bottom-0 left-0 w-28 h-24 lg:w-56 lg:h-48 pointer-events-none opacity-90 overflow-hidden">
                    <svg viewBox="0 0 150 120" fill="none" class="w-full h-full text-sage-medium">
                        <!-- Main branches curving up-right -->
                        <path d="M0,120 Q30,112 60,88" stroke="currentColor" stroke-width="1.2" />
                        <path d="M20,120 Q55,105 80,75" stroke="currentColor" stroke-width="0.9" />
                        <path d="M40,120 Q75,98 95,62" stroke="currentColor" stroke-width="0.7" />
                        <!-- Sage Green Leaves -->
                        <path d="M35,106 Q45,102 42,96 Q34,95 31,102 Z" fill="currentColor" />
                        <path d="M58,96 Q68,92 65,86 Q57,85 54,92 Z" fill="currentColor" />
                        <path d="M78,82 Q88,78 85,72 Q77,71 74,78 Z" fill="currentColor" />
                        <path d="M96,68 Q106,64 103,58 Q95,57 92,64 Z" fill="currentColor" />
                        
                        <!-- Dark Sage Green Leaves -->
                        <path d="M46,102 Q56,108 59,103 Q57,96 50,98 Z" fill="var(--color-sage-dark)" opacity="0.95" />
                        <path d="M68,92 Q78,98 81,93 Q79,86 72,88 Z" fill="var(--color-sage-dark)" opacity="0.95" />
                        <path d="M88,78 Q98,84 101,79 Q99,72 92,74 Z" fill="var(--color-sage-dark)" opacity="0.95" />
                        
                        <!-- Accent Gold Leaf touches -->
                        <path d="M22,112 Q30,114 28,108 Q22,106 20,110 Z" fill="var(--color-gold-medium)" opacity="0.7" />
                        <path d="M65,84 Q72,88 74,83 Q70,78 64,81 Z" fill="var(--color-gold-medium)" opacity="0.7" />
                    </svg>
                </div>

                <!-- Top Section: Monogram, Names and Main Date -->
                <div class="space-y-6 pt-0 relative z-10">
                    <!-- Geometric decagon monogram wrapped in foliage -->
                    <div class="relative w-16 h-16 lg:w-32 lg:h-32 mt-4 lg:-mt-10 mb-2 lg:mb-8 mx-auto flex items-center justify-center select-none">
                        <!-- Left foliage wrapper -->
                        <div class="absolute inset-0 pointer-events-none text-sage-medium/40">
                             <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                                <path d="M32,78 C18,65 14,40 28,22" stroke="currentColor" stroke-width="0.8" />
                                <path d="M22,60 Q12,58 16,53 C20,48 24,53 22,60 Z" fill="currentColor" />
                                <path d="M18,42 Q10,38 15,33 C20,28 22,35 18,42 Z" fill="var(--color-sage-dark)" opacity="0.8" />
                                <path d="M26,28 Q18,22 23,18 C28,14 30,22 26,28 Z" fill="currentColor" />
                            </svg>
                        </div>
                        
                        <!-- Right foliage wrapper -->
                        <div class="absolute inset-0 pointer-events-none text-sage-medium/40">
                            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                                <path d="M68,22 C82,35 86,60 72,78" stroke="currentColor" stroke-width="0.8" />
                                <path d="M78,40 Q88,42 84,47 C80,52 76,47 78,40 Z" fill="currentColor" />
                                <path d="M82,58 Q90,62 85,67 C80,72 78,65 82,58 Z" fill="var(--color-sage-dark)" opacity="0.8" />
                                <path d="M74,72 Q82,78 77,82 C72,86 70,78 74,72 Z" fill="currentColor" />
                            </svg>
                        </div>
 
                        <!-- Monogram Decagon Frame -->
                        <svg class="absolute inset-[7.8%] w-[84.4%] h-[84.4%] text-gold-medium/80" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="0.8">
                            <polygon points="50,2 79,11 98,38 98,72 79,98 50,89 21,98 2,72 2,38 21,11" />
                            <polygon points="50,6 76,14 94,39 94,70 76,94 50,85 24,94 6,70 6,39 24,14" stroke-width="0.5" stroke-dasharray="2 2" opacity="0.7"/>
                        </svg>
                        <span class="font-script text-gold-dark text-2xl lg:text-4xl relative top-[1.5%] -left-[3%]" style="text-shadow: 0 0 12px #FAF6F0, 0 0 8px #FAF6F0, 0 0 4px #FAF6F0;">M<span class="ampersand text-lg lg:text-3xl align-middle mx-1">&amp;</span>E</span>
                    </div>

                    <div class="space-y-2 mt-4 sm:mt-6 lg:!mt-36">
                        <h1 class="font-script text-4xl sm:text-4xl md:text-5xl lg:text-6xl text-charcoal font-medium select-none flex flex-col md:flex-row items-center justify-center gap-1 md:gap-4 leading-normal sm:leading-relaxed">
                            <span class="whitespace-nowrap">Monica Amendolara</span>
                            <span class="ampersand text-2xl sm:text-3xl md:text-3xl lg:text-5xl font-normal select-none mx-2">&amp;</span>
                            <span class="whitespace-nowrap">Erasmo Porfido</span>
                        </h1>
                    </div>
                </div>

                <!-- Divider -->
                <div class="w-full h-px bg-[#D1B280]/30 my-4 sm:my-6 relative z-10"></div>

                <!-- Middle Section: "Ci Sposiamo" & Ceremony/Reception Details -->
                <div class="space-y-4 md:space-y-6 my-2 relative z-10">
                    <div class="space-y-3 pt-2 md:pt-4">
                        <span class="font-script text-gold-dark text-[26px] sm:text-[28px] lg:text-4xl select-none">annunciano il loro matrimonio</span>
                        <p class="font-serif text-xs sm:text-sm lg:text-base uppercase tracking-[0.25em] text-black font-extrabold">
                            Venerdì 9 Ottobre 2026
                        </p>
                    </div>

                    <!-- Ceremony & Reception Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 max-w-4xl mx-auto pt-2 !mt-1 sm:!mt-3">
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

                    <!-- Address cards (Left and Right columns as in paper invitation) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-4 pt-3 sm:pt-4 max-w-3xl mx-auto">
                        <!-- Left: Bride's side -->
                        <div class="space-y-0.5 font-serif text-sm text-charcoal-light sm:border-r border-b sm:border-b-0 border-[#D1B280]/20 pb-4 sm:pb-0 pr-0 sm:pr-4 text-center sm:text-left">
                            <p class="font-semibold text-charcoal text-base">Monica Amendolara</p>
                            <p>Via Guardialto 93 - <span class="uppercase tracking-wider text-[11px]">Gravina</span></p>
                        </div>

                        <!-- Right: Groom's side -->
                        <div class="space-y-0.5 font-serif text-sm text-charcoal-light pl-0 sm:pl-4 text-center sm:text-right">
                            <p class="font-semibold text-charcoal text-base">Erasmo Porfido</p>
                            <p>Via Trieste 68/B - <span class="uppercase tracking-wider text-[11px]">Gravina</span></p>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="w-full h-px bg-[#D1B280]/30 my-4 sm:my-6 relative z-10"></div>

                <!-- Bottom Section: RSVP Confirmation Button -->
                <div class="space-y-4 sm:space-y-6 pb-2 relative z-10">
                    <div class="space-y-4">
                        <a href="/conferma" 
                           class="inline-block px-6 py-2.5 lg:px-8 lg:py-3 bg-sage-dark hover:bg-sage-medium text-white font-serif text-[11px] lg:text-xs uppercase tracking-wider font-semibold rounded-md shadow-md hover:shadow-lg active:scale-95 transition-all duration-200 cursor-pointer">
                            Conferma la tua partecipazione
                        </a>
                    </div>

                    <p class="font-serif italic text-[9px] lg:text-sm text-charcoal-light">
                        È gradita gentile conferma entro il 10 settembre 2026
                    </p>
                </div>

            </div>
        </div>
        
    </div>
</x-layout>
