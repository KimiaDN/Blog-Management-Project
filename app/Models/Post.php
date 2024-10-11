<?php

namespace App\Models;

use App\Http\Resources\IndexPostResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'user_id',
        'is_published',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function readWeeklyPosts(String $start_date, String $end_date): Collection
    {
        $posts = Post::where('created_at', '>=', $start_date)
                ->where('created_at', '<', $end_date)
                ->with(['tags', 'likes'])
                ->get();
                
        return $posts;
    }

    public function readPostsByUser(User $user): LengthAwarePaginator
    {
        if($user->role == 'admin')
        {
            $posts = Post::with(['tags', 'likes', 'user'])->paginate(5); 
        }
        else
        {
            $posts = Post::where('is_published', true)
                ->orWhere(function(Builder $query) use ($user)
                {
                    $query->where('is_published', false)->where('user_id', $user->id);

                })->with(['tags', 'likes', 'user'])->paginate(5); 
        }
        return $posts;
    }

    public function findPostJob(String $post_id): object|null
    {
        $job = DB::table('jobs')->where('payload', 'like', '%Post\\\";s:2:\\\"id\\\";i:'.$post_id.';s:9:\\\%')->first();
        return $job;
    }
    
    public function displayPosts($posts): AnonymousResourceCollection
    {
        return IndexPostResource::collection($posts);
    }
}
