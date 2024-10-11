<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    public function likePost(Post $post, String $user_id): bool
    {
        if($post->likes()->where('user_id', $user_id)->exists() == false)
        {
            $post->likes()->create([
                'user_id' => $user_id,
            ]);
            return true;
        }
        else
        {
            $post->likes()->where('user_id', $user_id)->delete();
            return false;
        }
    }

    public function likeComment(Comment $comment, String $user_id): bool
    {
        if($comment->likes()->where('user_id', $user_id)->exists() == true)
        {
            $comment->likes()->where('user_id', $user_id)->delete();
            return false;
        }
        else
        {
            $comment->likes()->create([
                'user_id' => $user_id,
            ]);
            return true;
        }
    }
}
