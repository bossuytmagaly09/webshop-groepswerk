<?php

namespace App\Actions\Products;

use App\Models\Product;

class CreateProductAction
{
    /**
     * Handelt de business logic af voor het aanmaken van een product.
     */
    public function execute(array $data): Product
    {
        // Implementatie volgt later - Stub voor architectuur fase 1
        return Product::create($data);
    }
}
