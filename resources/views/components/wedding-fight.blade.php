<style>
    /* Prevent default browser touch scrolling and pull-to-refresh during game on mobile */
    .touch-btn {
        touch-action: none;
        -webkit-tap-highlight-color: transparent;
    }
    
    /* Mobile Portrait Warning Overlay */
    @media screen and (max-width: 1024px) and (orientation: portrait) {
        #portraitNoticeOverlay {
            display: flex !important;
            z-index: 99999999 !important;
        }
        #gameMainCard {
            opacity: 0.15;
            pointer-events: none;
            filter: blur(4px);
        }
    }
    
    /* Mobile Landscape PURE FULLSCREEN GAME VIEW (COMPLETELY ELIMINATES NAVBAR, FOOTER, HEADER, MARGINS) */
    @media screen and (max-width: 1024px) and (orientation: landscape) {
        /* Hide Navbar, Footer, Drawers, Watermarks, Title Section */
        header,
        footer,
        nav,
        #mobile-menu-backdrop,
        #mobile-menu-sidebar,
        #gameHeaderTitleSection,
        .bg-couple-watermark {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            width: 0 !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        /* Lock Body & HTML to full window height with zero scrolling */
        html, body {
            overflow: hidden !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            height: 100dvh !important;
            background-color: #09090b !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
        }

        /* Expand Main Blade slot to fill window */
        main {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100vw !important;
            width: 100vw !important;
            height: 100vh !important;
            height: 100dvh !important;
            display: block !important;
            position: fixed !important;
            inset: 0 !important;
            z-index: 999999 !important;
        }

        /* Fixed 100% Fullscreen Game Card */
        #gameMainCard {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            inset: 0 !important;
            z-index: 9999999 !important;
            width: 100vw !important;
            height: 100vh !important;
            height: 100dvh !important;
            max-width: 100vw !important;
            max-height: 100vh !important;
            max-height: 100dvh !important;
            border-radius: 0 !important;
            border: none !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            background-color: #09090b !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            align-items: stretch !important;
            padding-left: env(safe-area-inset-left, 0px) !important;
            padding-right: env(safe-area-inset-right, 0px) !important;
            padding-bottom: env(safe-area-inset-bottom, 0px) !important;
        }

        /* Canvas Wrapper fills all space between header bar and touch controls */
        #canvasWrapper {
            flex: 1 1 0% !important;
            height: 100% !important;
            width: 100% !important;
            min-height: 0 !important;
            overflow: hidden !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background-color: #000000 !important;
            position: relative !important;
        }

        /* Canvas dynamically fits within Wrapper retaining 16:9 arcade ratio */
        #fightCanvas {
            max-width: 100% !important;
            max-height: 100% !important;
            width: auto !important;
            height: auto !important;
            aspect-ratio: 16 / 9 !important;
            object-fit: contain !important;
        }
    }

    /* Dynamic Responsive Adjustments for Mobile Landscape with Short Viewport Height (max-height: 520px) */
    @media screen and (orientation: landscape) and (max-height: 520px) {
        /* Main Container 100dvh Flex */
        #gameMainCard {
            height: 100dvh !important;
            max-height: 100dvh !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            overflow: hidden !important;
        }

        /* Top Bar Compact */
        #gameMainCard > div:first-child {
            padding-top: 0.2rem !important;
            padding-bottom: 0.2rem !important;
        }

        /* Touch Controls Bar Compact */
        #touchControlsBar {
            padding: 0.25rem 0.75rem !important;
            shrink: 0 !important;
        }
        #btnLeft, #btnRight {
            width: 2.75rem !important;
            height: 2.75rem !important;
            font-size: 1.25rem !important;
        }
        #btnAttack {
            width: auto !important;
            min-width: 6.5rem !important;
            height: 2.75rem !important;
            padding: 0.25rem 0.85rem !important;
            font-size: 0.75rem !important;
            font-family: 'Montserrat', monospace, sans-serif !important;
            font-weight: 900 !important;
            color: #FFFFFF !important;
            background: linear-gradient(135deg, #DC2626, #B91C1C) !important;
            border: 2px solid #EF4444 !important;
            border-radius: 0.75rem !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.35rem !important;
            text-align: center !important;
            white-space: nowrap !important;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.5) !important;
            overflow: hidden !important;
        }

        /* Selection Screen Compact Layout (Zero Scroll, Complete Visibility) */
        #selectScreen {
            padding: 0.35rem !important;
            justify-content: center !important;
            overflow: hidden !important;
        }
        #selectScreenHeader {
            margin-bottom: 0.25rem !important;
        }
        #selectScreenTitle {
            font-size: 1.1rem !important;
            margin-top: 0 !important;
            line-height: 1.1 !important;
        }
        #selectScreenSub {
            font-size: 0.6rem !important;
            margin-top: 0 !important;
        }
        #selectGridContainer {
            gap: 0.5rem !important;
            margin-bottom: 0.35rem !important;
            max-width: 26rem !important;
        }
        .char-card {
            padding: 0.25rem 0.5rem !important;
            border-radius: 0.5rem !important;
        }
        .char-card-img {
            width: 2.75rem !important;
            height: 2.75rem !important;
            margin-bottom: 0.15rem !important;
        }
        .char-card-title {
            font-size: 0.75rem !important;
            line-height: 1 !important;
        }
        .char-card-weapon {
            font-size: 0.55rem !important;
            margin-top: 0.1rem !important;
        }
        .char-card-badge {
            margin-top: 0.15rem !important;
            padding: 0.05rem 0.35rem !important;
            font-size: 0.5rem !important;
        }
        #startMatchBtn {
            padding: 0.3rem 1.25rem !important;
            font-size: 0.75rem !important;
            line-height: 1.2 !important;
        }

        /* Game Over Screen Compact Layout */
        #gameOverScreen {
            padding: 0.35rem !important;
            justify-content: center !important;
        }
        #winnerTitle {
            font-size: 1.25rem !important;
            margin-top: 0.15rem !important;
            margin-bottom: 0.15rem !important;
        }
        #gameOverBox {
            padding: 0.4rem 0.6rem !important;
            margin-top: 0.2rem !important;
            margin-bottom: 0.3rem !important;
            max-width: 24rem !important;
        }
        #winnerQuote {
            font-size: 0.75rem !important;
            line-height: 1.2 !important;
        }
        #posterBadge {
            font-size: 0.55rem !important;
            margin-top: 0.2rem !important;
            padding-top: 0.2rem !important;
        }
        #rematchBtn, #changeCharBtn {
            padding: 0.25rem 0.75rem !important;
            font-size: 0.7rem !important;
        }
    }

    /* Desktop View (PC) - Standard Centered Page Layout */
    @media screen and (min-width: 1025px) {
        #portraitNoticeOverlay {
            display: none !important;
        }
    }
</style>

<!-- OVERLAY: PORTRAIT ORIENTATION WARNING FOR MOBILE DEVICES -->
<div id="portraitNoticeOverlay" class="fixed inset-0 z-50 bg-zinc-950/95 backdrop-blur-xl flex flex-col items-center justify-center p-6 text-white text-center hidden">
    <div class="relative mb-6">
        <div class="w-20 h-20 rounded-2xl border-4 border-[#D1B280] flex items-center justify-center bg-zinc-900 shadow-[0_0_30px_rgba(209,178,128,0.3)] animate-bounce">
            <span class="text-4xl">📱</span>
        </div>
        <span class="absolute -top-2 -right-2 text-2xl animate-pulse">🔄</span>
    </div>

    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-950/80 border border-red-700/60 text-red-300 text-xs font-semibold uppercase tracking-wider mb-3">
        <span>🎮 MODALITÀ ORIZZONTALE RICHIESTA</span>
    </div>

    <h2 class="text-2xl sm:text-3xl font-extrabold font-serif text-[#D1B280] tracking-wide mb-2 uppercase drop-shadow">
        Ruota lo Smartphone!
    </h2>

    <p class="text-sm sm:text-base text-zinc-300 max-w-xs leading-relaxed mb-6 font-sans">
        Per giocare al meglio ad <strong>Arcade Wedding Fight</strong> sul tuo cellulare, ruota il dispositivo in <strong>orizzontale</strong> 🔄
    </p>

    <div class="px-4 py-2 bg-zinc-900/80 rounded-xl border border-zinc-800 text-xs text-zinc-400 font-mono flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
        <span>Il videogioco si avvierà in automatico</span>
    </div>
