<?php

namespace Tests\Feature;

use Livewire\Livewire;
use Tests\TestCase;

class CounterTest extends TestCase
{
    /**
     * Test if the welcome page loads successfully and contains the RSVP button.
     */
    public function test_rsvp_button_renders_on_welcome_page(): void
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertDontSeeLivewire('counter')
            ->assertSee('Conferma la tua partecipazione');
    }

    /**
     * Test if the counter starts at 0 and increments correctly.
     */
    public function test_counter_can_increment(): void
    {
        Livewire::test('counter')
            ->assertSet('count', 0)
            ->call('increment')
            ->assertSet('count', 1);
    }

    /**
     * Test if the counter decrements correctly.
     */
    public function test_counter_can_decrement(): void
    {
        Livewire::test('counter')
            ->assertSet('count', 0)
            ->call('decrement')
            ->assertSet('count', -1);
    }
}
