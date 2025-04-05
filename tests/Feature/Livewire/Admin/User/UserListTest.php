<?php

namespace Tests\Feature\Livewire\Admin\User;

use App\Models\Offices;
use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Admin\User\UserList;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create admin user for authentication
    $this->admin = User::factory()->create([
        'user_type' => 'ADMIN',
    ]);

    // Create test office
    $this->office = Offices::factory()->create([
        'name' => 'Test Office',
        'is_active' => true,
    ]);

    // Create test users
    $this->manager = User::factory()->create([
        'name' => 'Test Manager',
        'email' => 'manager@example.com',
        'user_type' => 'MANAGER',
        'office_id' => $this->office->id,
    ]);

    $this->employee = User::factory()->create([
        'name' => 'Test Employee',
        'email' => 'employee@example.com',
        'user_type' => 'PEGAWAI',
        'manager_id' => $this->manager->id,
        'office_id' => $this->office->id,
    ]);
});

test('user list component can be rendered', function () {
    $this->actingAs($this->admin)->get('/admin/users')->assertStatus(200);

    Livewire::test(UserList::class)->assertViewIs('livewire.admin.user.user-list');
});

test('user list shows users', function () {
    Livewire::actingAs($this->admin)->test(UserList::class)->assertSee('Test Manager')->assertSee('Test Employee')->assertSee('manager@example.com')->assertSee('employee@example.com');
});

test('user list can filter by role', function () {
    Livewire::actingAs($this->admin)->test(UserList::class)->set('filterRole', 'MANAGER')->assertSee('Test Manager')->assertDontSee('Test Employee');
});

test('user list can filter by office', function () {
    // Create another office and user
    $anotherOffice = Offices::factory()->create(['name' => 'Another Office']);
    $anotherUser = User::factory()->create([
        'name' => 'Another User',
        'user_type' => 'PEGAWAI',
        'office_id' => $anotherOffice->id,
    ]);

    Livewire::actingAs($this->admin)->test(UserList::class)->set('filterOffice', $this->office->id)->assertSee('Test Manager')->assertSee('Test Employee')->assertDontSee('Another User');
});

test('user list can search users', function () {
    Livewire::actingAs($this->admin)->test(UserList::class)->set('search', 'Manager')->assertSee('Test Manager')->assertDontSee('Test Employee');
});

test('user list can sort users', function () {
    Livewire::actingAs($this->admin)->test(UserList::class)->call('sortBy', 'name')->assertSet('sortField', 'name')->assertSet('sortDirection', 'desc');
});

test('user list can confirm delete', function () {
    Livewire::actingAs($this->admin)->test(UserList::class)->call('confirmDelete', $this->employee->id)->assertSet('confirmingDeletion', true)->assertSet('userToDelete', $this->employee->id);
});

test('user list can delete user', function () {
    Livewire::actingAs($this->admin)->test(UserList::class)->set('userToDelete', $this->employee->id)->call('deleteUser')->assertSet('confirmingDeletion', false)->assertSet('userToDelete', null);

    $this->assertDatabaseMissing('users', [
        'id' => $this->employee->id,
    ]);
});

test('user list can cancel delete', function () {
    Livewire::actingAs($this->admin)->test(UserList::class)->set('confirmingDeletion', true)->set('userToDelete', $this->employee->id)->call('cancelDelete')->assertSet('confirmingDeletion', false)->assertSet('userToDelete', null);

    $this->assertDatabaseHas('users', [
        'id' => $this->employee->id,
    ]);
});