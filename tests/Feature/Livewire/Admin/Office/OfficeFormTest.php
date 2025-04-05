<?php

namespace Tests\Feature\Livewire\Admin\Office;

use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Offices;
use App\Livewire\Admin\Office\OfficeForm;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
      // Create admin user for authentication
    $this->admin = User::factory()->create([
        'user_type' => 'ADMIN',
    ]);

      // Create available managers
    $this->manager1 = User::factory()->create([
        'name'      => 'Manager One',
        'user_type' => 'MANAGER',
        'office_id' => null,
    ]);

    $this->manager2 = User::factory()->create([
        'name'      => 'Manager Two',
        'user_type' => 'MANAGER',
        'office_id' => null,
    ]);
});

test('office form component can be rendered', function () {
    $this->actingAs($this->admin)->get('/admin/offices/create')->assertStatus(200);

    Livewire::test(OfficeForm::class)->assertViewIs('livewire.admin.office.office-form');
});

test('office form can create office', function () {
    Livewire::actingAs($this->admin)
        ->test(OfficeForm::class)
        ->set('name', 'New Office')
        ->set('address', '123 Main Street')
        ->set('city', 'New York')
        ->set('state', 'NY')
        ->set('postal_code', '10001')
        ->set('country', 'USA')
        ->set('latitude', 40.7128)
        ->set('longitude', -74.006)
        ->set('check_in_radius', 100)
        ->set('is_active', true)
        ->call('save')
        ->assertRedirect(route('admin.offices.index'));

    $this->assertDatabaseHas('offices', [
        'name'            => 'New Office',
        'address'         => '123 Main Street',
        'city'            => 'New York',
        'state'           => 'NY',
        'postal_code'     => '10001',
        'country'         => 'USA',
        'latitude'        => 40.7128,
        'longitude'       => -74.006,
        'check_in_radius' => 100,
        'is_active'       => true,
    ]);
});

test('office form can edit office', function () {
      // Create office to edit
    $office = Offices::factory()->create([
        'name'    => 'Office to Edit',
        'address' => '456 Park Avenue',
        'city'    => 'Chicago',
        'country' => 'USA',
    ]);

    Livewire::actingAs($this->admin)
        ->test(OfficeForm::class, ['office' => $office])
        ->assertSet('name', 'Office to Edit')
        ->assertSet('address', '456 Park Avenue')
        ->assertSet('city', 'Chicago')
        ->assertSet('country', 'USA')
        ->set('name', 'Updated Office')
        ->set('address', '789 Broadway')
        ->set('city', 'Los Angeles')
        ->call('save')
        ->assertRedirect(route('admin.offices.index'));

    $this->assertDatabaseHas('offices', [
        'id'      => $office->id,
        'name'    => 'Updated Office',
        'address' => '789 Broadway',
        'city'    => 'Los Angeles',
    ]);
});

test('office form validates required fields', function () {
    Livewire::actingAs($this->admin)
        ->test(OfficeForm::class)
        ->set('name', '')
        ->set('address', '')
        ->set('city', '')
        ->set('country', '')
        ->call('save')
        ->assertHasErrors(['name', 'address', 'city', 'country']);
});

test('office form validates latitude and longitude', function () {
    Livewire::actingAs($this->admin)
        ->test(OfficeForm::class)
        ->set('latitude', 100)   // Invalid latitude (must be between -90 and 90)
        ->set('longitude', 200)  // Invalid longitude (must be between -180 and 180)
        ->call('save')
        ->assertHasErrors(['latitude', 'longitude']);
});

test('office form validates check in radius', function () {
    Livewire::actingAs($this->admin)
        ->test(OfficeForm::class)
        ->set('check_in_radius', 5)  // Too small
        ->call('save')
        ->assertHasErrors(['check_in_radius'])
        ->set('check_in_radius', 2000)  // Too large
        ->call('save')
        ->assertHasErrors(['check_in_radius']);
});

test('map location can be selected', function () {
    Livewire::actingAs($this->admin)->test(OfficeForm::class)->call('mapLocationSelected', 35.6762, 139.6503)->assertSet('latitude', 35.6762)->assertSet('longitude', 139.6503);
});