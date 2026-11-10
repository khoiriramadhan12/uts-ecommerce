<?php

namespace App\Modules\Customer\DTO;

final readonly class AddressSnapshot
{
    public function __construct(
        public int $id,
        public int $customerId,
        public string $label,
        public string $recipientName,
        public string $phone,
        public string $line1,
        public ?string $line2,
        public string $city,
        public string $province,
        public string $postalCode,
        public bool $isDefault = false,
    ) {}

    public function fullAddress(): string
    {
        return collect([$this->line1, $this->line2, $this->city, $this->province, $this->postalCode])
            ->filter()
            ->implode(', ');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customerId,
            'label' => $this->label,
            'recipient_name' => $this->recipientName,
            'phone' => $this->phone,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'city' => $this->city,
            'province' => $this->province,
            'postal_code' => $this->postalCode,
            'full_address' => $this->fullAddress(),
            'is_default' => $this->isDefault,
        ];
    }
}
