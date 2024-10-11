<?php

namespace App\Http\Resources;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'author' => $this->user->name,
            'body' => $this->body,
            'tags' => $this->tags->pluck('name'),
            'created_at' => $this->created_at->format('Y-m-d'),
            'likes' => count($this->likes),
            'comments' => CommentResource::collection($this->comments->take(2)),
        ];
    }
}
