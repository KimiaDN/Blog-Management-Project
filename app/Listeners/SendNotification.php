<?php

namespace App\Listeners;

use App\Events\PostPublished;

use App\Models\User;
use App\Notifications\PostPublishNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendNotification implements ShouldQueue
{

    /**
     * Handle the event.
     */
    public function handle(PostPublished $event): void
    {
        $users = User::where('id', '!=', $event->post->user_id)->get();
        Notification::send($users, new PostPublishNotification($event->post));
    }
}
