<?php

namespace App\Http\Controllers;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request, $id): JsonResponse
    {
        $post = Post::find($id);
        if($post == null)
        {
            return response()->json(['message' => 'post not found'], 404);
        }
        if($post->is_published == false)
        {
            return response()->json(['message' => 'post is not published yet'], 403);
        }
        Comment::create([
            'body' => $request->string('body'),
            'post_id' => $post->id,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'comment set succesfully'], 200);        
    }

}
