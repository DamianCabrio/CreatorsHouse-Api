<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $post = Post::all()->random();
        return [
            'image' => $this->faker->randomElement(["https://www.placecage.com/200/300", "https://www.placecage.com/g/200/300", "https://www.placecage.com/c/200/300", "https://www.placecage.com/gif/200/300"]),
            'idPost' => $post,
        ];
    }
}
