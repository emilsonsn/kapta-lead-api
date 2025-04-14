<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    protected $model = Link::class;

    public function definition(): array
    {
        return [
            'channel_id' => Channel::factory(),
            'user_id' => User::factory(),
            'description' => $this->faker->sentence,
            'destination_url' => $this->faker->url,
            'hash' => $this->faker->unique()->md5,
        ];
    }
}
