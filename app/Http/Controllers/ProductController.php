<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {

        $products = $this->productService->getAllPaginated();

        return view('products.index', compact('products'));
    }

    public function adminIndex()
    {
        $products = $this->productService->getAllForAdmin();
        return view('products.adminIndex', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('products.create', compact('categories', 'tags'));

    }

    public function store(ProductRequest $request)
    {
        try {
            $product = $this->productService->storeProduct(
                $request->validated(),
                $request->input('tags', []),
                $request->file('images')
            );

            return redirect()->route('products.adminIndex')->with('success', 'Product and images uploaded!');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        try {
            $product = $this->productService->show($product);
            return view('products.show', compact('product'));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }

    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $product->load('tags');

        return view('products.edit', compact('product', 'categories', 'tags'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            $updatedProduct = $this->productService->updateProduct(
            $product,
            $request->validated(),
            $request->input('tags', []),
            $request->file('images')
        );

        return redirect()->route('products.adminIndex')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {

        if ($product->orderItems()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete product: It is linked to existing orders.');
        }

        try {
            $this->productService->destroy($product);
            return redirect()->route('products.adminIndex')->with('success', 'Product removed.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }

    }

    public function toggleActive(Product $product)
    {
        try {
            $this->productService->toggleActive($product);
            return back()->with('success', 'Product status updated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }


    }
}
