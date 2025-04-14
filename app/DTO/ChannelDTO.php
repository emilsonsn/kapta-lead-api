<?php

namespace App\DTO;

class ChannelDTO
{
    public function __construct(
        public string $name,
        public ?string $description,
        public ?string $image,
        public int $user_id,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            image: $data['image'] ?? null,
            user_id: $data['user_id'],
        );
    }
}
