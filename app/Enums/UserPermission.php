<?php

namespace App\Enums;

enum UserPermission: string
{
    case CREATE_MEETING = 'ایجاد جلسه';
    case TASK_REPORT_TABLE = 'گزارش جلسات شرکت';
    case SCRIPTORIUM_PERMISSIONS = 'دسترسی های دبیر جلسه';
    case PHONE_PERMISSIONS = 'دسترسی های دفترچه تلفنی';
    case NEWS_PERMISSIONS = 'دسترسی های اخبارواطلاعیه';
}
