<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function archiveNotification($notificationId)
    {
        DB::table('notifications')
            ->where('id', $notificationId)
            ->where('recipient_id', auth()->id())
            ->whereNull('deleted_at') // make sure it's not already archived
            ->update([
                'deleted_at' => now(),
                'recipient_read_at' => now(), // mark as read
            ]);
        return to_route('received.message')->with('status', __('پیام دریافتی با موفقیت بایگانی شد.'));
    }
}
