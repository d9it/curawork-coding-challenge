<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Connection>
 */
class ConnectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user_id = User::all()->count();
        $connection_id = User::all()->count();

        $user_connection = [];
        for ($i = 1; $i <= $user_id; $i++) {
            for ($j = 1; $j <= $connection_id; $j++) {
                if($i != $j){
                    array_push($user_connection, $i . "-" . $j);
                }
            }
        }
        $user_and_connection = $this->faker->unique->randomElement($user_connection);

        $user_and_connection = explode('-', $user_and_connection);
        $user_id = $user_and_connection[0];
        $connection_id = $user_and_connection[1];
        return [
            'user_id' => $user_id,
            'connection_id' => $connection_id,
        ];
    }
}
