<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductImageRequest;
use App\Http\Requests\UpdateProductImageRequest;
use App\Models\ProductImage;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // return view('product-images.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductImageRequest $request)
    {
        //
        // ProductImage::create($request->validated());
        // return redirect()->route('products.adminIndex')->with('success', 'Product image uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductImage $productImage)
    {
        //
        // return view('product-images.show', compact('productImage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductImage $productImage)
    {
        //
        // return view('product-images.edit', compact('productImage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductImageRequest $request, ProductImage $productImage)
    {
        //
        // $productImage->update($request->validated());
        // return redirect()->route('products.adminIndex')->with('success', 'Product image updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductImage $productImage)
    {
        // 1. Delete the physical file from storage
        \Storage::disk('public')->delete($productImage->image_url);

        // 2. Delete the database record
        $productImage->delete();

        return back()->with('success', 'Image removed.');
    }

    public function setPrimary(ProductImage $productImage)
    {
        // 1. Set ALL images for THIS product to is_primary = false
        ProductImage::where('product_id', $productImage->product_id)
                    ->update(['is_primary' => false]);

        // 2. Set the chosen image to true
        $productImage->update(['is_primary' => true]);

        return back()->with('success', 'Primary image updated.');
    }
}
