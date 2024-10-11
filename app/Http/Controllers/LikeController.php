<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LikeController
{
    public function store(LikeRequest $request, Like $like_model): JsonResponse
    {
        $user_id = Auth::id();
        if($request->has('comment_id'))
        {
            $comment = Comment::find($request->string('comment_id'));
            $comment_result = $like_model->likeComment($comment, $user_id);
            if($comment_result == true)
            {
                return response()->json(['message' => 'Comment liked successfully'], 200);
            }
            else
            {
                return response()->json(['message' => 'Comment unliked successfully'], 200);
            }
        }

        if($request->has('post_id'))
        {
            $post = Post::find($request->string('post_id'));
            if($post->is_published == false)
            {
                return response()->json(['message' => 'Post is not published yet'], 403);
            }
            $like_result = $like_model->likePost($post, $user_id);
            if($like_result == true)
            {
                return response()->json(['message' => 'Post liked successfully'], 200);
            }
            else
            {
                return response()->json(['message' => 'Post unliked successfully'], 200);
            }           
        }

        return response()->json(['message' => 'Bad input'], 422);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $post_id): JsonResponse
    {
        $post = Post::find($post_id);
        if($post == null)
        {
            return response()->json(['message' => 'post no found'], 404);
        }
        if($post->is_published == false)
        {
            return response()->json(['message' => 'post is not published yet'], 403);
        }
        $users_name = $post->likes()->with('user')->get()->pluck('user.name');
        return response()->json(['users' => $users_name], 200);
    }
}
