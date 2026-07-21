<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PersonalRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SecurityAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest cannot access the restricted area.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/area-riservata');
        $response->assertRedirect('/login');
    }

    /**
     * Test user can access the restricted area after login.
     */
    public function test_authenticated_user_can_access_area_riservata(): void
    {
        $user = User::factory()->create([
            'username' => 'guestuser',
            'role' => 'user'
        ]);

        $response = $this->actingAs($user)->get('/area-riservata');
        $response->assertStatus(200);
    }

    /**
     * Test database encryption for personal records.
     */
    public function test_sensitive_personal_data_is_encrypted_in_database(): void
    {
        $user = User::factory()->create(['username' => 'guestuser']);
        
        $record = PersonalRecord::create([
            'user_id' => $user->id,
            'full_name' => 'Mario Rossi',
            'phone' => '+393331234567',
            'notes' => 'Celiaco',
            'confirmed_seats' => 2,
        ]);

        // Retrieve the record via Eloquent - should be transparently decrypted
        $this->assertEquals('+393331234567', $record->phone);
        $this->assertEquals('Celiaco', $record->notes);

        // Query raw database row directly bypassing Eloquent casts
        $rawRow = DB::table('personal_records')->where('id', $record->id)->first();

        // The values stored in database MUST be encrypted and therefore NOT equal to the plain values
        $this->assertNotEquals('+393331234567', $rawRow->phone);
        $this->assertNotEquals('Celiaco', $rawRow->notes);
        
        // Ensure they are valid encrypted strings (usually starts with base64 encoded string, but we can verify it contains encrypted payload)
        $this->assertStringContainsString('eyJpdiI6', $rawRow->phone); // Laravel cipher text payload prefix
        $this->assertStringContainsString('eyJpdiI6', $rawRow->notes);
    }
}
