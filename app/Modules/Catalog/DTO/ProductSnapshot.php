<?php

namespace App\Modules\Catalog\DTO;

final readonly class ProductSnapshot
{
    public function __construct(
        public int $id,
        public string $name,
        public string $sku,
        public float $price,
        public int $stock,
        public bool $isActive,
        public string $categoryName = '',
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => $this->price,
            'stock' => $this->stock,
            'is_active' => $this->isActive,
            'category_name' => $this->categoryName,
        ];
    }
}
