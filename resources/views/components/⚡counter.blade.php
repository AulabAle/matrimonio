<?php

use Livewire\Component;

new class extends Component
{
    public $count = 0;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }
};
?>

<div class="flex items-center justify-between px-6 py-2.5 bg-[#FAF6F0] border border-[#D1B280]/30 rounded-full w-full max-w-sm mx-auto shadow-inner my-4">
    <button wire:click="decrement" class="w-9 h-9 flex items-center justify-center bg-white text-zinc-500 font-semibold rounded-full shadow hover:bg-zinc-50 active:scale-95 transition-all select-none border border-zinc-100">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
    </button>
    <span class="text-lg font-bold text-charcoal font-serif select-none">
        {{ $count }}
    </span>
    <button wire:click="increment" class="w-9 h-9 flex items-center justify-center bg-sage-dark hover:bg-sage-medium text-white font-semibold rounded-full shadow active:scale-95 transition-all select-none">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
    </button>
</div>