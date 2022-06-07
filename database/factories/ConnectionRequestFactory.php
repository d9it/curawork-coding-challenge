<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConnectionRequest>
 */
class ConnectionRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $request_from = User::all()->count();
        $request_to = User::all()->count();

        $user_connection = [];
        for ($i = 1; $i <= $request_from; $i++) {
            for ($j = 1; $j <= $request_to; $j++) {
                if ($i != $j) {
                    array_push($user_connection, $i . "-" . $j);
                }
            }
        }
        $user_and_connection = $this->faker->unique->randomElement($user_connection);

        $user_and_connection = explode('-', $user_and_connection);
        $request_from = $user_and_connection[0];
        $request_to = $user_and_connection[1];
        return [
            'request_from' => $request_from,
            'request_to' => $request_to,
        ];
    }
}
