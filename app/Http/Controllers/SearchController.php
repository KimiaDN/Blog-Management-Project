<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Post $post_model): JsonResponse
    {
        $search_text = $request->input('search_text');

        // full-text-search in posts
        $posts_search_result = Post::select('id', 'user_id', 'title', 'body', 'created_at')
        ->whereraw("MATCH(posts.title, posts.body) AGAINST(? IN BOOLEAN MODE)", [$search_text])
        ->where('is_published', true)
        ->with(['likes', 'tags', 'user'])
        ->get();

        // search is users name
        $users_search_result = User::where('name', 'LIKE', "%{$search_text}%")->pluck('id');
        $pots_search_by_author_name = Post::whereIn('user_id', $users_search_result)
        ->where('is_published', true)
        ->with(['likes', 'tags', 'user', 'comments'])
        ->get();

        // combination of results
        $combined_search_result = $posts_search_result->merge($pots_search_by_author_name);

        
        if($combined_search_result->isEmpty())
        {
            return response()->json(['message' => 'no results match'], 200); 
        }
        $posts_array = $post_model->displayPosts($combined_search_result);

        return response()->json($posts_array, 200); 
    }
}
