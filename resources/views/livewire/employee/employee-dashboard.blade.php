@php use App\Models\MeetingUser; @endphp
<div>
    <div class="grid grid-cols-3 gap-4 mt-20">


             <x-notifications/>


        <div class="col-span-2">
                {{--   this is Calender --}}
                <div class="w-full p-4 rounded-lg shadow-md">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 19l-7-7 7-7"/>
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

{{--        <div class="col-span-2">--}}
{{--            <div class="p-2">--}}
{{--                <div class="xl:col-span-1 col-span-2 xl:mt-0 mt-6 p-4">--}}
{{--                    <div--}}
{{--                        x-data="{--}}
{{--                tabSelected: 1,--}}
{{--                tabId: $id('tabs'),--}}
{{--                tabButtonClicked(tabButton){--}}
{{--                    this.tabSelected = tabButton.id.replace(this.tabId + '-', '');--}}
{{--                    this.tabRepositionMarker(tabButton);--}}
{{--                },--}}
{{--                tabRepositionMarker(tabButton){--}}
{{--                    this.$refs.tabMarker.style.width=tabButton.offsetWidth + 'px';--}}
{{--                    this.$refs.tabMarker.style.height=tabButton.offsetHeight + 'px';--}}
{{--                    this.$refs.tabMarker.style.left=tabButton.offsetLeft + 'px';--}}
{{--                },--}}
{{--                tabContentActive(tabContent){--}}
{{--                    return this.tabSelected == tabContent.id.replace(this.tabId + '-content-', '');--}}
{{--                }--}}
{{--            }"--}}
{{--                        x-init="tabRepositionMarker($refs.tabButtons.firstElementChild);" class="relative">--}}

{{--                        <div x-ref="tabButtons"--}}
{{--                             class="relative inline-grid items-center justify-center w-full h-10 grid-cols-2 p-1 text-gray-500 bg-gray-100 rounded-lg select-none">--}}
{{--                            <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button"--}}
{{--                                    class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">{{__('نقش دبیر جلسه')}}</button>--}}
{{--                            <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button"--}}
{{--                                    class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">{{__('نقش عضو جلسه')}}</button>--}}
{{--                            <div x-ref="tabMarker" class="absolute left-0 z-10 w-1/2 h-full duration-300 ease-out"--}}
{{--                                 x-cloak>--}}
{{--                                <div class="w-full h-full bg-white rounded-md shadow-sm"></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="relative mt-2 content">--}}
{{--                            <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative">--}}
{{--                                <!-- Tab Content 1 - Replace with your content -->--}}
{{--                                <div--}}
{{--                                    class="border rounded-lg p-3 space-y-2 shadow-sm bg-card text-neutral-900 h-[500px] overflow-y-auto">--}}
{{--                                    @foreach($this->meetingsSchedule as $schedule)--}}
{{--                                        <div--}}
{{--                                            class="flex flex-col justify-between sm:flex-row items-start border rounded-lg px-2 py-3 hover:shadow-md transition-shadow duration-300">--}}
{{--                                            <div>--}}
{{--                                                <div--}}
{{--                                                    class="text-lg font-medium text-gray-900">{{$schedule->title}}</div>--}}
{{--                                                <div class="text-sm text-gray-700">{{$schedule->location}}</div>--}}
{{--                                            </div>--}}
{{--                                            <div class="flex-shrink-0 mb-2 sm:mb-0 sm:mr-4">--}}
{{--                                                <div--}}
{{--                                                    class="text-lg font-medium text-indigo-600">{{$schedule->date}}</div>--}}
{{--                                                <div class="text-sm text-gray-500">{{$schedule->time}}</div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                                <!-- End Tab Content 1 -->--}}
{{--                            </div>--}}

{{--                            <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative" x-cloak>--}}
{{--                                <!-- Tab Content 2 - Replace with your content -->--}}
{{--                                <div class="border rounded-lg shadow-sm bg-card text-neutral-900">--}}
{{--                                    <div class="flex flex-col space-y-1.5 p-6">--}}
{{--                                        <h3 class="text-lg font-semibold leading-none tracking-tight">Password</h3>--}}
{{--                                        <p class="text-sm text-neutral-500">Change your password here. After saving,--}}
{{--                                            you'll be--}}
{{--                                            logged out.</p>--}}
{{--                                    </div>--}}
{{--                                    <div class="p-6 pt-0 space-y-2">--}}
{{--                                        <div class="space-y-1"><label--}}
{{--                                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"--}}
{{--                                                for="password">Current Password</label><input type="password"--}}
{{--                                                                                              placeholder="Current Password"--}}
{{--                                                                                              id="password"--}}
{{--                                                                                              class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md peer border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"/>--}}
{{--                                        </div>--}}
{{--                                        <div class="space-y-1"><label--}}
{{--                                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"--}}
{{--                                                for="password_new">New Password</label><input type="password"--}}
{{--                                                                                              placeholder="New Password"--}}
{{--                                                                                              id="password_new"--}}
{{--                                                                                              class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"/>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="flex items-center p-6 pt-0">--}}
{{--                                        <button type="button"--}}
{{--                                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-white transition-colors duration-200 rounded-md bg-neutral-950 hover:bg-neutral-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none">--}}
{{--                                            Save password--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                --}}{{--                                <!-- End Tab Content 2 -->--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    --}}{{--                    <div--}}
{{--                    --}}{{--                        class="xl:w-full overflow-y-auto h-96 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200 xl:max-w-md xl:mx-auto py-4 px-2 rounded-xl shadow-lg">--}}
{{--                    --}}{{--                        <div class="flex items-center gap-x-2 mb-6">--}}
{{--                    --}}{{--                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600 mr-3" fill="none"--}}
{{--                    --}}{{--                                 viewBox="0 0 24 24" stroke="currentColor">--}}
{{--                    --}}{{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                    --}}{{--                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>--}}
{{--                    --}}{{--                            </svg>--}}
{{--                    --}}{{--                            <h2 class="text-2xl font-semibold text-gray-800">{{__('جلساتی که باید شرکت کنم')}}</h2>--}}
{{--                    --}}{{--                        </div>--}}
{{--                    --}}{{--                        <div class="space-y-4">--}}
{{--                    --}}{{--                            @foreach($this->meetingsSchedule as $schedule)--}}
{{--                    --}}{{--                                <div--}}
{{--                    --}}{{--                                    class="flex flex-col justify-between sm:flex-row items-start border rounded-lg px-2 py-3 hover:shadow-md transition-shadow duration-300">--}}
{{--                    --}}{{--                                    <div>--}}
{{--                    --}}{{--                                        <div class="text-lg font-medium text-gray-900">{{$schedule->title}}</div>--}}
{{--                    --}}{{--                                        <div class="text-sm text-gray-700">{{$schedule->location}}</div>--}}
{{--                    --}}{{--                                    </div>--}}
{{--                    --}}{{--                                    <div class="flex-shrink-0 mb-2 sm:mb-0 sm:mr-4">--}}
{{--                    --}}{{--                                        <div class="text-lg font-medium text-indigo-600">{{$schedule->date}}</div>--}}
{{--                    --}}{{--                                        <div class="text-sm text-gray-500">{{$schedule->time}}</div>--}}
{{--                    --}}{{--                                    </div>--}}
{{--                    --}}{{--                                </div>--}}
{{--                    --}}{{--                            @endforeach--}}
{{--                    --}}{{--                            <div class="p-4 border-b">--}}
{{--                    --}}{{--                                <p class="font-semibold">1403/12/05</p>--}}
{{--                    --}}{{--                                <p class="text-sm">10:00</p>--}}
{{--                    --}}{{--                                <p class="mt-2">جلسه اول</p>--}}
{{--                    --}}{{--                                <p class="text-sm">ساختمان اول</p>--}}
{{--                    --}}{{--                            </div>--}}
{{--                    --}}{{--                        </div>--}}
{{--                    --}}{{--                        <ul class="space-y-6">--}}
{{--                    --}}{{--                            <p>role = scriptorium</p>--}}
{{--                    --}}{{--                            --}}{{----}}{{--                        @foreach($this->meetingNotifications as $meetingNotification)--}}
{{--                    --}}{{--                            --}}{{----}}{{--                            <li class="flex flex-col justify-between sm:flex-row items-start border rounded-lg p-4 hover:shadow-md transition-shadow duration-300">--}}
{{--                    --}}{{--                            --}}{{----}}{{--                                <div>--}}
{{--                    --}}{{--                            --}}{{----}}{{--                                    <div class="text-lg font-medium text-gray-900">{{$meetingNotification->title}}</div>--}}
{{--                    --}}{{--                            --}}{{----}}{{--                                    <div class="text-sm text-gray-700">{{$meetingNotification->location}}</div>--}}
{{--                    --}}{{--                            --}}{{----}}{{--                                </div>--}}
{{--                    --}}{{--                            --}}{{----}}{{--                                <div class="flex-shrink-0 mb-2 sm:mb-0 sm:mr-4">--}}
{{--                    --}}{{--                            --}}{{----}}{{--                                    <div class="text-lg font-medium text-indigo-600">{{$meetingNotification->date}}</div>--}}
{{--                    --}}{{--                            --}}{{----}}{{--                                    <div class="text-sm text-gray-500">{{$meetingNotification->time}}</div>--}}
{{--                    --}}{{--                            --}}{{----}}{{--                                </div>--}}
{{--                    --}}{{--                            --}}{{----}}{{--                            </li>--}}
{{--                    --}}{{--                            --}}{{----}}{{--                        @endforeach--}}
{{--                    --}}{{--                        </ul>--}}
{{--                    --}}{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
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
                        cell.classList.add('relative', 'p-2', 'text-center', 'rounded-md', 'hover:bg-gray-200');
                        const cellJalaliMoment = moment(jYear + '-' + (jMonth + 1) + '-' + dayCounter, 'jYYYY-jMM-jDD');
                        const todayJalaliMoment = moment().startOf('day');
                        if (cellJalaliMoment.isSame(todayJalaliMoment, 'jDay')) {
                            cell.classList.remove('hover:bg-gray-200');
                            cell.classList.add('bg-blue-500', 'text-white', 'font-semibold');
                        } else if (j === 6) {
                            cell.classList.add('text-red-500');
                        }
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
