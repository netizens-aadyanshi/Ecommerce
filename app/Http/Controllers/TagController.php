<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use App\Services\TagService;

class TagController extends Controller
{

    public function __construct(protected TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function index()
    {
        $tags = $this->tagService->getAllPaginated();
        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        return view('tags.create');
    }

    public function store(TagRequest $request)
    {
        try {
            $this->tagService->create($request->validated());


            return redirect()->route('tags.index')
                ->with('success', 'Tag created successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function show(Tag $tag)
    {
        return view('tags.show', compact('tag'));
    }

    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    public function update(TagRequest $request, Tag $tag)
    {
        try {
            $this->tagService->update($tag, $request->validated());

            return redirect()->route('tags.index')
                ->with('success', 'Tag updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong.');
        }
    }

    public function destroy(Tag $tag)
    {
        if ($tag->posts()->exists()) {
            return back()->with('error', 'Cannot delete tag with associated posts.');
        }

        try {
            $this->tagService->delete($tag);

            return redirect()->route('tags.index')
                ->with('success', 'Tag deleted successfully.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
