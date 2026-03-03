<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class TagService
{
    public function getAllPaginated()
    {
        return Tag::latest()->paginate(10);
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {

            $tag = Tag::create($data);

            DB::commit();

            return $tag;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating tag: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(Tag $tag, array $data)
    {
        DB::beginTransaction();
        try {

            $tag->update($data);

            DB::commit();

            return $tag;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating tag: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(Tag $tag)
    {
        DB::beginTransaction();
        try {

            $tag->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting tag: ' . $e->getMessage());
            throw $e;
        }
    }
}
