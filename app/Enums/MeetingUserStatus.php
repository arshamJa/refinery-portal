<?php

namespace App\Enums;

enum MeetingUserStatus:int
{
    case PENDING = 0 ;          // جواب نداده
    case IS_PRESENT = 1 ;
    case IS_NOT_PRESENT = -1;


}
