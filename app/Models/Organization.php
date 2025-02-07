<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'organization_name',
        'url',
        'image'
    ];

    public function getImageUrl()
    {
        return url('storage/'.$this->image);
    }


    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function departmentUserOrganizations(): HasMany
    {
        return $this->hasMany(DepartmentUserOrganization::class)->chaperone();
    }

    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }


}
