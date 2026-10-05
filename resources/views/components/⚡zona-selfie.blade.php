<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Selfie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

new class extends Component
{
    use WithFileUploads;

    public $photo = null;
    public $base64Image = null;
    public $caption = '';
    public $successMessage = null;
    public $errorMessage = null;

    public function mount()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect()->to('/login');
        }
    }

    protected function rules()
    {
        return [
            'photo' => 'nullable|image|max:10240', // 10MB max
            'caption' => 'nullable|string|max:255',
        ];
    }

    public function saveSelfie()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Azione non autorizzata.');
        }
        $this->successMessage = null;
        $this->errorMessage = null;

        try {
            $imagePath = null;

            if ($this->photo) {
                $this->validate();
                $imagePath = $this->photo->store('selfies', 'public');
            } elseif ($this->base64Image) {
                // Decode base64 image captured via camera canvas
                if (preg_match('/^data:image\/(\w+);base64,/', $this->base64Image, $type)) {
                    $data = substr($this->base64Image, strpos($this->base64Image, ',') + 1);
                    $type = strtolower($type[1]); // png, jpeg, webp

                    if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                        throw new \Exception('Formato immagine non valido.');
                    }

                    $data = base64_decode($data);

                    if ($data === false) {
                        throw new \Exception('Decodifica dell\'immagine fallita.');
                    }

                    $fileName = 'selfies/' . Str::uuid() . '.' . $type;
                    Storage::disk('public')->put($fileName, $data);
                    $imagePath = $fileName;
                } else {
                    throw new \Exception('Formato foto fotocamera non valido.');
                }
            } else {
                $this->errorMessage = 'Per favore, scatta una foto o seleziona un file prima di salvare.';
                return;
            }

            Selfie::create([
                'image_path' => $imagePath,
                'caption' => trim($this->caption) ?: null,
            ]);

            $this->reset(['photo', 'base64Image', 'caption']);
            $this->successMessage = 'Foto aggiunta con successo alla Zona Selfie!';
            $this->dispatch('selfie-added');

        } catch (\Exception $e) {
            $this->errorMessage = 'Errore durante il salvataggio: ' . $e->getMessage();
        }
    }

    public function deleteSelfie($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Azione non autorizzata.');
        }
        $selfie = Selfie::find($id);
        if ($selfie) {
            if ($selfie->image_path && Storage::disk('public')->exists($selfie->image_path)) {
                Storage::disk('public')->delete($selfie->image_path);
            }
            $selfie->delete();
            $this->successMessage = 'Foto eliminata con successo dalla galleria.';
            $this->dispatch('selfie-deleted');
        }
    }

    public function getSelfiesProperty()
    {
        return Selfie::orderBy('created_at', 'desc')->get();
    }

    public function downloadAllPolaroids()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Azione non autorizzata.');
        }

        $selfies = Selfie::orderBy('created_at', 'desc')->get();

        if ($selfies->isEmpty()) {
            $this->errorMessage = 'Nessuna foto disponibile da scaricare.';
            return;
        }

        $tempFolder = storage_path('app/temp_polaroids_' . Str::uuid());
        if (!is_dir($tempFolder)) {
            mkdir($tempFolder, 0755, true);
        }

        $fontPath = 'C:/Windows/Fonts/georgia.ttf';
        if (!file_exists($fontPath)) {
            $fontPath = 'C:/Windows/Fonts/arial.ttf';
        }

        $totalCount = $selfies->count();

        foreach ($selfies as $index => $selfie) {
            $fullPath = null;
            if (str_starts_with($selfie->image_path, 'selfies/')) {
                $fullPath = Storage::disk('public')->path($selfie->image_path);
            } elseif (file_exists(public_path(ltrim($selfie->image_path, '/')))) {
                $fullPath = public_path(ltrim($selfie->image_path, '/'));
            } elseif (file_exists(storage_path('app/public/' . ltrim($selfie->image_path, '/')))) {
                $fullPath = storage_path('app/public/' . ltrim($selfie->image_path, '/'));
            }

            if (!$fullPath || !file_exists($fullPath)) {
                continue;
            }

            $canvasWidth = 1200;
            $canvasHeight = 1440;
            $canvas = imagecreatetruecolor($canvasWidth, $canvasHeight);

            $bgColor = imagecolorallocate($canvas, 253, 251, 247);
            $borderColor = imagecolorallocate($canvas, 209, 178, 128);
            $innerBorderColor = imagecolorallocate($canvas, 229, 231, 235);
            $goldDark = imagecolorallocate($canvas, 140, 109, 59);
            $subTextColor = imagecolorallocate($canvas, 113, 113, 122);

            imagefilledrectangle($canvas, 0, 0, $canvasWidth, $canvasHeight, $bgColor);
            imagesetthickness($canvas, 4);
            imagerectangle($canvas, 10, 10, $canvasWidth - 11, $canvasHeight - 11, $borderColor);

            $imgInfo = @getimagesize($fullPath);
            if (!$imgInfo) {
                imagedestroy($canvas);
                continue;
            }

            $mime = $imgInfo['mime'];
            $srcImg = null;
            switch ($mime) {
                case 'image/jpeg':
                    $srcImg = @imagecreatefromjpeg($fullPath);
                    break;
                case 'image/png':
                    $srcImg = @imagecreatefrompng($fullPath);
                    break;
                case 'image/webp':
                    $srcImg = @imagecreatefromwebp($fullPath);
                    break;
                case 'image/gif':
                    $srcImg = @imagecreatefromgif($fullPath);
                    break;
            }

            if (!$srcImg) {
                imagedestroy($canvas);
                continue;
            }

            if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
                @$exif = exif_read_data($fullPath);
                if (!empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 3:
                            $srcImg = imagerotate($srcImg, 180, 0);
                            break;
                        case 6:
                            $srcImg = imagerotate($srcImg, -90, 0);
                            break;
                        case 8:
                            $srcImg = imagerotate($srcImg, 90, 0);
                            break;
                    }
                }
            }

            $origW = imagesx($srcImg);
            $origH = imagesy($srcImg);
            $boxX = 70;
            $boxY = 70;
            $boxSize = 1060;

            $srcAspect = $origW / $origH;
            if ($srcAspect > 1) {
                $cropH = $origH;
                $cropW = $origH;
                $cropX = (int)(($origW - $origH) / 2);
                $cropY = 0;
            } else {
                $cropW = $origW;
                $cropH = $origW;
                $cropX = 0;
                $cropY = (int)(($origH - $origW) / 2);
            }

            imagecopyresampled($canvas, $srcImg, $boxX, $boxY, $cropX, $cropY, $boxSize, $boxSize, $cropW, $cropH);
            imagedestroy($srcImg);

            imagesetthickness($canvas, 2);
            imagerectangle($canvas, $boxX - 1, $boxY - 1, $boxX + $boxSize, $boxY + $boxSize, $innerBorderColor);

            if (!empty($selfie->caption)) {
                $fontSize = 32;
                $bbox = imagettfbbox($fontSize, 0, $fontPath, '"' . $selfie->caption . '"');
                $textWidth = abs($bbox[2] - $bbox[0]);
                $textX = max(40, (int)(($canvasWidth - $textWidth) / 2));
                $textY = 1240;
                imagettftext($canvas, $fontSize, 0, $textX, $textY, $goldDark, $fontPath, '"' . $selfie->caption . '"');
            }

            $footerFontSize = 18;
            $dateStr = $selfie->created_at ? $selfie->created_at->format('d/m/Y H:i') : date('d/m/Y H:i');
            $footerText = "Ricordo N° " . ($totalCount - $index) . " • " . $dateStr;
            $fBbox = imagettfbbox($footerFontSize, 0, $fontPath, $footerText);
            $fWidth = abs($fBbox[2] - $fBbox[0]);
            $fX = (int)(($canvasWidth - $fWidth) / 2);
            $fY = 1350;

            imagettftext($canvas, $footerFontSize, 0, $fX, $fY, $subTextColor, $fontPath, $footerText);

            $fileName = sprintf("Polaroid_Selfie_%03d.jpg", $totalCount - $index);
            imagejpeg($canvas, $tempFolder . '/' . $fileName, 90);
            imagedestroy($canvas);
        }

        $zipFileName = 'Selfies_Polaroid_Monica_ed_Erasmo_' . date('Y-m-d_H-i') . '.zip';
        $zipPath = storage_path('app/' . $zipFileName);

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $files = glob($tempFolder . '/*.jpg');
            foreach ($files as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        array_map('unlink', glob($tempFolder . '/*.*'));
        @rmdir($tempFolder);

        if (!file_exists($zipPath)) {
            $this->errorMessage = 'Impossibile creare il pacchetto ZIP.';
            return;
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }
}; ?>

