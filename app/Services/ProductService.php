<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ProductService
{
    public function getAllPaginated()
    {
        return Product::where('is_active', true)
            ->with('primaryImage')
            ->latest()
            ->paginate(12);
    }

    public function getAllForAdmin()
    {
        return Product::latest()->paginate(10);
    }

    public function storeProduct($validatedData, $tags, $images)
    {
        DB::beginTransaction();

        try {
            // 1. Create the Product first
            $product = Product::create($validatedData);
            $product->tags()->sync($tags);

            // 2. Handle Image Uploads
            if ($images) {
                foreach ($images as $index => $image) {
                    // Store file in storage/app/public/products
                    $path = $image->store('products', 'public');

                    $product->images()->create([
                        'image_url' => $path,
                        'is_primary' => ($index === 0)
                    ]);
                }
            }

            DB::commit();
            return $product;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating product: ' . $e->getMessage());
            throw new Exception('Failed to create product.');
        }
    }

    public function updateProduct(Product $product, $validatedData, $tags, $images)
    {
        DB::beginTransaction();

        try {
            // 1. Update the Product
            $product->update($validatedData);
            $product->tags()->sync($tags);

            // 2. Handle Image Uploads (if new images are provided)
            if ($images) {
            // Check if a primary already exists
            $hasPrimary = $product->images()->where('is_primary', true)->exists();

            foreach ($images as $index => $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'image_url' => $path,
                    'is_primary' => ($index === 0 && !$hasPrimary) // Only make primary if none exist
                ]);
            }
        }

            DB::commit();
            return $product;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating product: ' . $e->getMessage());
            throw new Exception('Failed to update product.');
        }
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();

        try {
            $product->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting product: ' . $e->getMessage());
            throw new Exception('Failed to delete product.');
        }
    }

    public function toggleActive(Product $product)
    {
        DB::beginTransaction();

        try {
            $product->is_active = !$product->is_active;
            $product->save();

            DB::commit();
            return $product;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error toggling product active status: ' . $e->getMessage());
            throw new Exception('Failed to toggle product active status.');
        }
    }

    public function show(Product $product)
    {
        // Ensure the product is active before showing
        if (!$product->is_active) {
            throw new Exception('Product is not active.');
        }

        // Load images and category
        $product->load(['tags','images', 'category', 'reviews', 'reviews.user']);

        return $product;
    }

}
