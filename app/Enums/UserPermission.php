<?php

namespace App\Enums;

enum UserPermission: string
{
    case TASK_REPORT_TABLE = 'رویت گزارش جلسات و اقدامات';
    case SCRIPTORIUM_PERMISSIONS = 'دبیر جلسه';
    case PHONE_PERMISSIONS = 'مدیریت و نمایش دفترچه تلفنی';
    case NEWS_PERMISSIONS = 'مدیریت و نمایش اخبارواطلاعیه';
    case VIEW_ORGANIZATIONS = 'نمایش سامانه ها';
    case VIEW_PHONE_LISTS = 'نمایش دفترچه تلفن';
    case VIEW_MEETING_DASHBOARD = 'نمایش داشبورد جلسات';
    case VIEW_BLOG = 'نمایش اخبار و اطلاعیه';
    case ORGANIZATION_TABLE = 'جدول سامانه';
    case DEPARTMENT_TABLE = 'جدول دپارتمان';
    case CREATE_NEW_USER = 'ساخت کاربر جدید';
    case  USERS_TABLE = 'مدیریت کاربران';
    case ORGANIZATION_DEPARTMENT_MANAGE = 'مدیریت سامانه و دپارتمان';

}