</div>

<div class="w-full flex flex-col items-center justify-center py-2 sm:py-4 px-2 sm:px-4">
    <!-- Header Title Section (Hidden in Mobile Landscape Fullscreen) -->
    <div id="gameHeaderTitleSection" class="text-center mb-3 sm:mb-6 max-w-2xl px-4">
        <div class="inline-flex items-center gap-2 px-3 py-0.5 sm:py-1 rounded-full bg-[#8B0000]/20 border border-[#8B0000]/50 text-red-700 text-[10px] sm:text-xs font-semibold uppercase tracking-wider mb-1.5 sm:mb-2 shadow-sm">
            <span>🏛️ GRAVINA IN PUGLIA - ARCADE WEDDING FIGHT</span>
        </div>
        <h1 class="font-serif text-2xl sm:text-4xl lg:text-5xl font-bold text-charcoal tracking-tight">
            Edderasmo <span class="italic font-script text-red-700 text-3xl sm:text-5xl">vs</span> Yoshimonica
        </h1>
        <p class="text-xs sm:text-base text-charcoal-light mt-0.5 sm:mt-1 font-sans">
            "Il Peto Assassino" di Edderasmo vs "Il Grido di Munch" di Yoshimonica!
        </p>
    </div>

    <!-- Main Game Container Card -->
    <div id="gameMainCard" class="w-full max-w-4xl bg-zinc-950 rounded-xl sm:rounded-2xl shadow-2xl overflow-hidden border-2 border-[#D1B280]/40 relative flex flex-col justify-between items-center select-none transition-all">
        
        <!-- AUDIO & FULLSCREEN CONTROL HEADER BAR -->
        <div class="w-full bg-zinc-900/90 border-b border-zinc-800 px-3 sm:px-4 py-1.5 flex items-center justify-between z-10 text-xs text-[#D1B280] shrink-0">
            <span class="font-mono font-semibold flex items-center gap-2 text-[10px] sm:text-xs">
                <span>🎮 NINTENDO 8-BIT SPECIAL EDITION</span>
            </span>
            <div class="flex items-center gap-2">
                <button type="button" id="fullscreenBtn" class="px-2.5 py-1 rounded-lg bg-zinc-800 hover:bg-zinc-700 border border-gold-dark/50 text-gold-light text-xs font-bold flex items-center gap-1.5 transition-all cursor-pointer shadow-sm active:scale-95">
                    <span id="fullscreenIcon">⛶</span> <span class="inline">Schermo Intero</span>
                </button>
                <button type="button" id="audioToggleBtn" class="px-3 py-1 rounded-lg bg-red-950/80 hover:bg-red-900 border border-red-700/60 text-red-200 text-xs font-bold flex items-center gap-1.5 transition-all cursor-pointer shadow-sm active:scale-95">
                    <span id="audioIcon">🔊</span> <span id="audioText">Audio: ON</span>
                </button>
            </div>
        </div>

        <!-- CANVAS CONTAINER (Fills all vertical space on Mobile Landscape) -->
        <div id="canvasWrapper" class="relative w-full aspect-[16/9] sm:aspect-[16/9] bg-black overflow-hidden flex items-center justify-center min-h-0">
            
            <canvas id="fightCanvas" width="800" height="450" class="w-full h-full object-contain block bg-black"></canvas>

            <!-- OVERLAY: CHARACTER SELECTION SCREEN -->
            <div id="selectScreen" class="absolute inset-0 bg-black/85 backdrop-blur-md flex flex-col items-center justify-center p-3 sm:p-6 z-20 text-white transition-opacity duration-300 overflow-hidden">
                <div id="selectScreenHeader" class="text-center mb-2 sm:mb-6">
                    <span class="text-[10px] sm:text-xs uppercase tracking-widest text-[#D1B280] font-semibold">Matrimonio a Gravina in Puglia</span>
                    <h2 id="selectScreenTitle" class="text-xl sm:text-3xl lg:text-4xl font-extrabold font-serif tracking-wider text-red-500 drop-shadow-[0_2px_10px_rgba(239,68,68,0.6)] uppercase mt-0.5 sm:mt-1">
                        EDDERASMO VS YOSHIMONICA
                    </h2>
                    <p id="selectScreenSub" class="text-[10px] sm:text-xs text-zinc-300 italic mt-0.5">Scegli la tua arma letale e sfida il tuo destino!</p>
                </div>

                <div id="selectGridContainer" class="grid grid-cols-2 gap-3 sm:gap-8 max-w-xl w-full mb-3 sm:mb-6">
                    <!-- Sposo Selection Card -->
                    <button type="button" id="selectSposoBtn" class="char-card group relative bg-zinc-900/90 hover:bg-red-950/80 border-2 border-zinc-700 hover:border-red-500 rounded-xl p-2.5 sm:p-4 text-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_0_25px_rgba(220,38,38,0.5)] cursor-pointer">
                        <div class="char-card-img w-16 h-16 sm:w-28 sm:h-28 mx-auto mb-1.5 sm:mb-2 rounded-full border-2 border-[#D1B280] overflow-hidden relative shadow-lg bg-black">
                            <img src="{{ asset('images/game/sposo-idle.jpg') }}" alt="Edderasmo" class="w-full h-full object-cover object-top scale-125 transition-transform duration-300 group-hover:scale-135">
                        </div>
                        <h3 class="char-card-title font-serif font-bold text-sm sm:text-xl text-white group-hover:text-red-400">EDDERASMO</h3>
                        <p class="char-card-weapon text-[9px] sm:text-xs text-emerald-400 font-semibold mt-0.5">💨 Arma: Il Peto Assassino</p>
                        <div class="char-card-badge mt-1 sm:mt-2 inline-block px-2.5 sm:px-3 py-0.5 text-[9px] sm:text-[10px] bg-red-950/80 text-red-300 rounded border border-red-800/50 opacity-0 group-hover:opacity-100 transition-opacity">Seleziona</div>
                    </button>

                    <!-- Sposa Selection Card -->
                    <button type="button" id="selectSposaBtn" class="char-card group relative bg-zinc-900/90 hover:bg-red-950/80 border-2 border-zinc-700 hover:border-red-500 rounded-xl p-2.5 sm:p-4 text-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_0_25px_rgba(220,38,38,0.5)] cursor-pointer">
                        <div class="char-card-img w-16 h-16 sm:w-28 sm:h-28 mx-auto mb-1.5 sm:mb-2 rounded-full border-2 border-[#D1B280] overflow-hidden relative shadow-lg bg-black">
                            <img src="{{ asset('images/game/sposa-idle.jpg') }}" alt="Yoshimonica" class="w-full h-full object-cover object-top scale-125 transition-transform duration-300 group-hover:scale-135">
                        </div>
                        <h3 class="char-card-title font-serif font-bold text-sm sm:text-xl text-white group-hover:text-red-400">YOSHIMONICA</h3>
                        <p class="char-card-weapon text-[9px] sm:text-xs text-amber-400 font-semibold mt-0.5">😱 Arma: Il Grido di Munch</p>
                        <div class="char-card-badge mt-1 sm:mt-2 inline-block px-2.5 sm:px-3 py-0.5 text-[9px] sm:text-[10px] bg-red-950/80 text-red-300 rounded border border-red-800/50 opacity-0 group-hover:opacity-100 transition-opacity">Seleziona</div>
                    </button>
                </div>

                <button type="button" id="startMatchBtn" disabled class="px-6 sm:px-8 py-2.5 sm:py-3 bg-gradient-to-r from-red-800 to-red-600 hover:from-red-700 hover:to-red-500 disabled:from-zinc-800 disabled:to-zinc-900 disabled:opacity-40 text-white font-bold rounded-full text-sm sm:text-lg shadow-xl transition-all duration-200 transform hover:scale-105 cursor-pointer disabled:cursor-not-allowed uppercase tracking-wider border border-red-400/40">
                    Inizia il Match 🥊
                </button>
            </div>

            <!-- OVERLAY: GAME OVER / KO SCREEN -->
            <div id="gameOverScreen" class="hidden absolute inset-0 bg-black/90 backdrop-blur-lg flex flex-col items-center justify-center p-4 sm:p-6 z-30 text-white text-center overflow-hidden">
                <div class="inline-block px-3 sm:px-4 py-1 rounded-full bg-red-600 text-white font-black text-[10px] sm:text-xs uppercase tracking-widest mb-1.5 sm:mb-2 animate-bounce">
                    K.O. - MATCH CONCLUSO!
                </div>
                
                <h2 id="winnerTitle" class="text-2xl sm:text-5xl font-serif font-extrabold text-[#D1B280] drop-shadow-[0_4px_15px_rgba(209,178,128,0.5)] my-1 sm:my-2">
                    VITTORIA!
                </h2>

                <div id="gameOverBox" class="max-w-lg bg-zinc-900/90 border border-red-800/60 rounded-xl p-3 sm:p-6 my-2 sm:my-4 shadow-2xl">
                    <p id="winnerQuote" class="text-sm sm:text-xl text-zinc-100 italic font-serif leading-relaxed">
                        "Chi vince decide il futuro a Gravina in Puglia!"
                    </p>
                    <div id="posterBadge" class="mt-2 sm:mt-4 text-[10px] sm:text-[11px] text-red-400 uppercase tracking-wider font-semibold border-t border-zinc-800 pt-2 sm:pt-3">
                        ★ Cronache di Gravina in Puglia: "Scontro memorabile ai piedi della Gravina e sul Ponte Viadotto!" ★
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 sm:gap-4 justify-center mt-2">
                    <button type="button" id="rematchBtn" class="px-5 sm:px-6 py-2 sm:py-2.5 bg-red-700 hover:bg-red-600 text-white font-bold rounded-full text-xs sm:text-base shadow-lg transition-all transform hover:scale-105 cursor-pointer uppercase tracking-wider border border-red-400/40 active:scale-95">
                        🔄 Rivincita!
                    </button>
                    <button type="button" id="changeCharBtn" class="px-5 sm:px-6 py-2 sm:py-2.5 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold rounded-full text-xs sm:text-base border border-zinc-600 transition-all transform hover:scale-105 cursor-pointer uppercase tracking-wider active:scale-95">
                        👤 Cambia Combattente
                    </button>
                </div>
            </div>

        </div>

        <!-- ON-SCREEN MOBILE TOUCH CONTROLS -->
        <div id="touchControlsBar" class="w-full bg-zinc-950 border-t border-zinc-800 p-2.5 sm:p-4 flex items-center justify-between gap-2 z-10">
            <!-- Left / Right D-Pad Buttons -->
            <div class="flex items-center gap-2 sm:gap-3">
                <button type="button" id="btnLeft" class="touch-btn w-13 h-13 sm:w-16 sm:h-16 rounded-xl bg-zinc-800 hover:bg-zinc-700 active:bg-red-900 border border-zinc-700 text-white font-bold text-2xl sm:text-3xl flex items-center justify-center select-none shadow-md active:scale-95 transition-transform touch-none cursor-pointer">
                    ←
                </button>
                <button type="button" id="btnRight" class="touch-btn w-13 h-13 sm:w-16 sm:h-16 rounded-xl bg-zinc-800 hover:bg-zinc-700 active:bg-red-900 border border-zinc-700 text-white font-bold text-2xl sm:text-3xl flex items-center justify-center select-none shadow-md active:scale-95 transition-transform touch-none cursor-pointer">
                    →
                </button>
            </div>

            <!-- Keyboard Instructions Helper (PC Only) -->
            <div class="hidden md:flex flex-col items-center text-center text-xs text-zinc-400 font-mono">
                <span class="text-[#D1B280] font-semibold mb-0.5">CONTROLLI TASTIERA (PC)</span>
                <span><kbd class="px-1.5 py-0.5 bg-zinc-800 border border-zinc-700 rounded text-zinc-200">← / A</kbd> Indietro &nbsp;|&nbsp; <kbd class="px-1.5 py-0.5 bg-zinc-800 border border-zinc-700 rounded text-zinc-200">→ / D</kbd> Avanti</span>
                <span><kbd class="px-1.5 py-0.5 bg-zinc-800 border border-zinc-700 rounded text-zinc-200">SPAZIO / F</kbd> Attacco</span>
            </div>

            <!-- Mobile Helper -->
            <div class="flex md:hidden flex-col items-center text-center text-[10px] text-zinc-400 font-mono">
                <span class="text-[#D1B280] font-semibold">TOCCHI SMARTPHONE</span>
                <span>Peto vs Grido!</span>
            </div>

            <!-- Attack Button -->
            <div>
                <button type="button" id="btnAttack" class="touch-btn px-6 py-3.5 sm:px-8 sm:py-4 rounded-xl bg-gradient-to-r from-red-700 to-red-600 active:from-red-800 active:to-red-700 border-2 border-red-400 text-white font-black text-sm sm:text-xl flex items-center justify-center gap-1.5 select-none shadow-lg active:scale-95 transition-transform touch-none cursor-pointer uppercase tracking-wider font-mono text-center">
                    <span>🥊</span> <span class="text-white font-black uppercase tracking-wider font-mono drop-shadow">ATTACCA</span>
                </button>
            </div>
        </div>

    </div>
