<?php

namespace App\Enums;

enum MeetingStatus:int
{
    case PENDING = 0 ;          // در حال بررسی
    case IS_CANCELLED = 1 ;     // لغو شد
    case IS_NOT_CANCELLED = -1 ; // برگزار میشود
    case IS_IN_PROGRESS =  2;   // در حال برگزاری
    case IS_FINISHED =3 ;      // جلسه خاتمه یافت
}
