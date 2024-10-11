<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\PublishRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ShowPostResource;
use App\Jobs\PublishPostJob;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Sanctum\PersonalAccessToken;

class PostController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Post $post_model): AnonymousResourceCollection
    {
        $access_token = PersonalAccessToken::findToken($request->bearerToken());
        if($access_token != null) // when a logedin user want to see posts
        {
            $user = $access_token->tokenable;
            $posts = $post_model->readPostsByUser($user);
        }
        else 
        {
            $posts = Post::where('is_published', 1)
            ->with(['tags', 'likes', 'user'])->paginate(5);
        }
        
        return $post_model->displayPosts($posts);
    }

    public function store(CreatePostRequest $request): JsonResponse
    {
        //1.store post in posts table       
        $post = Post::create([
            'title' => $request->string('title'),
            'body' => $request->string('body'),
            'user_id' => Auth::id(),
        ]);

        //2.store tags and posts_tag relationships
        $this->createPostTags($request->input('tags'), $post->id);
        return response()->json(['message' => 'post created successfully'], 200);       
    }

    public function publish(PublishRequest $request, Post $post_model, string $id): JsonResponse
    {
        $post = Post::find($id);
        if($post == null)
        {
            return response()->json(['message' => 'post not found'], 404);
        }
        if($post->is_published == true or $post_model->findPostJob($post->id) != null)
        {
            return response()->json(['message' => 'post is already published'], 403);
        }
        if(Gate::inspect('publish', $post)->allowed())
        {            
            if(Gate::inspect('rateLimit', $post)->allowed())
            {
                $date = Carbon::parse($request->string('date'));
                PublishPostJob::dispatch($post)->delay($date);
                return response()->json(['message' => 'post published successfully'], 200);
            }
            else
            {
                $seconds = RateLimiter::availableIn('post-publish:'.Auth::id());
                $hours = floor($seconds/3600);
                return response()->json(['message' => 'you exceed your publish limitation rate, try again after '.$hours . ' hours'], 429);
            }            
        }
        return response()->json(['message' => 'You are not allowed to publish this post'], 403);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $post_id): AnonymousResourceCollection
    {
        $post = Post::where('id', $post_id)
            ->with(['likes', 'tags', 'user'])
            ->get();

        if($post->isEmpty() == true){
            return response()->json(['message' => 'post not found, author has deleted this post'], 404);
        }

        return ShowPostResource::collection($post);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post_model, string $post_id): JsonResponse
    {
        $post = Post::find($post_id);
        if($post == null)
        {
            return response()->json(['message' => 'post not found'], 404);
        }
        if(Gate::inspect('update', $post)->allowed())
        {
            //update post's information
            $post->update([
                'title' => $request->string('title'),
                'body' => $request->string('body'),
            ]);
            // update post tags
            $this->createPostTags($request['tags'], $post_id);
            $this->deletePostFromQueue($post, $post_model);
            return response()->json(['message' => 'post edited successfully'], 200);
        }
        return response()->json(['message' => 'You are not allowed to edit this post'], 403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Post $post_model): JsonResponse
    {
        $post = Post::find($id);
        if($post == null)
        {
            return response()->json(['message' => 'post not found'], 404);
        }
        
        if(Gate::inspect('update', $post)->allowed())
        {
            $post->delete();
            $this->deletePostFromQueue($post, $post_model);
            return response()->json(['message' => 'post deleted successfully'], 200);
        }
        else
        {
            return response()->json(['message' => 'you are not allow to delete this post'], 403);
        }
    }

    public function deletePostFromQueue(Post $post, Post $post_model): void
    {
        if($post->is_published == false)
        {
            $post_job = $post_model->findPostJob($post->id);
            if($post_job != null)   // if there is a job for post
            {
                DB::table('jobs')->delete($post_job->id);
            }                
        } 
    }

    protected function createPostTags(array $tags_array, int $post_id): void
    {
        $tags_id =[];
        foreach($tags_array as $tag_name)
        {
            // find tags or create them if they don't exist
            $tag = Tag::firstOrCreate(['name' => $tag_name]);
            $tags_id[] = $tag->id;
        }

        // find the corresponding post and sync tags with post
        $post = Post::find($post_id);
        $post->tags()->sync($tags_id);
    }
}
