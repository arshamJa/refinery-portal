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

    public function sender():BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient():BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function notifiable():MorphTo
    {
        return $this->morphTo();
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
    public function senderInfo():BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id')->withDefault()->with('user_info');
    }
}
