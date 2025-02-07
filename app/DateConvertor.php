<?php

namespace App;

use Illuminate\Support\Facades\Request;

trait DateConvertor
{
    public function Date(Request $request)
    {
        $date = $request->date;

        $g_day = jalali_to_gregorian(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2))[2] < 10 ?
            '0' . jalali_to_gregorian(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2))[2] :
            jalali_to_gregorian(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2))[2];

        $g_month = jalali_to_gregorian(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2))[1] < 10 ?
            '0' . jalali_to_gregorian(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2))[1]
            : jalali_to_gregorian(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2))[1];

        $g_year = jalali_to_gregorian(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2))[0];
        $gregorian_format = $g_month . '/' . $g_day . '/' . $g_year;

    }
}
