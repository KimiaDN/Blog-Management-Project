<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class TagController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tags = Tag::withCount('posts')->get()->map(function(object $items)
        {
            return [$items->name => $items->posts_count];
        });

        return response()->json(['tags' => $tags], 200);

    }

}
