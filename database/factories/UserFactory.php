<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'user_type' => fake()->randomElement(['PEGAWAI', 'ADMIN', 'MANAGER']),
            'position' => fake()->jobTitle(),
            'employee_id' => 'EMP' . fake()->unique()->randomNumber(5),
            'password' => static::$password ??= Hash::make('password'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
            'profile_photo_path' => null,
            'current_team_id' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(?callable $callback = null): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name.'\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedTeams'
        );
    }

     /**
     * Configure the model as an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'ADMIN',
        ]);
    }

    /**
     * Configure the model as a manager.
     */
    public function manager(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'MANAGER',
        ]);
    }

    /**
     * Configure the model as an employee.
     */
    public function employee(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'PEGAWAI',
        ]);
    }

    /**
     * Assign the user to an office.
     */
    public function inOffice(Office $office): static
    {
        return $this->state(fn (array $attributes) => [
            'office_id' => $office->id,
        ]);
    }

    /**
     * Assign a manager to the user.
     */
    public function withManager(User $manager): static
    {
        return $this->state(fn (array $attributes) => [
            'manager_id' => $manager->id,
            'office_id' => $manager->office_id,
        ]);
    }
}