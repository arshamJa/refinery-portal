@php use App\Models\MeetingUser; @endphp
<div>
    <div class="max-w-4xl mt-20">
        <x-notifications/>
{{--        <div class="col-span-2">--}}
{{--            this is Calender--}}
{{--            <div class="w-full p-4 rounded-lg shadow-md">--}}
{{--                <div class="flex justify-between items-center mb-6">--}}
{{--                    <button id="prev-month" class="p-2 rounded-full hover:bg-gray-100 focus:outline-none">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"--}}
{{--                             viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>--}}
{{--                        </svg>--}}
{{--                    </button>--}}
{{--                    <h2 class="text-lg font-semibold text-gray-800" id="month-year"></h2>--}}
{{--                    <button id="next-month" class="p-2 rounded-full hover:bg-gray-100 focus:outline-none">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"--}}
{{--                             viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                                  d="M15 19l-7-7 7-7"/>--}}
{{--                        </svg>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <table class="w-full text-sm text-center">--}}
{{--                    <thead>--}}
{{--                    <tr class="text-gray-500">--}}
{{--                        <th class="py-2">شنبه</th>--}}
{{--                        <th class="py-2">یکشنبه</th>--}}
{{--                        <th class="py-2">دوشنبه</th>--}}
{{--                        <th class="py-2">سه شنبه</th>--}}
{{--                        <th class="py-2">چهارشنبه</th>--}}
{{--                        <th class="py-2">پنجشنبه</th>--}}
{{--                        <th class="py-2">جمعه</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody id="calendar-body"></tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>


{{--    @script--}}
{{--    <script>--}}
{{--        const monthYear = document.getElementById('month-year');--}}
{{--        const calendarBody = document.getElementById('calendar-body');--}}
{{--        const prevMonthButton = document.getElementById('prev-month');--}}
{{--        const nextMonthButton = document.getElementById('next-month');--}}
{{--        let currentJalaliMonth = moment().jMonth();--}}
{{--        let currentJalaliYear = moment().jYear();--}}
{{--        const persianMonths = [--}}
{{--            "فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور",--}}
{{--            "مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"--}}
{{--        ];--}}

{{--        function generateCalendar(jMonth, jYear) {--}}
{{--            const jalaliMoment = moment(jYear + '-' + (jMonth + 1) + '-01', 'jYYYY-jMM-jDD');--}}
{{--            const firstDay = jalaliMoment.startOf('jMonth');--}}
{{--            const lastDay = jalaliMoment.endOf('jMonth');--}}
{{--            const daysInMonth = lastDay.jDate();--}}
{{--            const startDay = firstDay.day();--}}
{{--            monthYear.textContent = `${persianMonths[jMonth]} ${jYear}`;--}}
{{--            calendarBody.innerHTML = '';--}}
{{--            let dayCounter = 1;--}}
{{--            for (let i = 0; i < 6; i++) {--}}
{{--                const row = calendarBody.insertRow();--}}
{{--                for (let j = 0; j < 7; j++) {--}}
{{--                    const cell = row.insertCell();--}}
{{--                    if (i === 0 && j < startDay) {--}}
{{--                        cell.textContent = '';--}}
{{--                    } else if (dayCounter > daysInMonth) {--}}
{{--                        break;--}}
{{--                    } else {--}}
{{--                        cell.textContent = dayCounter;--}}
{{--                        cell.classList.add('relative', 'p-2', 'text-center', 'rounded-md', 'hover:bg-gray-200');--}}
{{--                        const cellJalaliMoment = moment(jYear + '-' + (jMonth + 1) + '-' + dayCounter, 'jYYYY-jMM-jDD');--}}
{{--                        const todayJalaliMoment = moment().startOf('day');--}}
{{--                        if (cellJalaliMoment.isSame(todayJalaliMoment, 'jDay')) {--}}
{{--                            cell.classList.remove('hover:bg-gray-200');--}}
{{--                            cell.classList.add('bg-blue-500', 'text-white', 'font-semibold');--}}
{{--                        } else if (j === 6) {--}}
{{--                            cell.classList.add('text-red-500');--}}
{{--                        }--}}
{{--                        dayCounter++;--}}
{{--                    }--}}
{{--                }--}}
{{--            }--}}
{{--        }--}}

{{--        generateCalendar(currentJalaliMonth, currentJalaliYear);--}}
{{--        prevMonthButton.addEventListener('click', () => {--}}
{{--            currentJalaliMonth--;--}}
{{--            if (currentJalaliMonth < 0) {--}}
{{--                currentJalaliMonth = 11;--}}
{{--                currentJalaliYear--;--}}
{{--            }--}}
{{--            generateCalendar(currentJalaliMonth, currentJalaliYear);--}}
{{--        });--}}
{{--        nextMonthButton.addEventListener('click', () => {--}}
{{--            currentJalaliMonth++;--}}
{{--            if (currentJalaliMonth > 11) {--}}
{{--                currentJalaliMonth = 0;--}}
{{--                currentJalaliYear++;--}}
{{--            }--}}
{{--            generateCalendar(currentJalaliMonth, currentJalaliYear);--}}
{{--        });--}}
{{--    </script>--}}
{{--    @endscript--}}
</div>
