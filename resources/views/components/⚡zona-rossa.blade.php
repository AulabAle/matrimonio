<?php

use Livewire\Component;
use App\Models\ZonaRossaMedia;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

new class extends Component
{
    public $activeFilter = 'all'; // 'all', 'image', 'video'
    public $selectedMedia = null; // For Lightbox viewer modal

    public function mount()
    {
    }

    public function setFilter($filter)
    {
        $this->activeFilter = $filter;
    }

    public function openLightbox($id)
    {
        $this->selectedMedia = ZonaRossaMedia::find($id);
    }

    public function closeLightbox()
    {
        $this->selectedMedia = null;
    }

    #[Computed]
    public function mediaItems()
    {
        $query = ZonaRossaMedia::query()->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');

        if ($this->activeFilter === 'image') {
            $query->where('media_type', 'image');
        } elseif ($this->activeFilter === 'video') {
            $query->where('media_type', 'video');
        }

        return $query->get();
    }

    #[Computed]
    public function counts()
    {
        return [
            'all' => ZonaRossaMedia::count(),
            'image' => ZonaRossaMedia::where('media_type', 'image')->count(),
            'video' => ZonaRossaMedia::where('media_type', 'video')->count(),
        ];
    }
};
?>

<div class="w-full min-h-screen py-6 px-3 sm:px-6 max-w-7xl mx-auto space-y-8 select-none">
    
    <!-- Hero Header Banner (Seamless & Immersed) -->
    <div class="relative py-8 sm:py-12 text-center">
        <!-- Decorative subtle background aura -->
        <div class="absolute inset-0 opacity-15 pointer-events-none bg-[radial-gradient(#8C6239_1px,transparent_1px)] [background-size:24px_24px]"></div>
        
        <div class="relative z-10 flex flex-col items-center space-y-4">

            <!-- Title in Bordeaux Profondo (#58181A) -->
            <h1 class="font-script text-5xl sm:text-7xl lg:text-8xl text-[#58181A] tracking-wide drop-shadow-sm">
                Zona Rossa
            </h1>
            
            <!-- Delicate Divider with Rosa Antico / Marsala (#A0525A) & Oro Antico (#8C6239) details -->
            <div class="flex items-center justify-center gap-3 w-full max-w-md my-2">
                <div class="h-px bg-gradient-to-r from-transparent via-[#8C6239]/40 to-transparent flex-1"></div>
                <span class="text-[#A0525A] text-lg">🌹</span>
                <span class="text-[#8C6239] text-sm">✦</span>
                <span class="text-[#A0525A] text-lg">🌹</span>
                <div class="h-px bg-gradient-to-r from-transparent via-[#8C6239]/40 to-transparent flex-1"></div>
            </div>

            <!-- Subtitle quote -->
            <p class="font-serif text-sm sm:text-base text-zinc-600 max-w-2xl leading-relaxed italic">
                "Galleria fotografica e multimediale esclusiva che raccoglie gli scatti di Monica & Erasmo in tutto il loro splendore."
            </p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="flex items-center justify-center gap-1.5 sm:gap-4 border-b border-[#8C6239]/20 pb-4">
        <button wire:click="setFilter('all')" 
                class="px-2.5 sm:px-4 py-2 rounded-full font-serif text-[10px] sm:text-xs uppercase tracking-wider font-semibold transition-all duration-200 cursor-pointer flex items-center justify-center gap-1.5 whitespace-nowrap {{ $activeFilter === 'all' ? 'bg-[#58181A] text-white shadow-md ring-2 ring-[#58181A]/30 scale-105 hover:bg-[#4A121A]' : 'bg-white text-charcoal hover:bg-[#FAF6F0] hover:text-[#58181A] border border-[#8C6239]/30 hover:border-[#8C6239]/60' }}">
            <span>Tutti <span class="hidden xs:inline sm:inline">i Media</span></span>
            <span class="text-[9px] sm:text-[10px] px-1.5 sm:px-2 py-0.5 rounded-full {{ $activeFilter === 'all' ? 'bg-[#4A121A] text-[#C08081]' : 'bg-zinc-100 text-zinc-600' }}">{{ $this->counts['all'] }}</span>
        </button>

        <button wire:click="setFilter('image')" 
                class="px-2.5 sm:px-4 py-2 rounded-full font-serif text-[10px] sm:text-xs uppercase tracking-wider font-semibold transition-all duration-200 cursor-pointer flex items-center justify-center gap-1.5 whitespace-nowrap {{ $activeFilter === 'image' ? 'bg-[#58181A] text-white shadow-md ring-2 ring-[#58181A]/30 scale-105 hover:bg-[#4A121A]' : 'bg-white text-charcoal hover:bg-[#FAF6F0] hover:text-[#58181A] border border-[#8C6239]/30 hover:border-[#8C6239]/60' }}">
            <span>📸 Foto</span>
            <span class="text-[9px] sm:text-[10px] px-1.5 sm:px-2 py-0.5 rounded-full {{ $activeFilter === 'image' ? 'bg-[#4A121A] text-[#C08081]' : 'bg-zinc-100 text-zinc-600' }}">{{ $this->counts['image'] }}</span>
        </button>

        <button wire:click="setFilter('video')" 
                class="px-2.5 sm:px-4 py-2 rounded-full font-serif text-[10px] sm:text-xs uppercase tracking-wider font-semibold transition-all duration-200 cursor-pointer flex items-center justify-center gap-1.5 whitespace-nowrap {{ $activeFilter === 'video' ? 'bg-[#58181A] text-white shadow-md ring-2 ring-[#58181A]/30 scale-105 hover:bg-[#4A121A]' : 'bg-white text-charcoal hover:bg-[#FAF6F0] hover:text-[#58181A] border border-[#8C6239]/30 hover:border-[#8C6239]/60' }}">
            <span>🎬 Video</span>
            <span class="text-[9px] sm:text-[10px] px-1.5 sm:px-2 py-0.5 rounded-full {{ $activeFilter === 'video' ? 'bg-[#4A121A] text-[#C08081]' : 'bg-zinc-100 text-zinc-600' }}">{{ $this->counts['video'] }}</span>
        </button>
    </div>

    <!-- Media Gallery Grid -->
    @if($this->mediaItems->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($this->mediaItems as $item)
                <div wire:key="media-{{ $item->id }}" 
                     wire:click="openLightbox({{ $item->id }})"
                     class="group relative bg-white border border-[#8C6239]/25 hover:border-[#8C6239]/50 rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 transform hover:-translate-y-1 cursor-pointer flex flex-col">
                    
                    <!-- Media Display Wrapper -->
                    <div class="relative w-full aspect-[4/3] bg-zinc-900 overflow-hidden">
                        @if($item->isVideo())
                            <video src="{{ $item->media_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" preload="metadata" muted playsinline></video>
                            <div class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                                <div class="w-14 h-14 rounded-full bg-[#58181A]/90 hover:bg-[#4A121A] text-white flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform border border-[#A0525A]/40">
                                    <svg class="w-7 h-7 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                            <span class="absolute top-3 right-3 bg-[#58181A]/90 text-white text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-full shadow border border-[#A0525A]/40">
                                🎬 Video
                            </span>
                        @else
                            <img src="{{ $item->media_url }}" alt="{{ $item->title ?: 'Foto Sposi' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                <span class="text-xs text-white/90 font-serif italic flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-[#8C6239]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                    Clicca per ingrandire
                                </span>
                            </div>
                            <span class="absolute top-3 right-3 bg-black/60 backdrop-blur-md text-[#8C6239] text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded-full border border-[#8C6239]/30">
                                📸 Foto
                            </span>
                        @endif
                    </div>

                    <!-- Caption & Details -->
                    @if($item->title || $item->caption)
                        <div class="p-5 flex-1 flex flex-col justify-between bg-gradient-to-b from-white to-[#FAF6F0]">
                            @if($item->title)
                                <h3 class="font-serif font-bold text-base text-charcoal group-hover:text-[#58181A] transition-colors">
                                    {{ $item->title }}
                                </h3>
                            @endif
                            @if($item->caption)
                                <p class="font-serif text-xs text-zinc-600 italic mt-1.5 leading-relaxed">
                                    "{{ $item->caption }}"
                                </p>
                            @endif
                            <div class="mt-4 pt-3 border-t border-[#8C6239]/20 flex justify-between items-center text-[10px] text-charcoal-light font-serif">
                                <span>Monica & Erasmo</span>
                                <span>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '' }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-[#8C6239]/30 rounded-xl p-12 text-center shadow-sm max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-full bg-[#8C6239]/10 border border-[#8C6239]/30 text-[#58181A] flex items-center justify-center mx-auto mb-4 text-2xl">
                🌹
            </div>
            <h3 class="font-serif text-lg font-bold text-charcoal mb-2">Nessun file presente</h3>
            <p class="font-serif text-xs text-zinc-500">
                Non ci sono ancora foto o video caricati nella Zona Rossa. I contenuti verranno aggiornati dalla dashboard.
            </p>
        </div>
    @endif

    <!-- Lightbox Modal for Full View -->
    @if($selectedMedia)
        <div class="fixed inset-0 z-[1000] bg-black/95 backdrop-blur-md flex flex-col items-center justify-center p-4 sm:p-8 transition-all duration-300 overflow-y-auto"
             wire:click.self="closeLightbox"
             wire:keydown.escape.window="closeLightbox">
            
            <!-- Close X Button fixed at top-right -->
            <button wire:click="closeLightbox" 
                    class="fixed top-4 right-4 sm:top-6 sm:right-6 z-[1010] text-white/90 hover:text-white p-2.5 rounded-full bg-black/60 hover:bg-black/80 border border-white/20 shadow-xl backdrop-blur-md transition-all duration-200 cursor-pointer focus:outline-none hover:scale-110 active:scale-95 flex items-center justify-center"
                    title="Chiudi (Esc)">
                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="relative max-w-5xl w-full flex flex-col items-center justify-center my-auto py-6">
                
                <!-- Media Display Container -->
                <div class="w-full flex items-center justify-center max-h-[78vh] sm:max-h-[82vh] overflow-hidden rounded-xl border border-[#8C6239]/40 shadow-2xl bg-black/80">
                    @if($selectedMedia->isVideo())
                        <video src="{{ $selectedMedia->media_url }}" controls autoplay class="max-h-[78vh] sm:max-h-[82vh] w-auto max-w-full rounded-xl object-contain"></video>
                    @else
                        <img src="{{ $selectedMedia->media_url }}" alt="{{ $selectedMedia->title ?: 'Foto Sposi' }}" class="max-h-[78vh] sm:max-h-[82vh] w-auto max-w-full object-contain rounded-xl">
                    @endif
                </div>

                <!-- Footer Title/Caption -->
                @if($selectedMedia->title || $selectedMedia->caption)
                    <div class="mt-4 text-center text-white space-y-1 max-w-2xl px-4">
                        @if($selectedMedia->title)
                            <h2 class="font-serif text-lg sm:text-xl font-bold text-[#8C6239] drop-shadow-sm">
                                {{ $selectedMedia->title }}
                            </h2>
                        @endif
                        @if($selectedMedia->caption)
                            <p class="font-serif text-sm text-zinc-300 italic leading-relaxed">
                                "{{ $selectedMedia->caption }}"
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>
