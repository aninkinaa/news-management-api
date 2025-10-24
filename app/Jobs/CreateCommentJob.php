<?php

namespace App\Jobs;

use App\Models\News;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class CreateCommentJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $news;
    protected $user;
    protected $content;

    public function __construct( News $news, User $user, string $content)
    {
        $this->news = $news;
        $this->user = $user;
        $this->content = $content;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->news->comments()->create([
            'content' => $this->content,
            'user_id' => $this->user->id,
        ]);
    }
}
