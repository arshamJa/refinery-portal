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
            // Get full names of participants
            $participants = $meeting->meetingUsers
                ->map(fn($mu) => optional($mu->user->user_info)->full_name)
                ->filter()
                ->join(', ');

            // Parse guest field if needed
            $guests = collect(is_array($meeting->guest) ? $meeting->guest : json_decode($meeting->guest, true))
                ->filter()
                ->join(', ');

            return [
                'ردیف' => $index + 1,
                'نام دبیر' => $meeting->scriptorium,
                'واحد سازمانی' => $meeting->unit_organization,
                'موضوع جلسه' => $meeting->title,
                'تاریخ برگزاری' => $meeting->date,
                'مکان' => $meeting->location,
                'زمان' => $meeting->time,
                'سمت سازمانی' => $meeting->position_organization,
                'واحد برگزار کننده' => $meeting->unit_held,
                'درخواست دهنده جلسه' => $meeting->applicant,
                'اعضای جلسه' => $participants,
                'مهمان' => $guests,
            ];
        });
    }
    public function headings(): array
    {
        return [
            'ردیف', 'نام دبیر', 'واحد سازمانی', 'موضوع جلسه', 'تاریخ برگزاری', 'مکان','زمان',
            'سمت سازمانی', 'واحد برگزار کننده', 'درخواست دهنده جلسه', 'اعضای جلسه', 'مهمان',
        ];
    }
}
