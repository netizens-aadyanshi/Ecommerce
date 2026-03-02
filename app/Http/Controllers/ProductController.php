<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $products = Product::where('is_active', true)
            ->with('primaryImage')
            ->latest()
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    // For Admins: Show the management table
    public function adminIndex()
    {
        $products = Product::latest()->paginate(10);
        return view('products.adminIndex', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('products.create', compact('categories', 'tags'));

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        // 1. Create the Product first
        $product = Product::create($request->validated());
        $product->tags()->sync($request->input('tags', []));
        // 2. Handle Image Uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                // Store file in storage/app/public/products
                $path = $image->store('products', 'public');

                $product->images()->create([
                    'image_url' => $path,
                    'is_primary' => ($index === 0)
                ]);
            }
        }

        return redirect()->route('products.adminIndex')->with('success', 'Product and images uploaded!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Ensure the product is active before showing
        if (!$product->is_active) {
            abort(404);
        }

        // Load images and category
        $product->load(['tags','images', 'category', 'reviews', 'reviews.user']);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $product->load('tags');

        return view('products.edit', compact('product', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        // 1. Get all validated data from your ProductRequest
        $validated = $request->validated();

        // 2. Sync the many-to-many Tags
        // We use $request->tags ?? [] to handle the case where all checkboxes are unchecked
        $product->tags()->sync($request->tags ?? []);

        // 3. Prepare product data by removing 'images' and 'tags'
        // so they don't try to save into the 'products' table columns
        $productData = collect($validated)->except(['images', 'tags'])->toArray();

        // 4. Update Name, Price, Stock, etc.
        $product->update($productData);

        // 5. Handle NEW image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {

                // Logic: Only make primary if the product currently has NO primary image
                // and this is the first image in the current upload batch ($key === 0)
                $isPrimary = false;
                if ($key === 0 && !$product->images()->where('is_primary', true)->exists()) {
                    $isPrimary = true;
                }

                $path = $image->store('products', 'public');

                $product->images()->create([
                    'image_url' => $path,
                    'is_primary' => $isPrimary
                ]);
            }
        }

        return redirect()->route('products.adminIndex')->with('success', 'Product updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // check the relationship defined in your Product model
        if ($product->orderItems()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete product: It is linked to existing orders.');
        }

        $product->delete();
        return redirect()->route('products.adminIndex')->with('success', 'Product removed.');
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        return back()->with('success', 'Product status updated.');
    }
}
