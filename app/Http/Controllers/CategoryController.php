<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAllPaginated();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(CategoryRequest $request)
    {
        try {
            $this->categoryService->create($request->validated());

            return redirect()->route('categories.index')
                ->with('success', 'Category created successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function show(Category $category)
    {
        try {
            $data = $this->categoryService->getCategoryDetails($category);

            return view('categories.adminShow', $data);

        } catch (\Exception $e) {
            return back()->with('error', 'Unable to load category details.');
        }
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $this->categoryService->update($category, $request->validated());

            return redirect()->route('categories.index')
                ->with('success', 'Category updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Update failed.');
        }
    }

    public function destroy(Category $category)
    {
        try {
            $this->categoryService->delete($category);

            return redirect()->route('categories.index')
                ->with('success', 'Category deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->route('categories.index')
                ->with('error', $e->getMessage());
        }
    }
}
