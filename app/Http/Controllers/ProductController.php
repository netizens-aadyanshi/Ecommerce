<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only show active products to customers
        $products = Product::where('is_active', true)
            ->with('primaryImage') // Eager load for performance
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
        return view('products.create', compact('categories'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        // 1. Create the Product first
        $product = Product::create($request->validated());

        // 2. Handle Image Uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                // Store file in storage/app/public/products
                $path = $image->store('products', 'public');

                $product->images()->create([
                    'image_url' => $path,
                    // Make the first image primary by default if none specified
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
        $product->load(['images', 'category']);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // 1. Fetch all categories so the dropdown has options
        $categories = Category::all();

        // 2. Pass both the product AND the categories to the view
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        // Update the text details
        $product->update($request->validated());

        // Handle NEW images if uploaded during edit
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');

                // Check if product already has a primary image
                $hasPrimary = $product->images()->where('is_primary', true)->exists();

                $product->images()->create([
                    'image_url' => $path,
                    'is_primary' => !$hasPrimary // Make primary only if it's the very first image for this product
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
