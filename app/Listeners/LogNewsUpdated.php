<?php

namespace App\Listeners;

use App\Events\NewsCreated;
use App\Events\NewsUpdated;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogNewsUpdated
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
    public function handle(NewsUpdated $event): void
    {
        Log::create([
            'news_id' => $event->news->id,
            'user_id' => Auth::id(),
            'action'  => 'updated'
        ]);
    }
}
