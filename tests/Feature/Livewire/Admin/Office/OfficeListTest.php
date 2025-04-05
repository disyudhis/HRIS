<?php

namespace Tests\Feature\Livewire\Admin\Office;

use App\Models\Offices;
use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Admin\Office\OfficeList;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create admin user for authentication
    $this->admin = User::factory()->create([
        'user_type' => 'ADMIN',
    ]);

    // Create test offices
    $this->office1 = Offices::factory()->create([
        'name' => 'Jakarta Office',
        'city' => 'Jakarta',
        'country' => 'Indonesia',
        'is_active' => true,
    ]);

    $this->office2 = Offices::factory()->create([
        'name' => 'Singapore Office',
        'city' => 'Singapore',
        'country' => 'Singapore',
        'is_active' => true,
    ]);

    // Create manager for office1
    $this->manager = User::factory()->create([
        'name' => 'Office Manager',
        'user_type' => 'MANAGER',
        'office_id' => $this->office1->id,
    ]);
});

test('office list component can be rendered', function () {
    $this->actingAs($this->admin)->get('/admin/offices')->assertStatus(200);

    Livewire::test(OfficeList::class)->assertViewIs('livewire.admin.office.office-list');
});

test('office list shows offices', function () {
    Livewire::actingAs($this->admin)->test(OfficeList::class)->assertSee('Jakarta Office')->assertSee('Singapore Office')->assertSee('Jakarta, Indonesia')->assertSee('Singapore, Singapore');
});

test('office list can search offices', function () {
    Livewire::actingAs($this->admin)->test(OfficeList::class)->set('search', 'Jakarta')->assertSee('Jakarta Office')->assertDontSee('Singapore Office');
});

test('office list can confirm delete', function () {
    Livewire::actingAs($this->admin)->test(OfficeList::class)->call('confirmDelete', $this->office2->id)->assertSet('confirmingDeletion', true)->assertSet('officeToDelete', $this->office2->id);
});

test('office list can delete office', function () {
    Livewire::actingAs($this->admin)
        ->test(OfficeList::class)
        ->set('officeToDelete', $this->office2->id)
        ->call('deleteOffice')
        ->assertSet('confirmingDeletion', false)
        ->assertSet('officeToDelete', null);

    $this->assertDatabaseMissing('offices', [
        'id' => $this->office2->id,
    ]);
});

test('office list can cancel delete', function () {
    Livewire::actingAs($this->admin)->test(OfficeList::class)->set('confirmingDeletion', true)->set('officeToDelete', $this->office2->id)->call('cancelDelete')->assertSet('confirmingDeletion', false)->assertSet('officeToDelete', null);

    $this->assertDatabaseHas('offices', [
        'id' => $this->office2->id,
    ]);
});

test('deleting office unassigns users from that office', function () {
    // Create employee for office2
    $employee = User::factory()->create([
        'user_type' => 'PEGAWAI',
        'office_id' => $this->office2->id,
    ]);

    Livewire::actingAs($this->admin)->test(OfficeList::class)->set('officeToDelete', $this->office2->id)->call('deleteOffice');

    $this->assertDatabaseMissing('offices', [
        'id' => $this->office2->id,
    ]);

    $this->assertDatabaseHas('users', [
        'id' => $employee->id,
        'office_id' => null,
    ]);
});