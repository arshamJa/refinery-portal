<?php

namespace App\Enums;

enum UserPermission: string
{
    case CREATE_MEETING = 'ایجاد جلسه';
    case TASK_REPORT_TABLE = 'جدول گزارش اقدامات';
    case SCRIPTORIUM_PERMISSIONS = 'دسترسی های دبیر جلسه';
}