</div>

<!-- GAME LOGIC & SPECIAL WEAPONS SYNTHESIZER SCRIPT -->
<script>
(function() {
    // -------------------------------------------------------------
    // 1. WEB AUDIO API - NINTENDO 8-BIT SYNTHESIZER & SPECIAL SFX
    // -------------------------------------------------------------
    const AudioCtx = window.AudioContext || window.webkitAudioContext;
    let audioCtx = null;
    let isAudioEnabled = true;
    let bgmInterval = null;

    const audioToggleBtn = document.getElementById('audioToggleBtn');
    const audioIcon = document.getElementById('audioIcon');
    const audioText = document.getElementById('audioText');

    function initAudio() {
        if (!audioCtx) {
            audioCtx = new AudioCtx();
        }
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
    }

    function toggleAudio() {
        isAudioEnabled = !isAudioEnabled;
        if (isAudioEnabled) {
            audioIcon.textContent = '🔊';
            audioText.textContent = 'Audio: ON';
            initAudio();
            startBGM();
        } else {
            audioIcon.textContent = '🔇';
            audioText.textContent = 'Audio: OFF';
            stopBGM();
        }
    }

    audioToggleBtn.addEventListener('click', toggleAudio);

    // Helper for wave shaper distortion curves
    function createDistortionCurve(amount) {
        const k = typeof amount === 'number' ? amount : 50;
        const n_samples = 44100;
        const curve = new Float32Array(n_samples);
        const deg = Math.PI / 180;
        for (let i = 0; i < n_samples; ++i) {
            const x = (i * 2) / n_samples - 1;
            curve[i] = ((3 + k) * x * 20 * deg) / (Math.PI + k * Math.abs(x));
        }
        return curve;
    }

    // Audio element per la scorreggia dello sposo (Scorreggia.mp3)
    const scorreggiaAudio = new Audio("{{ asset('audio/scorreggia.mp3') }}");
    scorreggiaAudio.preload = 'auto';

    function playFartSFX() {
        if (!isAudioEnabled) return;
        initAudio();

        try {
            scorreggiaAudio.currentTime = 0;
            const playPromise = scorreggiaAudio.play();
            if (playPromise !== undefined) {
                playPromise.catch(err => console.log('Playback error Scorreggia:', err));
            }
        } catch (err) {
            console.log('Scorreggia audio play exception:', err);
        }
    }

    // YOSHIMONICA SPECIAL SFX: Realistic Female Scream Synthesizer ("Il Grido di Munch")
    // Audio element per l'urlo della sposa (Urlo.mp3)
    const urloAudio = new Audio("{{ asset('audio/urlo.mp3') }}");
    urloAudio.preload = 'auto';

    function playMunchScreamSFX() {
        if (!isAudioEnabled) return;
        initAudio();

        try {
            urloAudio.currentTime = 0;
            const playPromise = urloAudio.play();
            if (playPromise !== undefined) {
                playPromise.catch(err => console.log('Playback error Urlo:', err));
            }
        } catch (err) {
            console.log('Urlo audio play exception:', err);
        }
    }

    // Hit Impact Sound Effect
    function playHitSFX() {
        if (!isAudioEnabled) return;
        initAudio();

        const now = audioCtx.currentTime;

        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(220, now);
        osc.frequency.exponentialRampToValueAtTime(35, now + 0.16);

        gain.gain.setValueAtTime(0.7, now);
        gain.gain.exponentialRampToValueAtTime(0.005, now + 0.16);

        // Snap noise burst
        const bufferSize = Math.floor(audioCtx.sampleRate * 0.08);
        const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
        const data = buffer.getChannelData(0);
        for (let i = 0; i < bufferSize; i++) {
            data[i] = (Math.random() * 2 - 1);
        }
        const noise = audioCtx.createBufferSource();
        noise.buffer = buffer;

        const noiseGain = audioCtx.createGain();
        noiseGain.gain.setValueAtTime(0.5, now);
        noiseGain.gain.exponentialRampToValueAtTime(0.01, now + 0.08);

        osc.connect(gain);
        gain.connect(audioCtx.destination);
        noise.connect(noiseGain);
        noiseGain.connect(audioCtx.destination);

        osc.start(now);
        osc.stop(now + 0.16);
        noise.start(now);
    }

    // Nintendo C-Major Victory Fanfare Arpeggio
    function playVictoryFanfare() {
        if (!isAudioEnabled) return;
        initAudio();
        stopBGM();

        const notes = [
            { note: 523.25, duration: 0.12 },
            { note: 659.25, duration: 0.12 },
            { note: 783.99, duration: 0.12 },
            { note: 1046.50, duration: 0.40 }
        ];
        let now = audioCtx.currentTime;
        notes.forEach(n => {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'square';
            osc.frequency.setValueAtTime(n.note, now);
            gain.gain.setValueAtTime(0.3, now);
            gain.gain.exponentialRampToValueAtTime(0.01, now + n.duration);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start(now);
            osc.stop(now + n.duration);
            now += n.duration;
        });
    }

    // 8-Bit BGM Loop
    const melodyNotes = [
        329.63, 329.63, 392.00, 329.63, 293.66, 261.63, 246.94, 261.63,
        329.63, 329.63, 392.00, 440.00, 392.00, 349.23, 329.63, 293.66
    ];
    let noteIdx = 0;

    function startBGM() {
        if (bgmInterval || !isAudioEnabled) return;
        initAudio();

        bgmInterval = setInterval(() => {
            if (!isAudioEnabled || !audioCtx) return;
            const now = audioCtx.currentTime;
            const freq = melodyNotes[noteIdx % melodyNotes.length];
            noteIdx++;

            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = 'square';
            osc.frequency.setValueAtTime(freq, now);
            gain.gain.setValueAtTime(0.06, now);
            gain.gain.exponentialRampToValueAtTime(0.005, now + 0.15);

            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start(now);
            osc.stop(now + 0.15);

            const bassOsc = audioCtx.createOscillator();
            const bassGain = audioCtx.createGain();
            bassOsc.type = 'triangle';
            bassOsc.frequency.setValueAtTime(freq / 2, now);
            bassGain.gain.setValueAtTime(0.09, now);
            bassGain.gain.exponentialRampToValueAtTime(0.005, now + 0.15);

            bassOsc.connect(bassGain);
            bassGain.connect(audioCtx.destination);
            bassOsc.start(now);
            bassOsc.stop(now + 0.15);

        }, 180);
    }

    function stopBGM() {
        if (bgmInterval) {
            clearInterval(bgmInterval);
            bgmInterval = null;
        }
    }

    // -------------------------------------------------------------
    // 2. CANVAS & ENGINE SETUP
    // -------------------------------------------------------------
    const canvas = document.getElementById('fightCanvas');
    const ctx = canvas.getContext('2d');
    
    const CANVAS_WIDTH = 800;
    const CANVAS_HEIGHT = 450;
    const GROUND_Y = 395;

    const selectScreen = document.getElementById('selectScreen');
    const gameOverScreen = document.getElementById('gameOverScreen');
    const selectSposoBtn = document.getElementById('selectSposoBtn');
    const selectSposaBtn = document.getElementById('selectSposaBtn');
    const startMatchBtn = document.getElementById('startMatchBtn');
    const winnerTitle = document.getElementById('winnerTitle');
    const winnerQuote = document.getElementById('winnerQuote');
    const posterBadge = document.getElementById('posterBadge');
    const rematchBtn = document.getElementById('rematchBtn');
    const changeCharBtn = document.getElementById('changeCharBtn');

    const btnLeft = document.getElementById('btnLeft');
    const btnRight = document.getElementById('btnRight');
    const btnAttack = document.getElementById('btnAttack');

    // -------------------------------------------------------------
    // 3. GREEN-SCREEN CHROMA KEYER
    // -------------------------------------------------------------
    const assets = {
        bg: new Image(),
        sposoIdleRaw: new Image(),
        sposoAttackRaw: new Image(),
        sposaIdleRaw: new Image(),
        sposaAttackRaw: new Image(),
        sposoIdle: null,
        sposoAttack: null,
        sposaIdle: null,
        sposaAttack: null,
        loaded: false
    };

    assets.bg.src = "{{ asset('images/game/stage-bg.jpg') }}";
    assets.sposoIdleRaw.src = "{{ asset('images/game/sposo-idle.jpg') }}";
    assets.sposoAttackRaw.src = "{{ asset('images/game/sposo-attack.jpg') }}";
    assets.sposaIdleRaw.src = "{{ asset('images/game/sposa-idle.jpg') }}";
    assets.sposaAttackRaw.src = "{{ asset('images/game/sposa-attack.jpg') }}";

    function processGreenScreenChromaKey(img) {
        const offscreen = document.createElement('canvas');
        const w = img.naturalWidth || img.width || 600;
        const h = img.naturalHeight || img.height || 800;
        offscreen.width = w;
        offscreen.height = h;
        
        const octx = offscreen.getContext('2d');
        octx.drawImage(img, 0, 0);
        
        const imgData = octx.getImageData(0, 0, w, h);
        const data = imgData.data;
        
        for (let i = 0; i < data.length; i += 4) {
            const r = data[i];
            const g = data[i + 1];
            const b = data[i + 2];
            
            if (g > 110 && g > r * 1.25 && g > b * 1.25) {
                data[i + 3] = 0;
            } else {
                data[i + 3] = 255;
            }
        }
        
        octx.putImageData(imgData, 0, 0);
        return offscreen;
    }

    let loadedCount = 0;
    function onAssetLoad() {
        loadedCount++;
        if (loadedCount >= 5) {
            assets.sposoIdle = processGreenScreenChromaKey(assets.sposoIdleRaw);
            assets.sposoAttack = processGreenScreenChromaKey(assets.sposoAttackRaw);
            assets.sposaIdle = processGreenScreenChromaKey(assets.sposaIdleRaw);
            assets.sposaAttack = processGreenScreenChromaKey(assets.sposaAttackRaw);
            assets.loaded = true;
        }
    }

    assets.bg.onload = onAssetLoad;
    assets.sposoIdleRaw.onload = onAssetLoad;
    assets.sposoAttackRaw.onload = onAssetLoad;
    assets.sposaIdleRaw.onload = onAssetLoad;
    assets.sposaAttackRaw.onload = onAssetLoad;

    // -------------------------------------------------------------
    // 4. GAME STATE & PARTICLES
    // -------------------------------------------------------------
    let gameState = 'SELECT';
    let selectedPlayerRole = null;
    
    const keys = { left: false, right: false, attack: false };
    let particles = [];
    let bats = [];
    let screenShakeTime = 0;

    for (let i = 0; i < 7; i++) {
        bats.push({
            x: Math.random() * CANVAS_WIDTH,
            y: 40 + Math.random() * 100,
            speed: 0.9 + Math.random() * 1.1,
            size: 6 + Math.random() * 8,
            wingPhase: Math.random() * Math.PI * 2
        });
    }

    // SPECIAL VISUAL FX: Edderasmo Poison Fart Gas Cloud
    function createFartGasCloud(x, y, facing) {
        for (let i = 0; i < 16; i++) {
            particles.push({
                type: 'fartGas',
                x: x + (facing * 20),
                y: y + (Math.random() - 0.5) * 40,
                vx: facing * (3 + Math.random() * 5),
                vy: (Math.random() - 0.5) * 3,
                size: 8 + Math.random() * 14,
                color: Math.random() > 0.4 ? '#22C55E' : '#A3E635',
                life: 25 + Math.random() * 15
            });
        }
    }

    // SPECIAL VISUAL FX: Yoshimonica Munch's Scream Soundwaves
    function createScreamSoundwaves(x, y, facing) {
        for (let i = 0; i < 3; i++) {
            particles.push({
                type: 'munchScreamWave',
                x: x + (facing * 30),
                y: y,
                facing: facing,
                radius: 15 + i * 18,
                life: 22 + i * 4
            });
        }
    }

    // -------------------------------------------------------------
    // 5. ANIMATED FIGHTER CLASS
    // -------------------------------------------------------------
    class AnimatedFighter {
        constructor({ role, isAI, startX, facing }) {
            this.role = role;
            this.isAI = isAI;
            this.x = startX;
            this.y = GROUND_Y;
            this.width = 130;
            this.height = 220;
            this.facing = facing;
            this.hp = 100;
            this.maxHp = 100;
            this.speed = 4.6;
            
            this.state = 'idle';
            this.animTimer = 0;
            this.attackCooldown = 0;
            this.isAttacking = false;
            this.attackFrame = 0;
            this.hitCooldown = 0;

            this.aiDecisionTimer = 0;
            this.aiAction = 'idle';
        }

        update(opponent) {
            this.animTimer += 0.16;

            if (this.attackCooldown > 0) this.attackCooldown--;
            if (this.hitCooldown > 0) this.hitCooldown--;

            if (this.hp <= 0) {
                this.state = 'ko';
                return;
            }

            if (this.hitCooldown > 12) {
                this.state = 'hit';
                return;
            }

            if (this.isAttacking) {
                this.attackFrame++;
                this.state = 'attack';
                
                if (this.attackFrame === 7) {
                    this.checkHit(opponent);
                }

                if (this.attackFrame > 16) {
                    this.isAttacking = false;
                    this.attackFrame = 0;
                    this.state = 'idle';
                }
                return;
            }

            if (this.isAI) {
                this.updateAI(opponent);
            } else {
                this.updatePlayerControls();
            }

            this.x = Math.max(70, Math.min(CANVAS_WIDTH - 70, this.x));

            if (!this.isAttacking && this.state !== 'hit') {
                this.facing = (this.x <= opponent.x) ? 1 : -1;
            }
        }

        updatePlayerControls() {
            let moving = false;
            if (keys.left) {
                this.x -= this.speed;
                moving = true;
            }
            if (keys.right) {
                this.x += this.speed;
                moving = true;
            }

            if (keys.attack && this.attackCooldown === 0 && !this.isAttacking) {
                this.startAttack();
            } else if (moving) {
                this.state = 'walk';
            } else {
                this.state = 'idle';
            }
        }

        updateAI(opponent) {
            this.aiDecisionTimer--;
            const dist = Math.abs(this.x - opponent.x);

            if (this.aiDecisionTimer <= 0) {
                this.aiDecisionTimer = 16 + Math.floor(Math.random() * 24);
                
                if (dist > 125) {
                    this.aiAction = (this.x < opponent.x) ? 'moveRight' : 'moveLeft';
                } else if (dist < 45) {
                    this.aiAction = (this.x < opponent.x) ? 'moveLeft' : 'moveRight';
                } else {
                    const rnd = Math.random();
                    if (rnd < 0.6) this.aiAction = 'attack';
                    else if (rnd < 0.8) this.aiAction = (this.x < opponent.x) ? 'moveRight' : 'moveLeft';
                    else this.aiAction = 'idle';
                }
            }

            if (this.aiAction === 'moveLeft') {
                this.x -= this.speed * 0.85;
                this.state = 'walk';
            } else if (this.aiAction === 'moveRight') {
                this.x += this.speed * 0.85;
                this.state = 'walk';
            } else if (this.aiAction === 'attack' && dist <= 130 && this.attackCooldown === 0) {
                this.startAttack();
                this.aiAction = 'idle';
            } else {
                this.state = 'idle';
            }
        }

        startAttack() {
            this.isAttacking = true;
            this.attackFrame = 0;
            this.attackCooldown = 30;

            // Trigger Special Lethal Weapons Visual & Audio FX!
            if (this.role === 'sposo') {
                playFartSFX();
                createFartGasCloud(this.x, this.y - 70, this.facing);
            } else {
                playMunchScreamSFX();
                createScreamSoundwaves(this.x, this.y - 130, this.facing);
            }
        }

        checkHit(opponent) {
            const attackReach = 135;
            const dist = Math.abs(this.x - opponent.x);
            const isFacingOpponent = (this.facing === 1 && opponent.x >= this.x) || (this.facing === -1 && opponent.x <= this.x);

            if (dist <= attackReach && isFacingOpponent && opponent.hp > 0) {
                const damage = 15 + Math.floor(Math.random() * 6);
                opponent.hp = Math.max(0, opponent.hp - damage);
                opponent.hitCooldown = 22;
                opponent.state = 'hit';

                opponent.x += this.facing * 24;

                screenShakeTime = 14;
                createBloodParticles(opponent.x, opponent.y - 110, 18);
                
                // Nuvoletta con scritta specifica all'impatto del colpo:
                // Sposa -> "grido di Munch"
                // Sposo -> "peto assassino"
                const cloudText = (this.role === 'sposo') ? "peto assassino" : "grido di Munch";
                const bubbleX = this.x + (this.facing * 20);
                const bubbleY = this.y - 250;
                createSpeechBubble(bubbleX, bubbleY, cloudText, this.role);

                // Danno HP fluttuante sopra l'avversario
                createDamageText(opponent.x, opponent.y - 140, `-${damage} HP`);
                
                if (this.role === 'sposa') {
                    playMunchScreamSFX();
                } else {
                    playHitSFX();
                }
            }
        }

        draw(ctx) {
            let spriteCanvas = null;
            if (this.role === 'sposo') {
                spriteCanvas = (this.state === 'attack') ? assets.sposoAttack : assets.sposoIdle;
            } else {
                spriteCanvas = (this.state === 'attack') ? assets.sposaAttack : assets.sposaIdle;
            }

            if (!spriteCanvas) return;

            ctx.save();
            ctx.translate(this.x, this.y);

            // Ground Shadow
            ctx.fillStyle = 'rgba(0, 0, 0, 0.65)';
            ctx.beginPath();
            ctx.ellipse(0, -3, 55, 14, 0, 0, Math.PI * 2);
            ctx.fill();

            const isHit = (this.state === 'hit');
            const isKO = (this.state === 'ko');

            if (this.facing === -1) {
                ctx.scale(-1, 1);
            }

            if (isKO) {
                ctx.rotate(this.facing * -Math.PI / 2.2);
                ctx.translate(0, 50);
            }

            let bounceY = 0;
            let thrustX = 0;
            let bodyRotate = 0;

            if (this.state === 'walk') {
                bounceY = -Math.abs(Math.sin(this.animTimer * 3)) * 12;
                bodyRotate = Math.sin(this.animTimer * 3) * 0.08 * (this.facing);
            } else if (this.state === 'idle') {
                bounceY = Math.sin(this.animTimer * 1.5) * 4;
            } else if (this.state === 'attack') {
                const attackProgress = Math.sin((this.attackFrame / 16) * Math.PI);
                thrustX = attackProgress * 48 * (this.facing);
                bounceY = -Math.sin(attackProgress * Math.PI) * 14;
            } else if (isHit) {
                thrustX = -18 * (this.facing);
                bodyRotate = -0.12 * (this.facing);
            }

            ctx.rotate(bodyRotate);

            if (isHit) {
                ctx.filter = 'drop-shadow(0px 0px 20px rgba(239, 68, 68, 1)) brightness(1.4) sepia(1) hue-rotate(-50deg) saturate(6)';
            } else {
                ctx.filter = 'drop-shadow(0px 0px 8px rgba(255, 230, 180, 0.4)) brightness(1.18) contrast(1.05)';
            }

            const drawW = 165;
            const drawH = 245;
            ctx.drawImage(spriteCanvas, -drawW / 2 + thrustX, -drawH + bounceY, drawW, drawH);

            ctx.filter = 'none';

            // Special Attack Visual Effects Overlay
            if (this.state === 'attack' && this.attackFrame > 3 && this.attackFrame < 13) {
                const fxX = (this.facing) * 60 + thrustX;
                const fxY = -drawH * 0.6 + bounceY;

                if (this.role === 'sposo') {
                    // EDDERASMO PETO ASSASSINO: Green Toxic Gas Cloud Arc
                    ctx.fillStyle = 'rgba(34, 197, 94, 0.7)';
                    ctx.beginPath();
                    ctx.arc(fxX, fxY + 20, 45, 0, Math.PI * 2);
                    ctx.fill();

                    ctx.strokeStyle = '#A3E635';
                    ctx.lineWidth = 5;
                    ctx.stroke();
                } else {
                    // YOSHIMONICA GRIDO DI MUNCH: Crimson Munch Scream Waves
                    ctx.strokeStyle = '#E63946';
                    ctx.lineWidth = 6;
                    ctx.beginPath();
                    ctx.arc(fxX, fxY - 10, 55, -Math.PI / 2.5, Math.PI / 2.5);
                    ctx.stroke();

                    ctx.strokeStyle = '#F59E0B';
                    ctx.lineWidth = 4;
                    ctx.beginPath();
                    ctx.arc(fxX + 15, fxY - 10, 40, -Math.PI / 2.5, Math.PI / 2.5);
                    ctx.stroke();
                }
            }

            ctx.restore();
        }
    }

    // -------------------------------------------------------------
    // 6. PARTICLES & DAMAGE POPUPS
    // -------------------------------------------------------------
    // SPECIAL VISUAL FX: Speech / Cloud Bubble ("Nuvoletta") on Hit
    function createSpeechBubble(x, y, text, role) {
        particles.push({
            type: 'speechBubble',
            text: text,
            role: role,
            x: x,
            y: y,
            vx: 0,
            vy: -0.95,
            life: 55,
            maxLife: 55
        });
    }

    function createBloodParticles(x, y, count) {
        for (let i = 0; i < count; i++) {
            particles.push({
                x: x,
                y: y,
                vx: (Math.random() - 0.5) * 10,
                vy: (Math.random() - 0.75) * 8,
                size: 2.5 + Math.random() * 4.5,
                color: Math.random() > 0.3 ? '#8B0000' : '#E63946',
                life: 28 + Math.random() * 14
            });
        }
    }

    function createDamageText(x, y, text) {
        particles.push({
            type: 'text',
            text: text,
            x: x,
            y: y,
            vx: (Math.random() - 0.5) * 2,
            vy: -2.2,
            life: 42
        });
    }

    function updateAndDrawParticles() {
        for (let i = particles.length - 1; i >= 0; i--) {
            const p = particles[i];
            p.life--;

            if (p.type === 'speechBubble') {
                p.x += (p.vx || 0);
                p.y += p.vy;

                const progress = (p.maxLife - p.life) / p.maxLife; // 0.0 -> 1.0
                
                // Elastic spring pop-in scale animation
                let scale = 1;
                if (progress < 0.15) {
                    scale = (progress / 0.15) * 1.1;
                } else if (progress < 0.25) {
                    scale = 1.1 - ((progress - 0.15) / 0.10) * 0.1;
                } else if (progress > 0.8) {
                    scale = 1 - ((progress - 0.8) / 0.2);
                }

                // Smooth fade-out alpha
                let alpha = 0.9;
                if (progress > 0.7) {
                    alpha = (1 - progress) / 0.3 * 0.9;
                }

                ctx.save();
                ctx.globalAlpha = Math.max(0, Math.min(0.9, alpha));
                ctx.translate(p.x, p.y);
                ctx.scale(scale, scale);

                const isSposo = (p.role === 'sposo');
                const bubbleWidth = isSposo ? 145 : 155;
                const bubbleHeight = 38;
                const hw = bubbleWidth / 2;
                const hh = bubbleHeight / 2;

                // Glowing drop shadow for cloud bubble
                ctx.shadowColor = isSposo ? 'rgba(34, 197, 94, 0.4)' : 'rgba(239, 68, 68, 0.4)';
                ctx.shadowBlur = 8;
                ctx.shadowOffsetY = 2;

                // Translucent cream-white cloud body fill (82% opacity for crystal clear visibility behind)
                ctx.fillStyle = 'rgba(255, 255, 255, 0.82)';
                ctx.strokeStyle = isSposo ? '#16A34A' : '#DC2626';
                ctx.lineWidth = 2.8;

                // Render fluffy comic cloud / speech bubble shape ("nuvoletta")
                ctx.beginPath();
                
                // Top lobes
                ctx.arc(-hw + 18, -hh, 16, Math.PI * 0.7, Math.PI * 1.8);
                ctx.arc(0, -hh - 4, 20, Math.PI * 0.95, Math.PI * 1.95);
                ctx.arc(hw - 18, -hh, 16, Math.PI * 1.1, Math.PI * 0.2);
                
                // Right side lobe
                ctx.arc(hw + 4, 0, 16, Math.PI * 1.6, Math.PI * 0.4);
                
                // Bottom right lobe
                ctx.arc(hw - 18, hh, 16, Math.PI * 0.1, Math.PI * 0.9);
                
                // Nuvoletta pointer tail pointing downwards
                ctx.lineTo(8, hh + 4);
                ctx.lineTo(0, hh + 14);
                ctx.lineTo(-10, hh + 5);
                
                // Bottom left lobe
                ctx.arc(-hw + 18, hh, 16, Math.PI * 0.2, Math.PI * 1.1);
                
                // Left side lobe
                ctx.arc(-hw - 4, 0, 16, Math.PI * 0.6, Math.PI * 1.4);

                ctx.closePath();
                ctx.fill();
                ctx.stroke();

                // Clear shadow for crisp inner text
                ctx.shadowColor = 'transparent';

                // Nuvoletta text styling
                ctx.font = 'bold 14px "Comic Sans MS", "Montserrat", sans-serif';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';

                // Text color matching character theme
                ctx.fillStyle = isSposo ? '#15803D' : '#B91C1C';
                ctx.fillText(p.text, 0, 1);

                ctx.restore();
            } else if (p.type === 'text') {
                p.x += p.vx;
                p.y += p.vy;

                ctx.font = 'bold 20px Cormorant Garamond, serif';
                ctx.fillStyle = `rgba(239, 68, 68, ${p.life / 42})`;
                ctx.fillText(p.text, p.x, p.y);
            } else if (p.type === 'fartGas') {
                p.x += p.vx;
                p.y += p.vy;
                p.size += 0.4;

                ctx.fillStyle = `rgba(34, 197, 94, ${p.life / 40})`;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fill();
            } else if (p.type === 'munchScreamWave') {
                p.radius += 3.5;
                ctx.strokeStyle = `rgba(230, 57, 70, ${p.life / 30})`;
                ctx.lineWidth = 4;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, -Math.PI / 3, Math.PI / 3);
                ctx.stroke();
            } else {
                p.x += p.vx;
                p.y += p.vy;
                p.vy += 0.35;

                ctx.fillStyle = p.color;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fill();
            }

            if (p.life <= 0) {
                particles.splice(i, 1);
            }
        }
    }

    // -------------------------------------------------------------
    // 7. STAGE & HUD RENDERER
    // -------------------------------------------------------------
    function drawStage() {
        if (assets.bg.complete) {
            ctx.drawImage(assets.bg, 0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
        } else {
            ctx.fillStyle = '#18080C';
            ctx.fillRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
        }

        ctx.fillStyle = '#050204';
        bats.forEach(b => {
            b.x += b.speed;
            if (b.x > CANVAS_WIDTH + 20) b.x = -20;
            b.wingPhase += 0.22;
            const wingY = Math.sin(b.wingPhase) * (b.size * 0.5);

            ctx.beginPath();
            ctx.moveTo(b.x, b.y);
            ctx.quadraticCurveTo(b.x - b.size, b.y - wingY, b.x - b.size * 1.2, b.y + wingY * 0.5);
            ctx.quadraticCurveTo(b.x, b.y, b.x + b.size * 1.2, b.y + wingY * 0.5);
            ctx.quadraticCurveTo(b.x + b.size, b.y - wingY, b.x, b.y);
            ctx.fill();
        });

        const flick = Math.sin(Date.now() * 0.01) * 4;
        ctx.fillStyle = 'rgba(255, 200, 50, 0.12)';
        
        ctx.beginPath();
        ctx.arc(60, 200, 45 + flick, 0, Math.PI * 2);
        ctx.fill();

        ctx.beginPath();
        ctx.arc(740, 200, 45 + flick, 0, Math.PI * 2);
        ctx.fill();
    }

    function drawHUD(p1, p2) {
        const barWidth = 270;
        const barHeight = 24;

        drawHealthBar(30, 25, barWidth, barHeight, p1, false);
        drawHealthBar(CANVAS_WIDTH - 30 - barWidth, 25, barWidth, barHeight, p2, true);

        const vsX = CANVAS_WIDTH / 2;
        const vsY = 37;

        ctx.fillStyle = '#0F0C1B';
        ctx.beginPath();
        ctx.arc(vsX, vsY, 26, 0, Math.PI * 2);
        ctx.fill();

        ctx.strokeStyle = '#D1B280';
        ctx.lineWidth = 2;
        ctx.stroke();

        ctx.fillStyle = '#EF4444';
        ctx.font = 'extrabold 18px Cormorant Garamond, serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('VS', vsX, vsY);
    }

    function drawHealthBar(x, y, width, height, player, isRight) {
        ctx.fillStyle = 'rgba(10, 8, 18, 0.9)';
        ctx.strokeStyle = '#D1B280';
        ctx.lineWidth = 1.5;
        ctx.fillRect(x - 5, y - 18, width + 10, height + 24);
        ctx.strokeRect(x - 5, y - 18, width + 10, height + 24);

        ctx.fillStyle = '#FFFFFF';
        ctx.font = 'bold 12px Montserrat, sans-serif';
        ctx.textAlign = isRight ? 'right' : 'left';
        ctx.textBaseline = 'bottom';
        const nameStr = (player.role === 'sposo') ? 'EDDERASMO 💨' : 'YOSHIMONICA 😱';
        const labelX = isRight ? x + width : x;
        ctx.fillText(nameStr, labelX, y - 2);

        ctx.fillStyle = '#27272A';
        ctx.fillRect(x, y, width, height);

        const currentWidth = (player.hp / player.maxHp) * width;
        const hpGrad = ctx.createLinearGradient(x, y, x + width, y);
        hpGrad.addColorStop(0, '#EF4444');
        hpGrad.addColorStop(1, '#990000');

        if (isRight) {
            ctx.fillStyle = hpGrad;
            ctx.fillRect(x + (width - currentWidth), y, currentWidth, height);
        } else {
            ctx.fillStyle = hpGrad;
            ctx.fillRect(x, y, currentWidth, height);
        }

        ctx.strokeStyle = 'rgba(255,255,255,0.3)';
        ctx.lineWidth = 1;
        ctx.strokeRect(x, y, width, height);

        ctx.fillStyle = '#FFFFFF';
        ctx.font = '10px Montserrat, sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(`${player.hp} / ${player.maxHp}`, x + width / 2, y + height / 2);
    }

    // -------------------------------------------------------------
    // 8. MATCH CONTROL & EDDERASMO vs YOSHIMONICA QUOTES
    // -------------------------------------------------------------
    let player1 = null;
    let player2 = null;

    function initMatch() {
        if (!selectedPlayerRole || !assets.loaded) return;

        const p1Role = selectedPlayerRole;
        const p2Role = (p1Role === 'sposo') ? 'sposa' : 'sposo';

        player1 = new AnimatedFighter({
            role: p1Role,
            isAI: false,
            startX: 240,
            facing: 1
        });

        player2 = new AnimatedFighter({
            role: p2Role,
            isAI: true,
            startX: 560,
            facing: -1
        });

        gameState = 'FIGHT';
        selectScreen.classList.add('hidden');
        gameOverScreen.classList.add('hidden');

        startBGM();
    }

    const edderasmoQuotes = [
        `"VITTORIA DI EDDERASMO! 💨 Il Peto Assassino ha steso Yoshimonica! Stasera Pallone di Gravina e vino Primitivo!"`,
        `"VITTORIA DI EDDERASMO! 🌉 Il Peto Assassino ha fatto tremare il Ponte Viadotto! Vincitore indiscusso!"`,
        `"VITTORIA DI EDDERASMO! 🧀 Hai perso Yoshimonica! Ora vai al forno a legna a Gravina a prendere la focaccia calda!"`,
        `"VITTORIA DI EDDERASMO! 🚗 Il viaggio di nozze con Yoshimonica si fa sulle curve panoramiche della Murgia!"`
    ];

    const yoshimonicaQuotes = [
        `"VITTORIA DI YOSHIMONICA! 😱 Il Grido di Munch ha spazzato via Edderasmo e il suo peto! Domenica il sugo lo fa mia madre!"`,
        `"VITTORIA DI YOSHIMONICA! 🏰 Il Grido di Munch ha zittito Edderasmo! Domenica tutti a pranzo dalla suocera a Gravina!"`,
        `"VITTORIA DI YOSHIMONICA! 🌾 Sulle Murge si fa come dico io! Chi perde pulisce le orecchiette fatte in casa!"`,
        `"VITTORIA DI YOSHIMONICA! 🛍️ Ho vinto io Edderasmo! Domani shopping a Gravina e poi aperitivo in centro!"`
    ];

    const gravinaBadges = [
        `★ Cronache di Gravina in Puglia: "Scontro epico tra il Peto Assassino di Edderasmo e il Grido di Munch di Yoshimonica!" ★`,
        `★ Gazzetta della Murgia: "Vittoria schiacciante approvata con lode da tutta Gravina!" ★`,
        `★ Gravina Life: "Una grande sfida d'amore e superpoteri nel cuore della Puglia!" ★`
    ];

    function triggerGameOver() {
        gameState = 'GAMEOVER';
        
        const winner = (player1.hp > 0) ? player1 : player2;
        const isEdderasmoWinner = (winner.role === 'sposo');
        const winnerName = isEdderasmoWinner ? 'EDDERASMO' : 'YOSHIMONICA';

        winnerTitle.textContent = `VITTORIA DI ${winnerName}!`;

        const quotesList = isEdderasmoWinner ? edderasmoQuotes : yoshimonicaQuotes;
        winnerQuote.textContent = quotesList[Math.floor(Math.random() * quotesList.length)];
        posterBadge.textContent = gravinaBadges[Math.floor(Math.random() * gravinaBadges.length)];

        gameOverScreen.classList.remove('hidden');

        playVictoryFanfare();
    }

    function gameLoop() {
        ctx.save();

        if (screenShakeTime > 0) {
            screenShakeTime--;
            const shakeX = (Math.random() - 0.5) * 10;
            const shakeY = (Math.random() - 0.5) * 10;
            ctx.translate(shakeX, shakeY);
        }

        drawStage();

        if (gameState === 'FIGHT' || gameState === 'GAMEOVER') {
            if (gameState === 'FIGHT') {
                player1.update(player2);
                player2.update(player1);

                if (player1.hp <= 0 || player2.hp <= 0) {
                    setTimeout(triggerGameOver, 600);
                }
            }

            player1.draw(ctx);
            player2.draw(ctx);

            updateAndDrawParticles();
            drawHUD(player1, player2);
        }

        ctx.restore();
        requestAnimationFrame(gameLoop);
    }

    // -------------------------------------------------------------
    // 9. INPUT & TOUCH HANDLERS
    // -------------------------------------------------------------
    window.addEventListener('keydown', (e) => {
        if (gameState !== 'FIGHT') return;

        if (e.key === 'ArrowLeft' || e.key === 'a' || e.key === 'A') {
            keys.left = true;
        }
        if (e.key === 'ArrowRight' || e.key === 'd' || e.key === 'D') {
            keys.right = true;
        }
        if (e.key === ' ' || e.key === 'f' || e.key === 'F') {
            keys.attack = true;
            e.preventDefault();
        }
    });

    window.addEventListener('keyup', (e) => {
        if (e.key === 'ArrowLeft' || e.key === 'a' || e.key === 'A') {
            keys.left = false;
        }
        if (e.key === 'ArrowRight' || e.key === 'd' || e.key === 'D') {
            keys.right = false;
        }
        if (e.key === ' ' || e.key === 'f' || e.key === 'F') {
            keys.attack = false;
        }
    });

    function setupTouchButton(btn, keyProp) {
        if (!btn) return;
        const start = (e) => {
            if (e.cancelable) e.preventDefault();
            keys[keyProp] = true;
            initAudio();
        };
        const end = (e) => {
            if (e.cancelable) e.preventDefault();
            keys[keyProp] = false;
        };

        btn.addEventListener('touchstart', start, { passive: false });
        btn.addEventListener('touchend', end, { passive: false });
        btn.addEventListener('touchcancel', end, { passive: false });
        btn.addEventListener('mousedown', start);
        btn.addEventListener('mouseup', end);
        btn.addEventListener('mouseleave', end);
    }

    setupTouchButton(btnLeft, 'left');
    setupTouchButton(btnRight, 'right');
    setupTouchButton(btnAttack, 'attack');

    // -------------------------------------------------------------
    // 10. FULLSCREEN & ORIENTATION MANAGEMENT FOR MOBILE & PC
    // -------------------------------------------------------------
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const fullscreenIcon = document.getElementById('fullscreenIcon');
    const gameMainCard = document.getElementById('gameMainCard');
    const portraitNoticeOverlay = document.getElementById('portraitNoticeOverlay');

    function requestGameFullscreen() {
        if (document.fullscreenElement || document.webkitFullscreenElement) return;

        const isMobileOrTablet = (window.innerWidth <= 1024) || ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);
        const target = isMobileOrTablet ? document.documentElement : (gameMainCard || document.documentElement);

        const req = target.requestFullscreen || 
                    target.webkitRequestFullscreen || 
                    target.mozRequestFullScreen || 
                    target.msRequestFullscreen ||
                    (gameMainCard && (gameMainCard.requestFullscreen || gameMainCard.webkitRequestFullscreen));

        if (req) {
            try {
                const res = req.call(target);
                if (res && res.then) {
                    res.then(() => {
                        if (window.screen && window.screen.orientation && window.screen.orientation.lock) {
                            window.screen.orientation.lock('landscape').catch(() => {});
                        }
                    }).catch(() => {
                        if (target !== gameMainCard && gameMainCard) {
                            if (gameMainCard.requestFullscreen) gameMainCard.requestFullscreen();
                            else if (gameMainCard.webkitRequestFullscreen) gameMainCard.webkitRequestFullscreen();
                        }
                    });
                }
            } catch (e) {
                console.log('Fullscreen error:', e);
            }
        }
    }

    function exitGameFullscreen() {
        const exitFS = document.exitFullscreen || document.webkitExitFullscreen || document.mozCancelFullScreen || document.msExitFullscreen;
        if (exitFS && (document.fullscreenElement || document.webkitFullscreenElement)) {
            exitFS.call(document).catch(() => {});
        }
    }

    function toggleFullscreen() {
        if (!document.fullscreenElement && !document.webkitFullscreenElement) {
            requestGameFullscreen();
        } else {
            exitGameFullscreen();
        }
    }

    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleFullscreen();
        });

        const updateFullscreenUI = () => {
            const isFS = !!(document.fullscreenElement || document.webkitFullscreenElement);
            if (fullscreenIcon) {
                fullscreenIcon.textContent = isFS ? '🗗' : '⛶';
            }
        };

        document.addEventListener('fullscreenchange', updateFullscreenUI);
        document.addEventListener('webkitfullscreenchange', updateFullscreenUI);
    }

    // Auto-request fullscreen on mobile user touch/interaction if in landscape
    const autoFullscreenOnMobileTouch = () => {
        const isMobileOrTablet = (window.innerWidth <= 1024) || ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);
        const isLandscape = window.innerWidth > window.innerHeight;
        if (isMobileOrTablet && isLandscape && !document.fullscreenElement && !document.webkitFullscreenElement) {
            requestGameFullscreen();
        }
    };

    if (gameMainCard) {
        gameMainCard.addEventListener('touchstart', autoFullscreenOnMobileTouch, { passive: true });
    }

    function checkDeviceOrientation() {
        if (!portraitNoticeOverlay) return;
        const isMobileOrTablet = (window.innerWidth <= 1024) || ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);
        const isPortrait = window.innerHeight > window.innerWidth;

        if (isMobileOrTablet && isPortrait) {
            portraitNoticeOverlay.classList.remove('hidden');
            portraitNoticeOverlay.classList.add('flex');
        } else {
            portraitNoticeOverlay.classList.add('hidden');
            portraitNoticeOverlay.classList.remove('flex');
        }
    }

    window.addEventListener('resize', checkDeviceOrientation);
    window.addEventListener('orientationchange', checkDeviceOrientation);
    checkDeviceOrientation();

    selectSposoBtn.addEventListener('click', () => {
        selectedPlayerRole = 'sposo';
        selectSposoBtn.classList.add('border-red-500', 'bg-red-950/90', 'ring-2', 'ring-red-500');
        selectSposaBtn.classList.remove('border-red-500', 'bg-red-950/90', 'ring-2', 'ring-red-500');
        startMatchBtn.disabled = false;
        initAudio();
        requestGameFullscreen();
    });

    selectSposaBtn.addEventListener('click', () => {
        selectedPlayerRole = 'sposa';
        selectSposaBtn.classList.add('border-red-500', 'bg-red-950/90', 'ring-2', 'ring-red-500');
        selectSposoBtn.classList.remove('border-red-500', 'bg-red-950/90', 'ring-2', 'ring-red-500');
        startMatchBtn.disabled = false;
        initAudio();
        requestGameFullscreen();
    });

    startMatchBtn.addEventListener('click', () => {
        initAudio();
        requestGameFullscreen();
        initMatch();
    });

    rematchBtn.addEventListener('click', () => {
        initAudio();
        requestGameFullscreen();
        initMatch();
    });

    changeCharBtn.addEventListener('click', () => {
        gameState = 'SELECT';
        stopBGM();
        gameOverScreen.classList.add('hidden');
        selectScreen.classList.remove('hidden');
    });

    // Start Engine Loop
    requestAnimationFrame(gameLoop);

})();
</script>
