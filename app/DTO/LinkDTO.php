<?php

namespace App\DTO;

class LinkDTO
{
    public function __construct(
        public readonly int $channel_id,
        public readonly string $description,
        public readonly string $destination_url,
        public readonly ?string $hash = null,
    ) {}
}
