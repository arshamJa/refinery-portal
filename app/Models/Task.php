<?php

namespace App\Models;

use App\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;


    protected $fillable = [
        'meeting_id',
        'user_id',
//        'title',
        'body',
        'sent_date',
        'time_out',
        'is_completed',
        'request_task'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function meeting():BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

//    public function compareDates()
//    {
//        // deadline to send task
//        $first_date = $this->time_out;
//        $first_year = \Illuminate\Support\Str::of($first_date)->explode('/');
//        $first_month = \Illuminate\Support\Str::of($first_date)->explode('/');
//        $first_day = \Illuminate\Support\Str::of($first_date)->explode('/');
//
//        // the date that the participant sent the task
//        $second_date = $this->sent_date;
//        $second_year = \Illuminate\Support\Str::of($second_date)->explode('/');
//        $second_month = \Illuminate\Support\Str::of($second_date)->explode('/');
//        $second_day = \Illuminate\Support\Str::of($second_date)->explode('/');
//
//        if ($first_year[0] === $second_year[0]){
//            if ($first_month[1] === $second_month[1]){
//                if ($first_day[2] === $second_day[2]){
//                    'OK'
//                }elseif($first_day[2] < $second_day[2]){
//                    'first day is smaller than second day'
//                }elseif($first_day[2] > $second_day[2]){
//                    'first day is greater than second day'
//                }
//            }elseif($first_month[1] < $second_month[1]){
//                'first month smaller than second month'
//            }elseif($first_month[1] > $second_month[1]){
//                'first month is grater than second month'
//            }
//        }elseif($first_year[0] < $second_year[0]){
//            'first year is smaller than second year'
//        }else{
//            'first year is grater thant second year'
//        }
//    }
}
