<?php

namespace App\Enums;

enum UserPermission: string
{
    case CREATE_MEETING = 'ایجاد جلسه';
    case TASK_REPORT_TABLE = 'گزارش جلسات شرکت';
    case SCRIPTORIUM_PERMISSIONS = 'دسترسی های دبیر جلسه';
}
