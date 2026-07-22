<?php

namespace Tests\Feature;

use App\Models\Rsvp;
use App\Models\RsvpMember;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RsvpTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the RSVP form page loads successfully.
     */
    public function test_rsvp_page_loads_successfully(): void
    {
        $this->get('/conferma')
            ->assertStatus(200)
            ->assertSeeLivewire('rsvp-form');
    }

    /**
     * Test that the print invitation page loads successfully.
     */
    public function test_print_invitation_page_loads_successfully(): void
    {
        $this->get('/stampa-invito')
            ->assertStatus(200)
            ->assertSee('Stampa il tuo Invito');
    }

    public function test_pdf_invitation_route_streams_pdf(): void
    {
        $response = $this->get(route('invito-pdf'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }



    /**
     * Test that validation fails when name is empty.
     */
    public function test_rsvp_validation_requires_names(): void
    {
        Livewire::test('rsvp-form')
            ->set('first_name', '')
            ->set('last_name', '')
            ->call('submit')
            ->assertHasErrors(['first_name', 'last_name']);
    }

    /**
     * Test that submitting the form saves the RSVP in the database.
     */
    public function test_rsvp_submits_successfully_and_saves_data(): void
    {
        Livewire::test('rsvp-form')
            ->set('first_name', 'Mario')
            ->set('last_name', 'Rossi')
            ->set('will_attend', true)
            ->set('allergies', 'Frutta a guscio')
            ->set('notes', 'Arriverò un po\' in ritardo')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('success', true);

        // Verify it was saved in the database
        $this->assertDatabaseHas('rsvps', [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'will_attend' => true,
            'is_pregnant' => false,
            'rsvp_type' => 'single',
        ]);

        $rsvp = Rsvp::where('first_name', 'Mario')->first();
        $this->assertEquals('Frutta a guscio', $rsvp->allergies);
    }

    /**
     * Test that admin can see rsvp entries in area-riservata.
     */
    public function test_admin_can_view_rsvp_list(): void
    {
        // Create an RSVP entry
        $rsvp = Rsvp::create([
            'first_name' => 'Giuseppe',
            'last_name' => 'Verdi',
            'will_attend' => true,
            'is_pregnant' => false,
            'allergies' => 'Fragole',
            'dietary_requirements' => 'Vegano',
            'notes' => 'Tavolo vicino alla finestra',
            'rsvp_type' => 'family',
        ]);

        RsvpMember::create([
            'rsvp_id' => $rsvp->id,
            'member_type' => 'spouse',
            'first_name' => 'Luisa',
            'last_name' => 'Verdi',
            'will_attend' => true,
            'allergies' => 'Lattosio',
        ]);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Livewire::test('area-riservata')
            ->set('activeTab', 'rsvp')
            ->assertSee('Giuseppe Verdi')
            ->assertSee('Fragole')
            ->assertSee('Tavolo vicino alla finestra')
            // Toggle expansion to show spouse
            ->call('toggleRsvpExpansion', $rsvp->id)
            ->assertSee('Luisa Verdi')
            ->assertSee('Lattosio');
    }

    /**
     * Test that admin can view the interactive seating map, assign guests to a table,
     * see correct stats computation, and remove guests.
     */
    public function test_admin_can_manage_seating_chart(): void
    {
        // 1. Setup guests: one primary guest, one adult spouse, one child needing highchair & baby menu
        $rsvp = Rsvp::create([
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'will_attend' => true,
            'is_pregnant' => false,
            'rsvp_type' => 'family',
        ]);

        $spouse = RsvpMember::create([
            'rsvp_id' => $rsvp->id,
            'member_type' => 'spouse',
            'first_name' => 'Paola',
            'last_name' => 'Rossi',
            'will_attend' => true,
            'is_pregnant' => false,
        ]);

        $child = RsvpMember::create([
            'rsvp_id' => $rsvp->id,
            'member_type' => 'child',
            'first_name' => 'Luca',
            'last_name' => 'Rossi',
            'will_attend' => true,
            'age' => 3,
            'needs_highchair' => true,
            'needs_baby_menu' => true,
        ]);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // 2. Load Area Riservata, check we are on map tab, select table 18
        $component = Livewire::test('area-riservata')
            ->set('activeTab', 'map')
            ->set('selectedTableId', 18);

        // All three guests should start as unassigned
        $component->assertSee('Mario Rossi')
            ->assertSee('Paola Rossi')
            ->assertSee('Luca Rossi')
            ->assertSee('Ospiti Non Assegnati')
            ->assertSee('3'); // count of unassigned guests

        // 3. Assign primary guest Mario Rossi (adult) to table 18
        $component->call('assignToTable', 'primary', $rsvp->id, 18);
        $this->assertEquals(18, $rsvp->refresh()->table_number);

        // 4. Assign spouse Paola Rossi (adult) to table 18
        $component->call('assignToTable', 'member', $spouse->id, 18);
        $this->assertEquals(18, $spouse->refresh()->table_number);

        // 5. Assign child Luca Rossi (needs highchair & baby menu) to table 18
        $component->call('assignToTable', 'member', $child->id, 18);
        $this->assertEquals(18, $child->refresh()->table_number);

        // 6. Verify stats are computed and visible in the view
        $component->assertSee('Menu Adulti (AD)')
            ->assertSee('Menu Baby (MB)')
            ->assertSee('Sedie (Regular)')
            ->assertSee('Seggioloni')
            ->assertSee('Tavolo 18')
            ->assertSee('Assegnati: 3 / 10');

        // 7. Remove child from table
        $component->call('removeFromTable', 'member', $child->id);
        $this->assertNull($child->refresh()->table_number);

        // Stats should update: Assegnati: 2 / 10
        $component->assertSee('Assegnati: 2 / 10');
    }

    /**
     * Test that admin can assign guest using the composite key from the dropdown.
     */
    public function test_admin_can_assign_guest_using_composite_key(): void
    {
        $rsvp = Rsvp::create([
            'first_name' => 'Luigi',
            'last_name' => 'Verdi',
            'will_attend' => true,
            'is_pregnant' => false,
            'rsvp_type' => 'single',
        ]);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Livewire::test('area-riservata')
            ->set('activeTab', 'map')
            ->set('selectedTableId', 5)
            ->set('assigneeGuestId', 'primary-' . $rsvp->id)
            ->call('assignToTable', 5)
            ->assertSet('assigneeGuestId', '');

        $this->assertEquals(5, $rsvp->refresh()->table_number);
    }


    /**
     * Test that admin can download the seating chart Excel spreadsheet,
     * and that guests/non-admin users are forbidden from downloading it.
     */
    public function test_admin_can_download_seating_excel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        // 1. Non-admin user should not be able to trigger downloadExcel
        $this->actingAs($user);
        Livewire::test('area-riservata')
            ->call('downloadExcel')
            ->assertStatus(403);

        // 2. Admin user should download the file successfully
        $this->actingAs($admin);
        
        $rsvp = Rsvp::create([
            'first_name' => 'Chiara',
            'last_name' => 'Rossi',
            'will_attend' => true,
            'rsvp_type' => 'single',
            'table_number' => 4,
        ]);

        $response = Livewire::test('area-riservata')
            ->call('downloadExcel');

        $response->assertStatus(200)
            ->assertFileDownloaded('Disposizione_Tavoli.xlsx');
    }

    /**
     * Test that guest dietary requirements and allergies are correctly retrieved
     * by getGuestsData() to support highlight formatting in the seating chart.
     */
    public function test_guest_dietary_requirements_retrieved_correctly(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $rsvp = Rsvp::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'will_attend' => true,
            'rsvp_type' => 'single',
            'table_number' => 8,
            'allergies' => 'noci',
            'dietary_requirements' => 'celiaco',
        ]);

        $component = Livewire::test('area-riservata');
        
        $guestsData = $component->instance()->getGuestsData();

        $this->assertNotEmpty($guestsData['attending']);
        $guest = collect($guestsData['attending'])->firstWhere('id', $rsvp->id);
        
        $this->assertNotNull($guest);
        $this->assertEquals('noci', $guest['allergies']);
        $this->assertEquals('celiaco', $guest['dietary_requirements']);
    }

    /**
     * Test that admin can update the has_gift and receives_favor status
     * of a guest, and that it saves successfully to the database.
     */
    public function test_admin_can_update_gift_and_favor_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $rsvp = Rsvp::create([
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'will_attend' => true,
            'rsvp_type' => 'single',
            'has_gift' => false,
            'receives_favor' => false,
        ]);

        Livewire::test('area-riservata')
            ->call('startEditGuest', 'primary', $rsvp->id)
            ->assertSet('editHasGift', false)
            ->assertSet('editReceivesFavor', false)
            ->set('editHasGift', true)
            ->set('editReceivesFavor', true)
            ->call('saveGuest');

        $rsvp->refresh();
        $this->assertTrue($rsvp->has_gift);
        $this->assertTrue($rsvp->receives_favor);

        // Verify it is also returned correctly in list representation
        $component = Livewire::test('area-riservata');
        $allGuests = $component->instance()->getAllGuestsForTable();
        $guest = collect($allGuests)->firstWhere('id', $rsvp->id);
        
        $this->assertNotNull($guest);
        $this->assertTrue($guest['has_gift']);
        $this->assertTrue($guest['receives_favor']);
    }

    /**
     * Test that admin can download the guest list CSV export,
     * and that regular users are forbidden from downloading it.
     */
    public function test_admin_can_download_guest_list_csv(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        // 1. Non-admin user should be forbidden
        $this->actingAs($user);
        Livewire::test('area-riservata')
            ->call('exportGuestsCsv')
            ->assertStatus(403);

        // 2. Admin should get a download stream response
        $this->actingAs($admin);
        $response = Livewire::test('area-riservata')
            ->call('exportGuestsCsv');

        $response->assertStatus(200)
            ->assertFileDownloaded('Lista_Invitati.csv');
    }

    /**
     * Test that submitting the form with additional names in notes does NOT extract and register them.
     */
    public function test_rsvp_does_not_extract_guests_from_notes(): void
    {
        Livewire::test('rsvp-form')
            ->set('first_name', 'Mario')
            ->set('last_name', 'Rossi')
            ->set('will_attend', true)
            ->set('notes', 'Saremo presenti io e Luca Neri, e anche Sofia')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('success', true);

        // Verify primary guest
        $this->assertDatabaseHas('rsvps', [
            'first_name' => 'Mario',
            'last_name' => 'Rossi',
            'will_attend' => true,
        ]);

        // Verify no extra guests were created
        $this->assertDatabaseMissing('rsvps', [
            'first_name' => 'Luca',
        ]);
        $this->assertDatabaseMissing('rsvps', [
            'first_name' => 'Sofia',
        ]);
    }

    public function test_opening_pdf_invitation_blocks_rest_of_application_for_guests(): void
    {
        // 1. Visit PDF page -> should set session variable and return PDF
        $response = $this->get(route('invito-pdf'));
        $response->assertStatus(200);
        $this->assertTrue(session()->has('block_site'));

        // 2. Try to visit home page -> should redirect back to the PDF
        $response = $this->get('/');
        $response->assertRedirect(route('invito-pdf'));

        // 3. Try to visit details page -> should redirect back to the PDF
        $response = $this->get('/dettagli');
        $response->assertRedirect(route('invito-pdf'));
    }

    public function test_admin_is_not_blocked_by_pdf_session(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // 1. Visit PDF page -> sets session variable
        $response = $this->get(route('invito-pdf'));
        $response->assertStatus(200);
        $this->assertTrue(session()->has('block_site'));

        // 2. Try to visit home page -> should load successfully (status 200) since user is admin
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_host_based_pdf_protection_redirects_non_pdf_routes(): void
    {
        $response = $this->get('https://pdf.invito/');
        $response->assertRedirect('https://pdf.invito/shared/invito-matrimonio-monica-erasmo.pdf');

        $response = $this->get('https://pdf.invito/dettagli');
        $response->assertRedirect('https://pdf.invito/shared/invito-matrimonio-monica-erasmo.pdf');
    }
}