<div class="w-full space-y-10" 
     x-data="{
         videoStream: null,
         cameraActive: false,
         capturedPreview: null,
         cameraError: null,
         galleryView: 'carousel',
         
         startCamera() {
             this.cameraError = null;
             if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                 navigator.mediaDevices.getUserMedia({ 
                     video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } } 
                 })
                 .then((stream) => {
                     this.videoStream = stream;
                     this.cameraActive = true;
                     $nextTick(() => {
                         const video = this.$refs.videoEl;
                         if (video) {
                             video.srcObject = stream;
                             video.play();
                         }
                     });
                 })
                 .catch((err) => {
                     console.error('Errore fotocamera:', err);
                     this.cameraError = 'Impossibile accedere alla fotocamera. Utilizza il pulsante per caricare una foto dalla galleria.';
                     this.cameraActive = false;
                 });
             } else {
                 this.cameraError = 'La fotocamera non è supportata dal browser in uso. Usa il caricamento da file.';
             }
         },
         
         stopCamera() {
             if (this.videoStream) {
                 this.videoStream.getTracks().forEach(track => track.stop());
                 this.videoStream = null;
             }
             this.cameraActive = false;
         },
         
         takeSnapshot() {
             const video = this.$refs.videoEl;
             const canvas = this.$refs.canvasEl;
             if (video && canvas) {
                 const context = canvas.getContext('2d');
                 canvas.width = video.videoWidth || 640;
                 canvas.height = video.videoHeight || 480;
                 context.drawImage(video, 0, 0, canvas.width, canvas.height);
                 
                 const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
                 this.capturedPreview = dataUrl;
                 $wire.set('base64Image', dataUrl);
                 $wire.set('photo', null);
                 this.stopCamera();
             }
         },
         
         retake() {
             this.capturedPreview = null;
             $wire.set('base64Image', null);
             this.startCamera();
         },

         clearSelection() {
             this.capturedPreview = null;
             $wire.set('base64Image', null);
             $wire.set('photo', null);
             this.stopCamera();
         }
     }">

    <!-- Section Header -->
    <div class="text-center space-y-3">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-light/20 border border-gold-medium/30 text-gold-dark text-xs font-semibold uppercase tracking-widest">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Zona Selfie Sposi
        </div>
        <h1 class="font-serif text-3xl sm:text-4xl text-charcoal font-semibold">
            Scatta e Condividi i Tuoi Momenti
        </h1>
        <p class="font-serif text-charcoal-light italic text-sm sm:text-base max-w-xl mx-auto">
            Scatta un ricordo in tempo reale oppure carica una foto per aggiungerla alla galleria di nozze di Monica ed Erasmo!
        </p>
    </div>

    <!-- Alert Messages -->
    @if ($successMessage)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-lg flex items-center justify-between shadow-sm transition-all duration-300">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $successMessage }}</span>
            </div>
            <button @click="show = false" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
        </div>
    @endif

    @if ($errorMessage)
        <div x-data="{ show: true }" x-show="show" 
             class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-sm rounded-lg flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $errorMessage }}</span>
            </div>
            <button @click="show = false" class="text-rose-600 hover:text-rose-900 font-bold">&times;</button>
        </div>
    @endif

    <!-- Gallery Section Header & Display Mode Selector -->
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-b border-zinc-200/80 pb-4">
            <div>
                <h2 class="font-serif text-2xl sm:text-3xl text-charcoal font-semibold text-center sm:text-left">
                    Galleria Ricordi Sposi
                </h2>
                <p class="font-serif text-xs sm:text-sm text-charcoal-light italic text-center sm:text-left">
                    Foto condivise dagli invitati per Monica ed Erasmo (Totale: {{ $this->selfies->count() }})
                </p>
            </div>

            <!-- View Switcher Tabs (Carosello / Griglia Foto) -->
            <div class="inline-flex rounded-lg bg-zinc-200/70 p-1 border border-zinc-300/60 font-serif text-xs">
                <button type="button" 
                        @click="galleryView = 'carousel'" 
                        :class="galleryView === 'carousel' ? 'bg-white text-gold-dark font-semibold shadow-sm' : 'text-charcoal-light hover:text-charcoal'"
                        class="px-3.5 py-1.5 rounded-md transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Carosello Polaroid
                </button>
                <button type="button" 
                        @click="galleryView = 'grid'" 
                        :class="galleryView === 'grid' ? 'bg-white text-gold-dark font-semibold shadow-sm' : 'text-charcoal-light hover:text-charcoal'"
                        class="px-3.5 py-1.5 rounded-md transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Tutte le Foto (Gestisci)
            </div>
        </div>

        @if ($this->selfies->isEmpty())
            <div class="bg-white border border-dashed border-[#D1B280]/60 rounded-xl p-12 text-center space-y-3 paper-texture">
                <div class="w-12 h-12 rounded-full bg-gold-light/20 text-gold-dark flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-serif text-lg font-semibold text-charcoal">Nessuna foto ancora inserita</h3>
                <p class="font-serif text-xs text-charcoal-light italic max-w-md mx-auto">
                    Sii il primo a scattare una foto e lasciarla come ricordo per il matrimonio di Monica ed Erasmo!
                </p>
            </div>
        @else

            <!-- MODE 1: Swiper JS Powered Polaroid Carousel -->
            <div x-show="galleryView === 'carousel'" x-transition class="space-y-4">
                <div x-data="{
                        swiper: null,
                        autoTimer: null,

                        initSwiper() {
                            if (typeof Swiper === 'undefined') return;
                            if (this.swiper) {
                                this.swiper.destroy(true, true);
                            }
                            const count = {{ $this->selfies->count() }};
                            if (count === 0) return;

                            this.swiper = new Swiper(this.$refs.swiperContainer, {
                                effect: 'fade',
                                fadeEffect: { crossFade: true },
                                speed: 600,
                                loop: count > 1,
                                autoplay: count > 1 ? {
                                    delay: 3000,
                                    disableOnInteraction: false,
                                } : false,
                                grabCursor: true,
                                pagination: {
                                    el: '.swiper-pagination',
                                    clickable: true,
                                },
                                navigation: {
                                    nextEl: '.swiper-button-next',
                                    prevEl: '.swiper-button-prev',
                                },
                                observer: true,
                                observeParents: true,
                            });

                            this.startAutoSlide(count);
                        },

                        startAutoSlide(count) {
                            this.stopAutoSlide();
                            if (count > 1) {
                                if (this.swiper && this.swiper.autoplay) {
                                    this.swiper.autoplay.start();
                                }
                                this.autoTimer = setInterval(() => {
                                    if (this.swiper) {
                                        this.swiper.slideNext();
                                    }
                                }, 3000);
                            }
                        },

                        stopAutoSlide() {
                            if (this.swiper && this.swiper.autoplay) {
                                this.swiper.autoplay.stop();
                            }
                            if (this.autoTimer) {
                                clearInterval(this.autoTimer);
                                this.autoTimer = null;
                            }
                        }
                     }"
                     x-init="$nextTick(() => initSwiper()); Livewire.on('selfie-added', () => $nextTick(() => initSwiper())); Livewire.on('selfie-deleted', () => $nextTick(() => initSwiper()));"
                     @mouseenter="stopAutoSlide()"
                     @mouseleave="startAutoSlide({{ $this->selfies->count() }})"
                     class="relative max-w-lg mx-auto">
                    
                    <!-- Swiper Main Container -->
                    <div x-ref="swiperContainer" class="swiper mySwiper overflow-hidden rounded-2xl p-1 pb-12">
                        <div class="swiper-wrapper">
                            @foreach ($this->selfies as $index => $selfie)
                                <div wire:key="swiper-slide-{{ $selfie->id }}" class="swiper-slide">
                                    
                                    <!-- Polaroid Card Frame -->
                                    <div class="bg-white border border-[#D1B280]/40 rounded-2xl p-5 sm:p-7 shadow-2xl overflow-hidden paper-texture flex flex-col space-y-4">
                                        
                                        <!-- Photo Image Container -->
                                        <div class="relative aspect-[4/3] sm:aspect-square w-full rounded-xl overflow-hidden bg-zinc-900 border border-zinc-200/80 shadow-md">
                                            <img src="{{ $selfie->image_url }}" 
                                                 alt="Selfie Matrimonio" 
                                                 class="w-full h-full object-cover">
                                        </div>

                                        <!-- Caption Text -->
                                        <div class="text-center px-1 min-h-[2.5rem] flex items-center justify-center">
                                            @if ($selfie->caption)
                                                <p class="font-script text-gold-dark text-2xl sm:text-3xl font-medium leading-relaxed">
                                                    "{{ $selfie->caption }}"
                                                </p>
                                            @else
                                                <p class="font-serif text-xs text-charcoal-light/60 italic">
                                                    Ricordo di Nozze
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Footer Info -->
                                        <div class="flex items-center justify-center border-t border-[#D1B280]/30 pt-3 text-[11px] text-charcoal-light font-serif">
                                            <span class="tracking-wider">Ricordo N° {{ $this->selfies->count() - $index }} • {{ $selfie->created_at->format('d/m/Y H:i') }}</span>
                                        </div>

                                    </div>

                                </div>
                            @endforeach
                        </div>

                        <!-- Swiper Navigation Arrows -->
                        @if ($this->selfies->count() > 1)
                            <div class="swiper-button-prev !w-10 !h-10 !rounded-full !bg-white/90 !text-gold-dark hover:!bg-gold-dark hover:!text-white !border !border-[#D1B280]/40 !shadow-md backdrop-blur transition-all duration-200"></div>
                            <div class="swiper-button-next !w-10 !h-10 !rounded-full !bg-white/90 !text-gold-dark hover:!bg-gold-dark hover:!text-white !border !border-[#D1B280]/40 !shadow-md backdrop-blur transition-all duration-200"></div>
                            
                            <!-- Swiper Pagination Dots -->
                            <div class="swiper-pagination !bottom-1"></div>
                        @endif
                    </div>

                </div>
            </div>

            <!-- MODE 2: Grid View for Instant Management & Deletion -->
            <div x-show="galleryView === 'grid'" x-transition class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach ($this->selfies as $index => $selfie)
                        <div wire:key="grid-card-{{ $selfie->id }}" 
                             class="bg-white border border-[#D1B280]/40 rounded-xl p-3 shadow-md hover:shadow-lg transition-all duration-200 paper-texture flex flex-col justify-between">
                            
                            <!-- Image Frame -->
                            <div class="aspect-square w-full rounded-lg overflow-hidden bg-zinc-900 border border-zinc-200 relative group">
                                <img src="{{ $selfie->image_url }}" alt="Selfie Matrimonio" class="w-full h-full object-cover">
                            </div>

                            <!-- Caption & Delete Footer -->
                            <div class="pt-3 space-y-2">
                                @if ($selfie->caption)
                                    <p class="font-script text-gold-dark text-lg font-medium leading-snug line-clamp-2">
                                        "{{ $selfie->caption }}"
                                    </p>
                                @endif

                                <div class="flex items-center justify-between gap-2 border-t border-zinc-200/60 pt-2 text-[10px] text-charcoal-light font-serif">
                                    <span>{{ $selfie->created_at->format('d/m/Y H:i') }}</span>

                                    <!-- Delete Button -->
                                    <button type="button" 
                                            wire:click="deleteSelfie({{ $selfie->id }})" 
                                            wire:confirm="Sei sicuro di voler eliminare questa foto dalla galleria?"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-200 hover:border-rose-600 rounded transition-all duration-200 font-semibold cursor-pointer">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Elimina
                                    </button>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

        @endif
    </div>

    <!-- Selfie Capture Card -->
    <div x-show="galleryView === 'carousel'" x-transition class="bg-white border border-[#D1B280]/40 rounded-xl p-4 sm:p-8 shadow-xl relative overflow-hidden paper-texture">
        <div class="max-w-2xl mx-auto space-y-6">
            
            <div class="flex items-center justify-between border-b border-zinc-200/80 pb-4">
                <h2 class="font-serif text-lg font-semibold text-charcoal flex items-center gap-2">
                    <svg class="w-5 h-5 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    </svg>
                    Scatta o Carica una Foto
                </h2>
                <div class="text-xs text-charcoal-light font-serif italic">
                    Polaroid Moment
                </div>
            </div>

            <!-- Viewport / Canvas Container -->
            <div class="relative bg-zinc-900 rounded-lg overflow-hidden shadow-inner aspect-[4/3] flex items-center justify-center border-4 border-white shadow-md">
                
                <!-- Live Video Element -->
                <video x-ref="videoEl" 
                       x-show="cameraActive && !capturedPreview" 
                       autoplay playsinline 
                       class="w-full h-full object-cover transform -scale-x-100">
                </video>

                <!-- Hidden Canvas for capturing image -->
                <canvas x-ref="canvasEl" class="hidden"></canvas>

                <!-- Captured Preview (via Camera) -->
                <template x-if="capturedPreview">
                    <img :src="capturedPreview" alt="Anteprima Selfie" class="w-full h-full object-cover">
                </template>

                <!-- File Upload Livewire Preview -->
                @if ($photo && !is_string($photo))
                    <img src="{{ $photo->temporaryUrl() }}" alt="Anteprima file caricato" class="w-full h-full object-cover">
                @endif

                <!-- Initial Idle State (Camera inactive & no photo uploaded) -->
                <div x-show="!cameraActive && !capturedPreview && !@js($photo)" 
                     class="text-center p-6 space-y-4">
                    <div class="w-16 h-16 rounded-full bg-gold-light/20 text-gold-dark flex items-center justify-center mx-auto border border-gold-medium/40">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <p class="text-white/80 font-serif text-sm max-w-xs mx-auto">
                        Attiva la fotocamera per scattare direttamente, oppure carica un'immagine dal tuo dispositivo.
                    </p>
                </div>

                <!-- Camera Error Alert Overlay -->
                <div x-show="cameraError" class="absolute inset-0 bg-charcoal/90 text-white p-6 flex flex-col justify-center items-center text-center space-y-3">
                    <svg class="w-10 h-10 text-gold-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-sm font-serif" x-text="cameraError"></p>
                    <button type="button" @click="cameraError = null" class="px-4 py-1.5 bg-gold-dark text-white rounded text-xs uppercase font-semibold">
                        Chiudi
                    </button>
                </div>
            </div>

            <!-- Controls & Action Buttons -->
            <div class="space-y-4">
                
                <!-- Action Buttons: Camera On / Take Photo / Retake / File Upload -->
                <div class="flex flex-wrap items-center justify-center gap-3">
                    
                    <!-- Start Camera Button -->
                    <button type="button" 
                            x-show="!cameraActive && !capturedPreview && !@js($photo)" 
                            @click="startCamera()" 
                            class="px-5 py-2.5 bg-sage-dark hover:bg-sage-medium text-white font-serif text-xs uppercase tracking-wider font-semibold rounded-lg shadow transition-all duration-200 flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Apri Fotocamera
                    </button>

                    <!-- Take Snapshot Button -->
                    <button type="button" 
                            x-show="cameraActive && !capturedPreview" 
                            @click="takeSnapshot()" 
                            class="px-6 py-2.5 bg-gold-dark hover:bg-gold-medium text-white font-serif text-xs uppercase tracking-wider font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 cursor-pointer ring-4 ring-gold-light/40 animate-pulse">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Scatta Foto
                    </button>

                    <!-- Retake Camera Snapshot Button -->
                    <button type="button" 
                            x-show="capturedPreview" 
                            @click="retake()" 
                            class="px-4 py-2 bg-zinc-200 hover:bg-zinc-300 text-charcoal font-serif text-xs uppercase tracking-wider font-semibold rounded-lg transition-all duration-200 flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Riscatta
                    </button>

                    <!-- File Upload Button (Fallback / Alternative) -->
                    <label class="px-4 py-2.5 bg-white border border-zinc-300 hover:border-gold-medium text-charcoal font-serif text-xs uppercase tracking-wider font-semibold rounded-lg shadow-sm hover:shadow transition-all duration-200 flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 text-gold-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span>Carica Foto</span>
                        <input type="file" wire:model="photo" accept="image/*" capture="user" class="hidden" @change="capturedPreview = null; stopCamera()">
                    </label>

                    <!-- Reset Selection Button -->
                    <button type="button" 
                            x-show="capturedPreview || @js($photo) || cameraActive" 
                            @click="clearSelection()" 
                            class="px-3 py-2 text-rose-600 hover:text-rose-800 text-xs font-serif italic cursor-pointer">
                        Annulla
                    </button>
                </div>

                <!-- Caption Input & Save Form -->
                <div x-show="capturedPreview || @js($photo)" x-transition class="pt-4 border-t border-zinc-200/80 space-y-4">
                    <div>
                        <label for="caption" class="block font-serif text-xs uppercase tracking-wider text-charcoal font-semibold mb-1">
                            Aggiungi una dedica o il tuo nome (opzionale)
                        </label>
                        <input type="text" 
                               id="caption"
                               wire:model="caption" 
                               placeholder="Es: Auguri dagli zii Marco e Lucia!" 
                               class="w-full px-4 py-2.5 border border-zinc-300 rounded-lg text-sm text-charcoal focus:ring-2 focus:ring-gold-medium/50 focus:border-gold-medium outline-none transition-all">
                        @error('caption') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-center">
                        <button type="button" 
                                wire:click="saveSelfie" 
                                wire:loading.attr="disabled"
                                class="w-full sm:w-auto px-8 py-3 bg-gold-dark hover:bg-gold-medium disabled:opacity-50 text-white font-serif text-sm uppercase tracking-widest font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
                            <span wire:loading.remove wire:target="saveSelfie">Pubblica Foto nella Galleria</span>
                            <span wire:loading wire:target="saveSelfie" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Salvataggio in corso...
                            </span>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
