<?php

namespace Database\Factories;

use App\Models\Creator;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CreatorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Creator::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $usersCreator = User::where("isCreator", 1)->get();

        return [
            'banner' => $this->faker->unique()->word,
            'description' => $this->faker->paragraph(2),
            'instagram' => $this->faker->randomElement(["https://www.instagram.com/lau.muri/", "https://www.instagram.com/damian.cabr/", "https://www.instagram.com/n0rb3rt/"]),
            "youtube" => $this->faker->randomElement(["https://www.youtube.com/channel/UC3kKUVzWcPY9ExjNXzFPBUw", "https://www.youtube.com/c/ElTobaoficial", "https://www.youtube.com/c/FitDanceLife"]),
            "costVip" => $this->faker->randomNumber([100, 200, 150]),
            "emailMercadoPago" => $this->faker->unique()->safeEmail,
            //"idUser" => $this->faker->randomNumber([1, 2, 3, 4, 5]),
            "idUser" => $usersCreator->random(),
        ];
    }
}
