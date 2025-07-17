<?php

namespace App\Models;

use App\Enums\MeetingStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Meeting extends Model
{
    use HasFactory,Notifiable,SoftDeletes;


    protected $fillable = [
        'title',
        'scriptorium_id',
        'boss_id',
        'location',
        'date',
        'time',
        'end_time',
        'unit_held',
        'treat',
        'guest',
        'status'
    ];

    protected $casts = [
        'guest' => 'array',
        'status' => MeetingStatus::class
    ];
    protected function guest(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value,true),
            set: fn($value) => json_encode($value)
        );
    }
    public function meetingUsers():HasMany
    {
        return $this->hasMany(MeetingUser::class)->chaperone();
    }
    public function tasks():HasMany
    {
        return $this->hasMany(Task::class)->chaperone();
    }

    // Scope for Scriptorium Report
    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('date', [$start, $end]);
    }
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('location', 'like', "%{$term}%")
                ->orWhere('date', 'like', "%{$term}%")
                ->orWhere('time', 'like', "%{$term}%")
                ->orWhere('unit_held', 'like', "%{$term}%")
                ->orWhere('guest', 'like', "%{$term}%")
                ->orWhere('applicant', 'like', "%{$term}%");
        })
            ->orWhereHas('scriptorium.user_info', function ($q) use ($term) {
                $q->where('full_name', 'like', "%{$term}%")
                    ->orWhere('position', 'like', "%{$term}%")
                    ->orWhereHas('department', function ($q2) use ($term) {
                        $q2->where('department_name', 'like', "%{$term}%");
                    });
            });
    }
    // End of  Scope for Scriptorium Report

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
    public function boss():BelongsTo
    {
        return $this->belongsTo(User::class, 'boss_id');
    }
    public function scriptorium():BelongsTo
    {
        return $this->belongsTo(User::class, 'scriptorium_id');
    }







    public function getRoleForUser(User $user): string
    {
        if ($user->id === $this->scriptorium_id) {
            return 'دبیرجلسه';
        }

        if ($user->id === $this->boss_id) {
            return 'رئیس جلسه';
        }

        $meetingUser = $this->meetingUsers->firstWhere('user_id', $user->id);
        if ($meetingUser) {
            return $meetingUser->is_guest ? 'مهمان' : 'عضو جلسه';
        }

        return 'none'; // user not associated with meeting
    }
}
