<?php

namespace App\Actions\Products;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateProductAction
{
    public function handle(Product $product, array $data, ?UploadedFile $image): Product
    {
        $data['slug'] ??= Str::slug($data['name']);

        if ($image) {
            // Verwijder de oude afbeelding als die bestaat
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $image->store('products', 'public');
        }

        $product->update($data);

        return $product->fresh();
    }
}
