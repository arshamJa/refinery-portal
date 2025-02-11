<?php

namespace App\Models;

use App\MeetingStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Meeting extends Model
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'title',
        'unit_organization',
        'scriptorium',
        'location',
        'date',
        'time',
        'unit_held',
        'treat',
        'guest',
        'applicant',
        'position_organization',
        'signature',
        'reminder',
        'is_cancelled'
    ];

//    protected $casts = [
//        'is_cancelled' => MeetingStatus::class
//    ];
    protected function guest(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value,true),
            set: fn($value) => json_encode($value)
        );
    }
//    public function users(): BelongsToMany
//    {
//        return $this->belongsToMany(User::class);
//    }
    public function meetingUsers():HasMany
    {
        return $this->hasMany(MeetingUser::class)->chaperone();
    }
    public function tasks():HasMany
    {
        return $this->hasMany(Task::class)->chaperone();
    }
}
