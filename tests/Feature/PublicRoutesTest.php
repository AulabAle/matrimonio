<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;
    public function test_zona_selfie_is_publicly_accessible(): void
    {
        $response = $this->get('/zona-selfie');
        $response->assertStatus(200);
        $response->assertSee('Zona Selfie');
    }

    public function test_wedding_fight_is_publicly_accessible(): void
    {
        $response = $this->get('/wedding-fight');
        $response->assertStatus(200);
        $response->assertSee('Wedding Fight');
    }

    public function test_zona_rossa_is_publicly_accessible(): void
    {
        $response = $this->get('/zona-rossa');
        $response->assertStatus(200);
        $response->assertSee('Zona Rossa');
    }

    public function test_navbar_displays_public_links_for_guests(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('/zona-selfie');
        $response->assertSee('/wedding-fight');
        $response->assertSee('/zona-rossa');
    }
}
