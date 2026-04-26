<?php

namespace App\Actions\Products;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CreateProductAction
{
    public function handle(array $data, ?UploadedFile $image): Product
    {
        $data['slug'] ??= Str::slug($data['name']);

        if ($image) {
            $data['image'] = $image->store('products', 'public');
        }

        return Product::create($data);
    }
}
