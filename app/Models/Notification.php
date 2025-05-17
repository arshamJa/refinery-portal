<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $fillable = [
        'type', 'data', 'notifiable_type', 'notifiable_id', 'sender_id', 'recipient_id', 'status'
    ];


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
