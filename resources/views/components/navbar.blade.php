<header class="w-full max-w-6xl mx-auto px-4 sm:px-6 py-4 sm:py-6 flex justify-between items-center border-b border-zinc-200/60 bg-white/80 backdrop-blur-md sticky top-0 z-50">
    <a href="/" class="flex items-center space-x-2 sm:space-x-3 group shrink-0">
        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border border-gold-medium/40 overflow-hidden bg-white shadow-sm flex items-center justify-center transition-all duration-300 group-hover:scale-105 group-hover:border-gold-medium group-hover:shadow-md">
            <img src="{{ asset('images/logo-sposi.png') }}" alt="Logo Monica & Erasmo" class="w-full h-full object-cover">
        </div>
        <span class="font-serif text-sm sm:text-lg font-semibold tracking-wide text-charcoal group-hover:text-gold-dark transition duration-200">
            Matrimonio <span class="font-script text-gold-dark text-xl sm:text-2xl ml-0.5 sm:ml-1">M<span class="ampersand text-lg sm:text-xl align-middle mx-0.5">&amp;</span>E</span>
        </span>
    </a>
    
    <!-- Desktop Navigation -->
    <nav class="hidden lg:flex items-center space-x-6">
        <a href="/" class="text-xs uppercase tracking-wider font-semibold {{ request()->is('/') ? 'text-gold-dark' : 'text-charcoal hover:text-gold-dark' }} transition duration-200">Home</a>
        <a href="/dettagli" class="text-xs uppercase tracking-wider font-semibold {{ request()->is('dettagli') ? 'text-gold-dark' : 'text-charcoal hover:text-gold-dark' }} transition duration-200">Dettagli</a>
        @if(auth()->check() && auth()->user()->isAdmin())
            <a href="/zona-selfie" class="text-xs uppercase tracking-wider font-semibold {{ request()->is('zona-selfie') ? 'text-gold-dark' : 'text-charcoal hover:text-gold-dark' }} transition duration-200">Zona Selfie</a>
        @endif
        <a href="/wedding-fight" class="text-xs uppercase tracking-wider font-semibold {{ request()->is('wedding-fight') ? 'text-red-700 font-bold' : 'text-charcoal hover:text-red-700' }} transition duration-200 flex items-center gap-1">🥊 Wedding Fight</a>
        <a href="#" id="iban-trigger" class="text-xs uppercase tracking-wider font-semibold text-charcoal-light hover:text-gold-dark transition duration-200">Regalo</a>
        <a href="/stampa-invito" target="_blank" class="text-xs uppercase tracking-wider font-semibold text-charcoal-light hover:text-gold-dark transition duration-200">Stampa Invito</a>
        @auth
            <a href="/logout" class="text-xs uppercase tracking-wider font-semibold text-charcoal-light hover:text-red-500 transition duration-200">Esci</a>
        @endauth
    </nav>

    <!-- Hamburger Button for Mobile/Tablet -->
    <button id="mobile-menu-toggle" class="lg:hidden flex items-center justify-center w-10 h-10 border border-[#D1B280]/20 rounded-md bg-white/80 hover:bg-[#FAF6F0] text-charcoal hover:text-gold-dark transition-all duration-300 z-50 cursor-pointer" aria-label="Apri menu">
        <svg id="hamburger-icon" class="w-6 h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg id="close-icon" class="w-6 h-6 hidden transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</header>

<!-- Mobile Navigation Drawer -->
<div id="mobile-menu-backdrop" class="fixed inset-0 bg-charcoal/60 backdrop-blur-sm z-[40] opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden"></div>

