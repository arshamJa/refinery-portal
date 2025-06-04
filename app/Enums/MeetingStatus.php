<?php

namespace App\Enums;

enum MeetingStatus:int
{
    case PENDING = 0 ;          // در حال بررسی
    case IS_CANCELLED = 1 ;     // لغو شد
    case IS_NOT_CANCELLED = -1 ; // برگزار میشود
    case IS_IN_PROGRESS =  2;   // در حال برگزاری
    case IS_FINISHED =3 ;      // جلسه خاتمه یافت


    public function label(): string
    {
        return match($this) {
            self::PENDING => 'درحال بررسی دعوتنامه',
            self::IS_CANCELLED => 'جلسه لغو شد',
            self::IS_NOT_CANCELLED => 'جلسه برگزار میشود',
            self::IS_IN_PROGRESS => 'جلسه درحال برگزاری است',
            self::IS_FINISHED => 'جلسه خاتمه یافت',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-600',
            self::IS_CANCELLED => 'bg-red-100 text-red-600',
            self::IS_NOT_CANCELLED => 'bg-green-100 text-green-600',
            self::IS_IN_PROGRESS => 'bg-blue-100 text-blue-600',
            self::IS_FINISHED => 'bg-gray-100 text-gray-700',
        };
    }

}
