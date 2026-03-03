<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CategoryService
{
    public function getAllPaginated()
    {
        return Category::latest()->paginate(10);
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $category = Category::create($data);

            DB::commit();
            return $category;

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Category Create Failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            throw $e;
        }
    }

    public function update(Category $category, array $data)
    {
        DB::beginTransaction();

        try {
            $category->update($data);

            DB::commit();
            return $category;

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Category Update Failed', [
                'error' => $e->getMessage(),
                'category_id' => $category->id
            ]);

            throw $e;
        }
    }

    public function delete(Category $category)
    {
        DB::beginTransaction();

        try {
            if ($category->products()->exists()) {
                throw new Exception("Category contains active products.");
            }

            $category->delete();

            DB::commit();

            return true;

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Category Delete Failed', [
                'error' => $e->getMessage(),
                'category_id' => $category->id
            ]);

            throw $e;
        }
    }

    public function getCategoryDetails(Category $category)
    {
        try {
            $allCategories = Category::orderBy('name')->get();

            $totalReviews = $category->reviews()->count();
            $avgRating = $category->reviews()->avg('rating') ?? 0;

            $recentReviews = $category->reviews()
                ->with(['product', 'user'])
                ->latest()
                ->paginate(10);

            return [
                'category' => $category,
                'allCategories' => $allCategories,
                'totalReviews' => $totalReviews,
                'avgRating' => $avgRating,
                'recentReviews' => $recentReviews
            ];

        } catch (Exception $e) {
            Log::error('Category Details Fetch Failed', [
                'error' => $e->getMessage(),
                'category_id' => $category->id
            ]);

            throw $e;
        }
    }
}
