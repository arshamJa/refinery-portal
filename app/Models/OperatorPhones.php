<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatorPhones extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'department_id',
        'work_phone',
        'house_phone',
        'phone',
        'n_code',
        'p_code',
        'position',
        'full_name'

    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

}
