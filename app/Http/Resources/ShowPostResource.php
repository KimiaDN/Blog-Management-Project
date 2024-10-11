<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $comments = Comment::where('post_id', $this->id)->with('user', 'likes')->paginate(2);
        return [
            'title' => $this->title,
            'author' => $this->user->name, 
            'body' => $this->body,
            'tags' => $this->tags->pluck('name'),
            'created_at' => $this->created_at->format('Y-m-d'),
            'likes' => count($this->likes),
            'comments' => CommentResource::collection($comments),
            'comments_pagination' => [
                'total' => $comments->total(),
                'next_page_url' => $comments->nextPageUrl(),
                'prev_page_url' => $comments->previousPageUrl(),
            ]
        ];
    }
}
