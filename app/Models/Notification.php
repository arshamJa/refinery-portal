<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $fillable = [
        'type', 'data', 'notifiable_type', 'notifiable_id', 'sender_id', 'recipient_id', 'status',
        'sender_read_at','recipient_read_at'
    ];

    // Mark as read for sender
    public function markAsReadBySender()
    {
        $this->update(['sender_read_at' => now()]);
    }

    // Mark as read for recipient
    public function markAsReadByRecipient()
    {
        $this->update(['recipient_read_at' => now()]);
    }

    // Check if the notification is read for sender
    public function isReadBySender()
    {
        return !is_null($this->sender_read_at);
    }

    // Check if the notification is read for recipient
    public function isReadByRecipient()
    {
        return !is_null($this->recipient_read_at);
    }

    // Check if the notification is read by the authenticated user
    public function isReadByCurrentUser()
    {
        $userId = auth()->id();
        if ($this->sender_id === $userId) {
            return $this->isReadBySender();
        } elseif ($this->recipient_id === $userId) {
            return $this->isReadByRecipient();
        }
        return false;
    }
    public static function hasUnreadForCurrentUser()
    {
        $userId = auth()->id();
        if (!$userId) {
            return false;
        }

        return self::where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->orWhere('recipient_id', $userId);
        })
            ->where(function ($query) use ($userId) {
                $query->where(function ($q) use ($userId) {
                    // Unread if current user is sender and sender_read_at is null
                    $q->where('sender_id', $userId)
                        ->whereNull('sender_read_at');
                })
                    ->orWhere(function ($q) use ($userId) {
                        // Or unread if current user is recipient and recipient_read_at is null
                        $q->where('recipient_id', $userId)
                            ->whereNull('recipient_read_at');
                    });
            })
            ->exists();
    }



    /**
     * Polymorphic relationship to related models (Meeting, Task, etc.)
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relationship to the user who sent the notification.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relationship to the user who received the notification.
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
