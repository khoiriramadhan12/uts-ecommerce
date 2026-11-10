<?php

namespace App\Modules\Customer\DTO;

final readonly class CustomerSnapshot
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $fullName,
        public ?string $phone,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'full_name' => $this->fullName,
            'phone' => $this->phone,
        ];
    }
}
