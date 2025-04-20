<?php

namespace App\Models;

use App\MeetingStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Meeting extends Model
{
    use HasFactory,Notifiable,SoftDeletes;


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
        'is_cancelled'
    ];

    protected $casts = [
        'guest' => 'array',
    ];
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


    // Scope for Scriptorium Report
    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('date', [$start, $end]);
    }
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('unit_organization', 'like', "%{$term}%")
                ->orWhere('scriptorium', 'like', "%{$term}%")
                ->orWhere('location', 'like', "%{$term}%")
                ->orWhere('date', 'like', "%{$term}%")
                ->orWhere('time', 'like', "%{$term}%")
                ->orWhere('unit_held', 'like', "%{$term}%")
                ->orWhere('guest', 'like', "%{$term}%")
                ->orWhere('applicant', 'like', "%{$term}%")
                ->orWhere('position_organization', 'like', "%{$term}%");
        });
    }
    // End of  Scope for Scriptorium Report


}
