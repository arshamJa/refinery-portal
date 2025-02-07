<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
    protected $fillable=['department_name'];


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    public function userInfos(): HasMany
    {
        return $this->hasMany(UserInfo::class)->chaperone();
    }

    public function organizations():HasMany
    {
        return $this->hasMany(Organization::class)->chaperone();
    }





}
