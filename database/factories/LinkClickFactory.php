<?php

namespace Database\Factories;

use App\Models\Link;
use App\Models\LinkClick;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkClickFactory extends Factory
{
    protected $model = LinkClick::class;

    public function definition(): array
    {
        return [
            'link_id' => Link::factory(),
            'ip' => $this->faker->ipv4,
            'city' => $this->faker->city,
            'region' => $this->faker->state,
            'country' => $this->faker->country,
        ];
    }
}