<div id="mobile-menu-sidebar" class="fixed top-0 right-0 h-full w-[280px] bg-white border-l border-[#D1B280]/20 z-[45] translate-x-full transition-transform duration-300 ease-in-out lg:hidden flex flex-col paper-texture shadow-2xl">
    <!-- Header inside drawer -->
    <div class="p-6 border-b border-[#D1B280]/15 flex items-center justify-between">
        <a href="/" class="flex items-center space-x-2">
            <div class="w-8 h-8 rounded-full border border-gold-medium/40 overflow-hidden bg-white shadow-sm flex items-center justify-center">
                <img src="{{ asset('images/logo-sposi.png') }}" alt="Logo Monica & Erasmo" class="w-full h-full object-cover">
            </div>
            <span class="font-serif text-sm font-semibold tracking-wide text-charcoal">
                Matrimonio <span class="font-script text-gold-dark text-xl ml-1">M<span class="ampersand text-sm align-middle">&amp;</span>E</span>
            </span>
        </a>
    </div>

    <!-- Navigation links inside drawer -->
    <nav class="flex-grow py-8 px-6 flex flex-col space-y-6">
        <a href="/" class="text-sm uppercase tracking-wider font-semibold {{ request()->is('/') ? 'text-gold-dark font-bold' : 'text-charcoal hover:text-gold-dark' }} transition duration-200 flex items-center gap-3">
            <svg class="w-4 h-4 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Home
        </a>
        <a href="/dettagli" class="text-sm uppercase tracking-wider font-semibold {{ request()->is('dettagli') ? 'text-gold-dark font-bold' : 'text-charcoal hover:text-gold-dark' }} transition duration-200 flex items-center gap-3">
            <svg class="w-4 h-4 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Dettagli
        </a>
        @if(auth()->check() && auth()->user()->isAdmin())
            <a href="/zona-selfie" class="text-sm uppercase tracking-wider font-semibold {{ request()->is('zona-selfie') ? 'text-gold-dark font-bold' : 'text-charcoal hover:text-gold-dark' }} transition duration-200 flex items-center gap-3">
                <svg class="w-4 h-4 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Zona Selfie
            </a>
        @endif
        <a href="/wedding-fight" class="text-sm uppercase tracking-wider font-semibold {{ request()->is('wedding-fight') ? 'text-red-700 font-bold' : 'text-charcoal hover:text-red-700' }} transition duration-200 flex items-center gap-3">
            <span class="text-base">🥊</span>
            Wedding Fight
        </a>
        <a href="#" id="iban-trigger-mobile" class="text-sm uppercase tracking-wider font-semibold text-charcoal hover:text-gold-dark transition duration-200 flex items-center gap-3">
            <svg class="w-4 h-4 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Regalo
        </a>
        <a href="/stampa-invito" target="_blank" class="text-sm uppercase tracking-wider font-semibold text-charcoal hover:text-gold-dark transition duration-200 flex items-center gap-3">
            <svg class="w-4 h-4 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Stampa Invito
        </a>
        @auth
            <a href="/logout" class="text-sm uppercase tracking-wider font-semibold text-charcoal hover:text-red-500 transition duration-200 flex items-center gap-3">
                <svg class="w-4 h-4 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Esci
            </a>
        @endauth
    </nav>

    <!-- Footer of drawer -->
    <div class="p-6 border-t border-[#D1B280]/15 text-center text-[10px] text-charcoal-light italic font-serif">
        &copy; 2026 Monica & Erasmo
    </div>
</div>

