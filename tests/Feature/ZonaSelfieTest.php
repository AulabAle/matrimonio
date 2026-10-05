<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use App\Models\Selfie;
use Tests\TestCase;

class ZonaSelfieTest extends TestCase
{
    use RefreshDatabase;

    public function test_zona_selfie_page_renders_successfully(): void
    {
        $response = $this->get('/zona-selfie');

        $response->assertStatus(200);
        $response->assertSee('Zona Selfie');
    }

    public function test_can_upload_selfie(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('selfie.jpg');

        Livewire::test('zona-selfie')
            ->set('photo', $file)
            ->set('caption', 'Foto di prova')
            ->call('saveSelfie')
            ->assertSet('successMessage', 'Foto aggiunta con successo alla Zona Selfie!');

        $this->assertDatabaseHas('selfies', [
            'caption' => 'Foto di prova',
        ]);

        $selfie = Selfie::first();
        Storage::disk('public')->assertExists($selfie->image_path);
    }

    public function test_can_delete_selfie(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('selfie_to_delete.jpg');
        $path = $file->store('selfies', 'public');

        $selfie = Selfie::create([
            'image_path' => $path,
            'caption' => 'Da eliminare',
        ]);

        Livewire::test('zona-selfie')
            ->call('deleteSelfie', $selfie->id)
            ->assertSet('successMessage', 'Foto eliminata con successo dalla galleria.');

        $this->assertDatabaseMissing('selfies', [
            'id' => $selfie->id,
        ]);

        Storage::disk('public')->assertMissing($path);
    }
}
