<?php

namespace App\DTO;

class UserDto
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone,
        public ?string $cpf_cnpj,
        public ?string $role,
        public ?string $photo,
        public string $password
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'] ?? null,
            cpf_cnpj: $data['cpf_cnpj'] ?? null,
            role: $data['role'] ?? null,
            photo: $data['photo'] ?? null,
            password: $data['password'],
        );
    }
}
