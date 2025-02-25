@php use App\Models\MeetingUser; @endphp
<div>
        <div class="max-w-full p-2 grid grid-cols-2 justify-between mt-10">
        <div class="xl:col-span-1 col-span-2 p-3">
            {{--            this is the boxes--}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-4">
                <a href="{{route('message')}}"
                   class="flex justify-between items-center gap-x-2 hover:bg-[#3D3D3D] hover:text-[#FFFAEC] text-black border border-[#3D3D3D] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                        <span class="flex items-center gap-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                        </svg>
                        <span class="text-sm font-semibold">{{__('پیغام های دریافتی')}}</span>
                        </span>
                    <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">{{$this->messages}}</span>
                </a>
                <a href="{{route('attended.meetings')}}"
                   class="flex justify-between items-center gap-x-4 hover:bg-[#882042] hover:text-[#FFFAEC] text-black border border-[#882042] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                    <h3 class="text-sm font-semibold"> {{__('جلساتی که در آن شرکت کردم')}}</h3>
                    <span
                        class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">{{MeetingUser::where('user_id',auth()->user()->id)->where('is_present',1)->count()}}</span>
                </a>
            </div>

            {{--            this is the boxes--}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-4">
                <div>
                    <a href="{{route('meetings.create')}}"
                       class="flex items-center gap-x-2 hover:bg-[#40A578] hover:text-[#FFFAEC] border border-[#40A578] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        <h3 class="text-sm font-semibold"> {{__('ایجاد جلسه جدید')}}</h3>
                    </a>
                </div>
                <div>
                    <a href="{{route('meetingsList')}}"
                       class="flex items-center gap-x-2 bg-transparent text-black hover:bg-[#9DDE8B] hover:text-[#FFFAEC] border border-[#9DDE8B] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"/>
                        </svg>
                        <h3 class="text-sm font-semibold"> {{__('جدول جلسات')}}</h3>
                        {{--                                                <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5"></span>--}}
                    </a>
                </div>
                <div>
                    <a href="{{route('scriptorium.report')}}"
                       class="flex justify-between items-center gap-x-4 hover:bg-[#CD5555] hover:text-[#FFFAEC] text-black border border-[#CD5555] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                        <h3 class="text-sm font-semibold"> {{__('گزارش جلسات تشکیل شده')}}</h3>
                        <span class="rounded-md p-1 bg-gray-400 text-white py-1 px-2.5">{{$this->meetings}}</span>
                    </a>
                </div>
                <div>
                    <a href="{{route('meeting.report')}}"
                       class="flex h-full items-center gap-x-2 hover:bg-[#3D3D3D] hover:text-[#FFFAEC] text-black border border-[#3D3D3D] hover:drop-shadow-xl transition duration-300 ease-in-out p-4 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z"/>
                        </svg>
                        <h3 class="text-sm font-semibold"> {{__('داشبورد جلسات')}}</h3>
                    </a>
                </div>
            </div>


            {{--            this is calender --}}
            <div class="w-full p-4 bg-white rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-6">
                    <button id="prev-month" class="p-2 rounded-full hover:bg-gray-100 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <h2 class="text-lg font-semibold text-gray-800" id="month-year"></h2>
                    <button id="next-month" class="p-2 rounded-full hover:bg-gray-100 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                </div>
                <table class="w-full text-sm text-center">
                    <thead>
                    <tr class="text-gray-500">
                        <th class="py-2">شنبه</th>
                        <th class="py-2">یکشنبه</th>
                        <th class="py-2">دوشنبه</th>
                        <th class="py-2">سه شنبه</th>
                        <th class="py-2">چهارشنبه</th>
                        <th class="py-2">پنجشنبه</th>
                        <th class="py-2">جمعه</th>
                    </tr>
                    </thead>
                    <tbody id="calendar-body"></tbody>
                </table>
            </div>
        </div>
        {{--        this is for time table reminder --}}
        <div class="xl:col-span-1 col-span-2 xl:mt-0 mt-6 p-3">
            <div class="xl:w-full overflow-y-auto h-96 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200 xl:max-w-md xl:mx-auto py-4 px-2 rounded-xl shadow-lg">
                <div class="flex items-center gap-x-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600 mr-3" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h2 class="text-2xl font-semibold text-gray-800">{{__('جلساتی که باید شرکت کنم')}}</h2>
                </div>
                <div class="space-y-4">
                    @foreach($this->meetingsSchedule as $schedule)
                        <div class="flex flex-col justify-between sm:flex-row items-start border rounded-lg px-2 py-3 hover:shadow-md transition-shadow duration-300">
                            <div>
                                <div class="text-lg font-medium text-gray-900">{{$schedule->title}}</div>
                                <div class="text-sm text-gray-700">{{$schedule->location}}</div>
                            </div>
                            <div class="flex-shrink-0 mb-2 sm:mb-0 sm:mr-4">
                                <div class="text-lg font-medium text-indigo-600">{{$schedule->date}}</div>
                                <div class="text-sm text-gray-500">{{$schedule->time}}</div>
                            </div>
                        </div>
                    @endforeach
                        <div class="p-4 border-b">
                            <p class="font-semibold">1403/12/05</p>
                            <p class="text-sm">10:00</p>
                            <p class="mt-2">جلسه اول</p>
                            <p class="text-sm">ساختمان اول</p>
                        </div>
                </div>
                {{--                <ul class="space-y-6">--}}
                {{--                    <p>role = scriptorium</p>--}}
                {{--                    @foreach($this->meetingNotifications as $meetingNotification)--}}
                {{--                        <li class="flex flex-col justify-between sm:flex-row items-start border rounded-lg p-4 hover:shadow-md transition-shadow duration-300">--}}
                {{--                            <div>--}}
                {{--                                <div class="text-lg font-medium text-gray-900">{{$meetingNotification->title}}</div>--}}
                {{--                                <div class="text-sm text-gray-700">{{$meetingNotification->location}}</div>--}}
                {{--                            </div>--}}
                {{--                            <div class="flex-shrink-0 mb-2 sm:mb-0 sm:mr-4">--}}
                {{--                                <div class="text-lg font-medium text-indigo-600">{{$meetingNotification->date}}</div>--}}
                {{--                                <div class="text-sm text-gray-500">{{$meetingNotification->time}}</div>--}}
                {{--                            </div>--}}
                {{--                        </li>--}}
                {{--                    @endforeach--}}
                {{--                </ul>--}}
            </div>
        </div>
    </div>


    @script
    <script>
        const monthYear = document.getElementById('month-year');
        const calendarBody = document.getElementById('calendar-body');
        const prevMonthButton = document.getElementById('prev-month');
        const nextMonthButton = document.getElementById('next-month');
        let currentJalaliMonth = moment().jMonth();
        let currentJalaliYear = moment().jYear();
        const persianMonths = [
            "فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور",
            "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"
        ];

        function generateCalendar(jMonth, jYear) {
            const jalaliMoment = moment(jYear + '-' + (jMonth + 1) + '-01', 'jYYYY-jMM-jDD');
            const firstDay = jalaliMoment.startOf('jMonth');
            const lastDay = jalaliMoment.endOf('jMonth');
            const daysInMonth = lastDay.jDate();
            const startDay = firstDay.day();
            monthYear.textContent = `${persianMonths[jMonth]} ${jYear}`;
            calendarBody.innerHTML = '';
            let dayCounter = 1;
            for (let i = 0; i < 6; i++) {
                const row = calendarBody.insertRow();
                for (let j = 0; j < 7; j++) {
                    const cell = row.insertCell();
                    if (i === 0 && j < startDay) {
                        cell.textContent = '';
                    } else if (dayCounter > daysInMonth) {
                        break;
                    } else {
                        cell.textContent = dayCounter;
                        cell.classList.add('relative', 'px-2.5', 'py-2', 'text-center', 'rounded-md', 'hover:bg-gray-200');
                        const cellJalaliMoment = moment(jYear + '-' + (jMonth + 1) + '-' + dayCounter, 'jYYYY-jMM-jDD');
                        const todayJalaliMoment = moment().startOf('day');
                        if (cellJalaliMoment.isSame(todayJalaliMoment, 'jDay')) {
                            cell.classList.remove('hover:bg-gray-200');
                            cell.classList.add('bg-blue-500', 'text-white', 'font-semibold');
                        } else if (j === 6) {
                            cell.classList.add('text-red-500');
                        }
                        // Add tooltip
                        const tooltip = document.createElement('div');
                        tooltip.textContent = 'at this time you have a meeting';
                        tooltip.classList.add('absolute', 'bg-gray-800', 'text-white', 'p-2', 'rounded-md', 'text-xs', 'hidden', '-right-10', 'bottom-10', 'z-50');
                        cell.appendChild(tooltip);
                        cell.addEventListener('mouseover', () => {
                            tooltip.classList.remove('hidden');
                        });
                        cell.addEventListener('mouseleave', () => { // Change mouseout to mouseleave
                            tooltip.classList.add('hidden');
                        });
                        dayCounter++;
                    }
                }
            }
        }

        generateCalendar(currentJalaliMonth, currentJalaliYear);
        prevMonthButton.addEventListener('click', () => {
            currentJalaliMonth--;
            if (currentJalaliMonth < 0) {
                currentJalaliMonth = 11;
                currentJalaliYear--;
            }
            generateCalendar(currentJalaliMonth, currentJalaliYear);
        });
        nextMonthButton.addEventListener('click', () => {
            currentJalaliMonth++;
            if (currentJalaliMonth > 11) {
                currentJalaliMonth = 0;
                currentJalaliYear++;
            }
            generateCalendar(currentJalaliMonth, currentJalaliYear);
        });
    </script>
    @endscript
</div>
