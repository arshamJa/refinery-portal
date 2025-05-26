<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'ادمین';
    case OPERATOR = 'اپراتور';
    case USER = 'کاربر';
    case SCRIPTORIUM = 'دبیرجلسه';
    case BOSS = 'رئیس';

}
