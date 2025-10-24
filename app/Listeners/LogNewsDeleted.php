<?php

namespace App\Listeners;

use App\Events\NewsDeleted;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogNewsDeleted
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewsDeleted $event): void
    {
        Log::create([
            'news_id' => $event->news->id,
            'user_id' => Auth::id(),
            'action'  => 'deleted'
        ]);
    }
}
