<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

  /**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OfficesFactory extends Factory
{
      /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'            => fake()->company() . ' Office',
            'address'         => fake()->streetAddress(),
            'city'            => fake()->city(),
            'state'           => fake()->state(),
            'postal_code'     => fake()->postcode(),
            'country'         => fake()->country(),
            'latitude'        => fake()->latitude(),
            'longitude'       => fake()->longitude(),
            'check_in_radius' => fake()->numberBetween(50, 500),
            'description'     => fake()->paragraph(),
            'is_active'       => true,
        ];
    }

      /**
     * Indicate that the office is inactive.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }
}