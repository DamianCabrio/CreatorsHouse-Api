<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $post = Post::all()->random();
        return [
            'video' => $this->faker->randomElement(["https://www.youtube.com/watch?v=qzPvx8VUSDw", "https://www.youtube.com/watch?v=hvALj6qFL1E"]),
            'idPost' => $post,
        ];
    }
}
