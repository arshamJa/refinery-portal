<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentUserOrganization extends Model
{
    use HasFactory;

    protected $fillable = ['department_user_id','organization_id'];

    public function departmentUser():BelongsTo
    {
        return $this->belongsTo(DepartmentUser::class);
    }

    public function organizations():BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }


}
