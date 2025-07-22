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
            // Safely retrieve scriptorium info
            $scriptoriumUserInfo = optional($meeting->scriptorium->user_info);
            $scriptoriumName = $scriptoriumUserInfo->full_name ?? '—';
            $scriptoriumDepartment = optional($scriptoriumUserInfo->department)->department_name ?? '—';
            $scriptoriumPosition = $scriptoriumUserInfo->position ?? '—';

            // Boss is a user related by boss_id
            $bossUserInfo = optional($meeting->boss->user_info);
            $bossName = $bossUserInfo->full_name ?? '—';

            // Participants: exclude guests and boss
            $participants = $meeting->meetingUsers
                ->filter(fn($mu) => !$mu->is_guest && $mu->user_id !== $meeting->boss_id)
                ->map(fn($mu) => optional($mu->user->user_info)->full_name)
                ->filter()->join(', ');

            // Inner Guests (users marked as guests)
            $innerGuests = $meeting->meetingUsers
                ->filter(fn($mu) => $mu->is_guest)
                ->map(fn($mu) => optional($mu->user->user_info)->full_name)
                ->filter()->join(', ');

            // Outer Guests (from guest JSON)
            $outerGuests = collect(is_array($meeting->guest) ? $meeting->guest : json_decode($meeting->guest, true))
                ->filter()->join(', ');

            return [
                'ردیف' => $index + 1,
                'نام دبیر' => $scriptoriumName,
                'نام رئیس جلسه' => $bossName,
                'واحد متولی جلسه' => $scriptoriumDepartment,
                'موضوع جلسه' => $meeting->title,
                'تاریخ برگزاری' => $meeting->date,
                'مکان' => $meeting->location,
                'زمان' => $meeting->time,
                'سمت سازمانی' => $scriptoriumPosition,
                'واحد برگزار کننده' => $meeting->unit_held,
                'اعضای جلسه' => $participants,
                'مهمان داخلی' => $innerGuests,
                'مهمان خارجی' => $outerGuests,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ردیف', 'نام دبیر', 'نام رئیس جلسه', 'واحد متولی جلسه', 'موضوع جلسه', 'تاریخ برگزاری', 'مکان', 'زمان',
            'سمت سازمانی', 'واحد برگزار کننده', 'اعضای جلسه', 'مهمان داخلی', 'مهمان خارجی',
        ];
    }
}
