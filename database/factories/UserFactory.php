<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
        $emailVerified = $this->faker->boolean(80);
        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->unique()->phoneNumber(),
            'role' => $this->faker->randomElement(['farmer', 'worker', 'driver']),
            'profile_picture' => null,
            'is_active' => true,
            'email_verified' => $emailVerified,
            'phone_verified' => $this->faker->boolean(50),
            'password' => static::$password ??= Hash::make('password'),
        ];

        if (Schema::hasColumn('users', 'email_verified_at')) {
            $data['email_verified_at'] = $emailVerified ? now() : null;
        }

        if (Schema::hasColumn('users', 'remember_token')) {
            $data['remember_token'] = Str::random(10);
        }

        return $data;
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            $state = [
                'email_verified' => false,
            ];

            if (Schema::hasColumn('users', 'email_verified_at')) {
                $state['email_verified_at'] = null;
            }

            return $state;
        });
    }

    /**
     * Admin user state.
     */
    public function admin(): static
    {
        return $this->state(function (array $attributes) {
            $state = [
                'role' => 'admin',
                'email_verified' => true,
                'is_active' => true,
            ];

            if (Schema::hasColumn('users', 'email_verified_at')) {
                $state['email_verified_at'] = now();
            }

            return $state;
        });
    }
}
