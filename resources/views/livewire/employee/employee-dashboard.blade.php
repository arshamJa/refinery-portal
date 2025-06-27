@php use App\Models\MeetingUser; @endphp
<div>
    <div class="grid lg:grid-cols-2 mt-20">
        <div class="p-2">
            <x-notifications/>
        </div>
        <div class="px-8 pb-6 pt-2 rounded-xl h-[600px] overflow-y-auto space-y-6">
            <x-meeting-time-table/>
        </div>
    </div>
</div>
