<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->unique()->word,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'email' => $this->faker->unique()->safeEmail,
            "isCreator" => $this->faker->randomElement([0,1]),
            "avatar" => $this->faker->randomElement(["ElSaurio.jpg","Norbert.jpg","Laura.jpg"]),
            "birthDate" => $this->faker->date("Y-m-d","2002-10-19"),
            "name" => $this->faker->firstName(),
            "surname" => $this->faker->lastName,
            "dni" => $this->faker->randomNumber(8),
            "isAdmin" => $this->faker->randomElement([0,1]),
            "isVerified" => $isVerified = $this->faker->randomElement([0,1]),
            "verificationToken" => $isVerified == 1 ? null : User::generateVerificationCode(),
            'remember_token' => Str::random(10),
        ];
    }
}
