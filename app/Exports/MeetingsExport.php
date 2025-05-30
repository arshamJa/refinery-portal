<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class MeetingsExport implements FromCollection, WithHeadings
{
    protected Collection $meetings;

    public function __construct($meetings)
    {
        $this->meetings = $meetings;
    }

    public function collection()
    {
        return $this->meetings->values()->map(function ($meeting, $index) {

            // Get full names of participants (including all meeting user info)
            $participants = $meeting->meetingUsers
                ->map(function ($mu) {
                    // Ensure there's no null value when accessing user_info
                    return optional($mu->user)->user_info ? optional($mu->user->user_info)->full_name : null;
                })
                ->filter()  // Filter out null values
                ->join(', ');




            // Parse guest field if needed
            $guests = collect(is_array($meeting->guest) ? $meeting->guest : json_decode($meeting->guest, true))
                ->filter()
                ->join(', ');


            return [
                'ردیف' => $index + 1,
                'نام دبیر' => $meeting->scriptorium,
                'نام رئیس جلسه' => $meeting->boss,
                'واحد سازمانی' => $meeting->scriptorium_department,
                'موضوع جلسه' => $meeting->title,
                'تاریخ برگزاری' => $meeting->date,
                'مکان' => $meeting->location,
                'زمان' => $meeting->time,
                'سمت سازمانی' => $meeting->scriptorium_position,
                'واحد برگزار کننده' => $meeting->unit_held,
                'اعضای جلسه' => $participants,
                'مهمان' => $guests,
            ];
        });
    }
    public function headings(): array
    {
        return [
            'ردیف', 'نام دبیر','نام رئیس جلسه' , 'واحد سازمانی', 'موضوع جلسه', 'تاریخ برگزاری', 'مکان','زمان',
            'سمت سازمانی', 'واحد برگزار کننده', 'اعضای جلسه', 'مهمان',
        ];
    }
}
