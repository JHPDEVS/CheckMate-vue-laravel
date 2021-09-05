<?php

namespace Database\Factories;

use App\Models\Attend_posts;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class Attend_postsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attend_posts::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {   
        return [
            'user_id' => User::factory(),
            'image' => null,
            'content' => 'test',
            'flag' => 0,
            'run' => 10,
        ];
    }
}
