<x-layout title="Dettagli - Matrimonio Monica Amendolara & Erasmo Porfido">
    <div class="flex items-center justify-center min-h-[70vh] w-full py-4">
        
        <!-- Unified Full-Page Invitation Card -->
        <div class="bg-white p-2.5 border border-[#D1B280]/40 rounded-sm shadow-2xl relative w-full max-w-2xl mx-auto transition-all duration-300">
            <div class="border border-[#D1B280]/60 p-4 sm:p-12 text-center relative paper-texture flex flex-col justify-center items-center min-h-[40vh] overflow-hidden">
                
                <!-- Background couple image watermark -->
                <div class="bg-couple-watermark" style="background-image: url('{{ asset('images/bg-sposi-processed.png') }}');"></div>

                <div class="space-y-8 relative z-10 w-full">
                    <span class="font-script text-gold-dark text-4xl select-none">Mappa e Indicazioni</span>
                    
                    <div class="flex flex-col sm:flex-row gap-6 justify-center items-center pt-4">
                        <!-- Ceremony Maps Button -->
                        <a href="https://www.google.com/maps/search/?api=1&query=Parrocchia+Ges%C3%B9+Buon+Pastore+Gravina+in+Puglia+Via+Guardialto+76" 
                           target="_blank" rel="noopener noreferrer" 
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-white border border-[#D1B280]/60 hover:bg-[#FAF6F0] text-charcoal font-serif text-sm uppercase tracking-wider font-semibold rounded-md shadow-md hover:shadow-lg active:scale-95 transition-all duration-200 cursor-pointer">
                            <svg class="w-4 h-4 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Mappa Cerimonia</span>
                        </a>

                        <!-- Reception Maps Button -->
                        <a href="https://www.google.com/maps/search/?api=1&query=Masseria+del+Parco+Matera+SP8" 
                           target="_blank" rel="noopener noreferrer" 
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-white border border-[#D1B280]/60 hover:bg-[#FAF6F0] text-charcoal font-serif text-sm uppercase tracking-wider font-semibold rounded-md shadow-md hover:shadow-lg active:scale-95 transition-all duration-200 cursor-pointer">
                            <svg class="w-4 h-4 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Mappa Ricevimento</span>
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="w-full h-px bg-[#D1B280]/30 my-6"></div>

                    <!-- Navigation back -->
                    <div class="pt-2">
                        <a href="/" 
                           class="inline-block px-8 py-2.5 bg-sage-dark hover:bg-sage-medium text-white font-serif text-xs uppercase tracking-wider font-semibold rounded-md shadow-md hover:shadow-lg active:scale-95 transition-all duration-200 cursor-pointer">
                            Torna alla Home
                        </a>
                    </div>
                </div>

            </div>
        </div>
        
    </div>
</x-layout>
