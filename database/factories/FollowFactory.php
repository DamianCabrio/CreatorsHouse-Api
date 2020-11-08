<?php

namespace Database\Factories;

use App\Models\Creator;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Follow::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::all()->random();
        $creator = Creator::where("idUser", "!=", $user->id)->get();
        return [
            'idUser' => $user,
            'idCreator' => $creator->random(),
            'isVip' => $this->faker->randomElement([true, false]),
        ];
    }
}
