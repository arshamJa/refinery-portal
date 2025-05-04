<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = '0';          // حالت 0 هست
    case ACCEPTED = '1';     // تایید شده
    case DENIED = '-1';   // نیاز به ویرایش دارد
    case IS_COMPLETED = '2';     // انجام شده

}
