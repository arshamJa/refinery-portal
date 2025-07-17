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
        return redirect()->route('received.message')
            ->with('status', 'پیام دریافتی با موفقیت بایگانی شد.');
    }
    public function restoreNotification($notificationId)
    {
        DB::table('notifications')
            ->where('id', $notificationId)
            ->where('recipient_id', auth()->id())
            ->whereNotNull('deleted_at') // only restore if it is archived
            ->update(['deleted_at' => null]);
        return redirect()->route('received.message')
            ->with('status', 'پیام دریافتی با موفقیت از بایگانی خارج شد.');
    }
}
