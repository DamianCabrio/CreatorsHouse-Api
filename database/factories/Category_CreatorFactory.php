<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Creator;
use Illuminate\Database\Eloquent\Factories\Factory;

class Category_CreatorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category_Creator::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $creator = Creator::all();
        $category = Category::all();
        return [
            'category_id' => $category->random(),
            'creator_id' => $creator->random(),
        ];
    }
}
