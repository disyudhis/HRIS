<?php

namespace Tests\Feature\Livewire\Admin\User;

use App\Models\Offices;
use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Admin\User\UserForm;
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

    // Create test manager
    $this->manager = User::factory()->create([
        'name' => 'Test Manager',
        'email' => 'manager@example.com',
        'user_type' => 'MANAGER',
        'office_id' => $this->office->id,
    ]);
});

test('user form component can be rendered', function () {
    $this->actingAs($this->admin)->get('/admin/users/create')->assertStatus(200);

    Livewire::test(UserForm::class)->assertViewIs('livewire.admin.user.user-form');
});

test('user form can create employee', function () {
    Livewire::actingAs($this->admin)->test(UserForm::class)->set('name', 'New Employee')->set('email', 'new.employee@example.com')->set('password', 'password123')->set('password_confirmation', 'password123')->set('user_type', 'PEGAWAI')->set('office_id', $this->office->id)->set('manager_id', $this->manager->id)->set('position', 'Developer')->set('department', 'IT')->set('employee_id', 'EMP001')->call('save')->assertRedirect(route('admin.users.index'));

    $this->assertDatabaseHas('users', [
        'name' => 'New Employee',
        'email' => 'new.employee@example.com',
        'user_type' => 'PEGAWAI',
        'office_id' => $this->office->id,
        'manager_id' => $this->manager->id,
        'position' => 'Developer',
        'department' => 'IT',
        'employee_id' => 'EMP001',
    ]);
});

test('user form can create manager', function () {
    // Create another office without manager
    $anotherOffice = Offices::factory()->create([
        'name' => 'Another Office',
        'is_active' => true,
    ]);

    Livewire::actingAs($this->admin)->test(UserForm::class)->set('name', 'New Manager')->set('email', 'new.manager@example.com')->set('password', 'password123')->set('password_confirmation', 'password123')->set('user_type', 'MANAGER')->set('office_id', $anotherOffice->id)->set('position', 'Manager')->set('department', 'Management')->set('employee_id', 'MGR001')->call('save')->assertRedirect(route('admin.users.index'));

    $this->assertDatabaseHas('users', [
        'name' => 'New Manager',
        'email' => 'new.manager@example.com',
        'user_type' => 'MANAGER',
        'office_id' => $anotherOffice->id,
        'position' => 'Manager',
        'department' => 'Management',
        'employee_id' => 'MGR001',
    ]);
});

test('user form can edit user', function () {
    $user = User::factory()->create([
        'name' => 'User to Edit',
        'email' => 'user.edit@example.com',
        'user_type' => 'PEGAWAI',
        'office_id' => $this->office->id,
        'manager_id' => $this->manager->id,
    ]);

    Livewire::actingAs($this->admin)
        ->test(UserForm::class, ['user' => $user])
        ->assertSet('name', 'User to Edit')
        ->assertSet('email', 'user.edit@example.com')
        ->assertSet('user_type', 'PEGAWAI')
        ->assertSet('office_id', $this->office->id)
        ->assertSet('manager_id', $this->manager->id)
        ->set('name', 'Updated User')
        ->set('email', 'updated.user@example.com')
        ->set('position', 'Senior Developer')
        ->call('save')
        ->assertRedirect(route('admin.users.index'));

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated User',
        'email' => 'updated.user@example.com',
        'position' => 'Senior Developer',
    ]);
});

test('user form validates required fields', function () {
    Livewire::actingAs($this->admin)
        ->test(UserForm::class)
        ->set('name', '')
        ->set('email', '')
        ->set('password', '')
        ->set('password_confirmation', '')
        ->call('save')
        ->assertHasErrors(['name', 'email', 'password']);
});

test('user form validates email format', function () {
    Livewire::actingAs($this->admin)
        ->test(UserForm::class)
        ->set('email', 'invalid-email')
        ->call('save')
        ->assertHasErrors(['email']);
});

test('user form validates password confirmation', function () {
    Livewire::actingAs($this->admin)
        ->test(UserForm::class)
        ->set('password', 'password123')
        ->set('password_confirmation', 'different-password')
        ->call('save')
        ->assertHasErrors(['password']);
});

test('user form validates unique email', function () {
    // User with this email already exists
    User::factory()->create(['email' => 'existing@example.com']);

    Livewire::actingAs($this->admin)
        ->test(UserForm::class)
        ->set('name', 'New User')
        ->set('email', 'existing@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('save')
        ->assertHasErrors(['email']);
});

test('user form validates unique employee id', function () {
    // User with this employee_id already exists
    User::factory()->create(['employee_id' => 'EMP123']);

    Livewire::actingAs($this->admin)
        ->test(UserForm::class)
        ->set('name', 'New User')
        ->set('email', 'new.user@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->set('employee_id', 'EMP123')
        ->call('save')
        ->assertHasErrors(['employee_id']);
});

test('user form prevents creating second manager for same office', function () {
    // Try to create another manager for the same office
    Livewire::actingAs($this->admin)->test(UserForm::class)->set('name', 'Second Manager')->set('email', 'second.manager@example.com')->set('password', 'password123')->set('password_confirmation', 'password123')->set('user_type', 'manager')->set('office_id', $this->office->id)->call('save');

    $this->assertDatabaseMissing('users', [
        'name' => 'Second Manager',
        'email' => 'second.manager@example.com',
    ]);
});