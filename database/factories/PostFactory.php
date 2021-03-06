<?php

namespace Database\Factories;

use App\Models\Creator;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $creator = Creator::all();
        return [
            'content' => $this->faker->paragraph(2),
            'date' => date("Y-m-d h:m:s"),
            'idCreator' => $creator->random(),
            "tipo" => $this->faker->randomElement([1, 2, 3]),
            "title" => $this->faker->sentence(6),
            "isPublic" => $this->faker->randomElement([true, false]),
        ];
    }
}
