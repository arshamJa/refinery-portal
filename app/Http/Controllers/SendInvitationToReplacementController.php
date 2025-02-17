<?php

namespace App\Http\Controllers;

use App\Models\MeetingUser;
use App\Models\User;
use App\Models\UserInfo;
use App\Rules\farsi_chs;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SendInvitationToReplacementController extends Controller
{
    /**
     * Handle the incoming request.
     * @throws ValidationException
     */
    public function __invoke(Request $request, string $meetingId)
    {
        $request->validate([
          'checkBox' => ['required'],
          'full_name' => ['required','string' ,new farsi_chs()],
          'p_code' => ['required','numeric','digits:6']
        ]);
        $full_name = Str::deduplicate($request->full_name);
        $userId =UserInfo::where('full_name',$full_name)->value('user_id');
        if (!User::where('p_code',$request->p_code)->exists()){
            throw ValidationException::withMessages([
               'p_code' => 'کد پرسنلی وجود ندارد'
            ]);
        }elseif(MeetingUser::where('meeting_id',$meetingId)->where('user_id',$userId)->exists()) {
            throw ValidationException::withMessages([
                'full_name' => 'شخص جانشین قبلا دعوت به جلسه شده است'
            ]);
        }else{
            MeetingUser::create([
                'meeting_id' => $meetingId,
                'user_id' => $userId,
            ]);
            return redirect()->back()->with('status','دعوتنامه با موفقیت به جانشین ارسال شد');
        }
    }
}