<!-- Iban Modal -->
<div id="iban-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-charcoal/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div id="iban-modal-card" class="bg-white p-2.5 border border-[#D1B280]/40 rounded-sm shadow-2xl relative w-full max-w-md mx-auto transform scale-95 transition-all duration-300">
        <div class="border border-[#D1B280]/60 p-8 sm:p-10 text-center relative paper-texture flex flex-col justify-between h-full overflow-hidden">
            
            <!-- Close Button (X icon at top-right) -->
            <button id="iban-modal-close-x" class="absolute top-4 right-4 text-charcoal-light hover:text-gold-dark transition-colors duration-200 cursor-pointer" aria-label="Chiudi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Decorative decagon monogram/icon wrapped in foliage (small version for modal header) -->
            <div class="relative w-20 h-20 mx-auto flex items-center justify-center select-none mb-4">
                <svg class="absolute inset-1 w-[calc(100%-8px)] h-[calc(100%-8px)] text-gold-medium/80" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="0.8">
                    <polygon points="50,2 79,11 98,38 98,72 79,98 50,89 21,98 2,72 2,38 21,11" />
                </svg>
                <svg class="w-8 h-8 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <div class="space-y-4 pt-2 relative z-10">
                <span class="font-script text-gold-dark text-3xl select-none">Regalo di Nozze</span>
                
                <!-- Divider -->
                <div class="w-full h-px bg-[#D1B280]/30 my-4"></div>
                
                <p class="font-serif text-sm text-charcoal leading-relaxed text-center italic">
                    "Abbiamo già pensato alla casa, all'arredamento e al gatto. Se desiderate farci un regalo, un piccolo contributo per i nostri sogni nel cassetto e per i progetti futuri è quello che ci vuole!"
                </p>

                <!-- IBAN Display Box -->
                <div class="bg-[#FAF6F0] border border-[#D1B280]/30 rounded-md p-4 mt-6 relative space-y-4">
                    <!-- Account Holder -->
                    <div class="text-left">
                        <p class="font-serif text-[10px] uppercase tracking-wider text-gold-dark font-semibold mb-2">
                            Intestato a
                        </p>
                        <div class="flex items-center justify-between gap-2 bg-white px-3 py-2 border border-zinc-200/60 rounded">
                            <span id="holder-text" class="font-mono text-[10px] sm:text-[11px] text-charcoal font-semibold tracking-wider select-all">Porfido Erasmo e Monica Amendolara</span>
                            <button id="holder-copy-btn" class="p-1 text-charcoal-light hover:text-gold-dark transition-colors duration-200 cursor-pointer" title="Copia Intestatario">
                                <!-- Copy icon -->
                                <svg id="holder-copy-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5h2a2 2 0 002-2M8 5a2 2 0 002 2h2a2 2 0 002-2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                                <!-- Check icon (hidden initially) -->
                                <svg id="holder-check-icon" class="w-4 h-4 text-sage-medium hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- IBAN -->
                    <div class="text-left">
                        <p class="font-serif text-[10px] uppercase tracking-wider text-gold-dark font-semibold mb-2">
                            IBAN
                        </p>
                        <div class="flex items-center justify-between gap-2 bg-white px-3 py-2 border border-zinc-200/60 rounded">
                            <span id="iban-text" class="font-mono text-[11px] text-charcoal font-semibold tracking-wider select-all">IT73P0329601601000067653476</span>
                            <button id="iban-copy-btn" class="p-1 text-charcoal-light hover:text-gold-dark transition-colors duration-200 cursor-pointer" title="Copia IBAN">
                                <!-- Copy icon -->
                                <svg id="copy-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5h2a2 2 0 002-2M8 5a2 2 0 002 2h2a2 2 0 002-2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                                <!-- Check icon (hidden initially) -->
                                <svg id="check-icon" class="w-4 h-4 text-sage-medium hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <span id="copy-feedback" class="absolute -top-3 right-4 bg-sage-dark text-white text-[10px] px-2 py-0.5 rounded shadow opacity-0 transition-opacity duration-300 pointer-events-none font-serif">
                        Copiato!
                    </span>
                </div>
            </div>

            <!-- Footer Close Button -->
            <div class="mt-8">
                <button id="iban-modal-close-btn" class="px-6 py-2 bg-sage-dark hover:bg-sage-medium text-white font-serif text-xs uppercase tracking-wider font-semibold rounded-md shadow-md hover:shadow-lg active:scale-95 transition-all duration-200 cursor-pointer">
                    Chiudi
                </button>
            </div>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', () => {
    // Mobile Drawer Logic
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
    const mobileMenuSidebar = document.getElementById('mobile-menu-sidebar');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');

    const openMobileMenu = () => {
        if (mobileMenuSidebar) {
            mobileMenuSidebar.classList.remove('translate-x-full');
            mobileMenuSidebar.classList.add('translate-x-0');
        }
        if (mobileMenuBackdrop) {
            mobileMenuBackdrop.classList.remove('opacity-0', 'pointer-events-none');
            mobileMenuBackdrop.classList.add('opacity-100');
        }
        if (hamburgerIcon) hamburgerIcon.classList.add('hidden');
        if (closeIcon) closeIcon.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    const closeMobileMenu = () => {
        if (mobileMenuSidebar) {
            mobileMenuSidebar.classList.remove('translate-x-0');
            mobileMenuSidebar.classList.add('translate-x-full');
        }
        if (mobileMenuBackdrop) {
            mobileMenuBackdrop.classList.remove('opacity-100');
            mobileMenuBackdrop.classList.add('opacity-0', 'pointer-events-none');
        }
        if (hamburgerIcon) hamburgerIcon.classList.remove('hidden');
        if (closeIcon) closeIcon.classList.add('hidden');
        
        // Only restore body overflow if no other modal is open
        const isIbanOpen = modal && !modal.classList.contains('opacity-0');
        if (!isIbanOpen) {
            document.body.style.overflow = '';
        }
    };

    if (mobileMenuToggle && mobileMenuSidebar && mobileMenuBackdrop) {
        mobileMenuToggle.addEventListener('click', () => {
            const isOpen = mobileMenuSidebar.classList.contains('translate-x-0');
            if (isOpen) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });

        mobileMenuBackdrop.addEventListener('click', closeMobileMenu);
        
        // Close menu when clicking links that aren't triggers
        const drawerLinks = mobileMenuSidebar.querySelectorAll('a:not([id*="trigger"])');
        drawerLinks.forEach(link => {
            link.addEventListener('click', closeMobileMenu);
        });
    }

    // IBAN Modal Logic
    const trigger = document.getElementById('iban-trigger');
    const triggerMobile = document.getElementById('iban-trigger-mobile');
    const modal = document.getElementById('iban-modal');
    const card = document.getElementById('iban-modal-card');
    const closeBtnX = document.getElementById('iban-modal-close-x');
    const closeBtnBtn = document.getElementById('iban-modal-close-btn');
    const copyBtn = document.getElementById('iban-copy-btn');
    const ibanText = document.getElementById('iban-text');
    const copyFeedback = document.getElementById('copy-feedback');
    const copyIcon = document.getElementById('copy-icon');
    const checkIcon = document.getElementById('check-icon');
    
    const holderCopyBtn = document.getElementById('holder-copy-btn');
    const holderText = document.getElementById('holder-text');
    const holderCopyIcon = document.getElementById('holder-copy-icon');
    const holderCheckIcon = document.getElementById('holder-check-icon');
    
    if ((trigger || triggerMobile) && modal && card) {
        const openModal = (e) => {
            e.preventDefault();
            // Close mobile menu if it is open
            closeMobileMenu();
            modal.classList.remove('opacity-0', 'pointer-events-none');
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
            document.body.style.overflow = 'hidden';
        };
        
        const closeModal = () => {
            modal.classList.add('opacity-0', 'pointer-events-none');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
            document.body.style.overflow = '';
        };
        
        if (trigger) trigger.addEventListener('click', openModal);
        if (triggerMobile) triggerMobile.addEventListener('click', openModal);
        
        if (closeBtnX) closeBtnX.addEventListener('click', closeModal);
        if (closeBtnBtn) closeBtnBtn.addEventListener('click', closeModal);
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
        
        // Escape key close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('opacity-0')) {
                closeModal();
            }
        });
        
        const setupCopyButton = (btn, textEl, iconEl, checkEl) => {
            if (btn && textEl) {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    navigator.clipboard.writeText(textEl.textContent.trim()).then(() => {
                        copyFeedback.classList.remove('opacity-0');
                        copyFeedback.classList.add('opacity-100');
                        iconEl.classList.add('hidden');
                        checkEl.classList.remove('hidden');
                        
                        setTimeout(() => {
                            copyFeedback.classList.remove('opacity-100');
                            copyFeedback.classList.add('opacity-0');
                            iconEl.classList.remove('hidden');
                            checkEl.classList.add('hidden');
                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy text: ', err);
                    });
                });
            }
        };

        setupCopyButton(copyBtn, ibanText, copyIcon, checkIcon);
        setupCopyButton(holderCopyBtn, holderText, holderCopyIcon, holderCheckIcon);
    }


});
</script>
