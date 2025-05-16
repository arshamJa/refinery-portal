<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $notificationData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $notificationData)
    {
        $this->notificationData = $notificationData;
    }
    /**
     * Execute the job.
     */
    public function handle()
    {
        Notification::create($this->notificationData);
    }
}
