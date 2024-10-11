<?php

namespace App\Jobs;

use App\Events\PostPublished;
use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PublishPostJob implements ShouldQueue
{
    use Queueable;
    protected $post;
    /**
     * Create a new job instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->post->update([
            'is_published' => true,
        ]);
        PostPublished::dispatch($this->post);
    }
}
