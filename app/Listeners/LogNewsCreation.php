<?php

namespace App\Listeners;

use App\Events\NewsCreated;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogNewsCreation
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
    public function handle(NewsCreated $event): void
    {
        Log::create([
            'news_id' => $event->news->id,
            'user_id' => Auth::id(),
            'action'  => 'created'
        ]);
    }
}
