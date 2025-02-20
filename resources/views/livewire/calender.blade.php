<div>
    <div class="mt-10 shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <button id="prev-month" class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300 mr-2">&lt;</button>
            <h2 class="text-2xl font-bold" id="month-year"></h2>
            <button id="next-month" class="px-3 py-2 rounded bg-gray-200 hover:bg-gray-300">&gt;</button>
        </div>

        <table class="w-full">
            <thead>
            <tr class="text-gray-500">
                <th class="py-2">شنبه</th>
                <th class="py-2">یکشنبه</th>
                <th class="py-2">دوشنبه</th>
                <th class="py-2">سه‌شنبه</th>
                <th class="py-2">چهارشنبه</th>
                <th class="py-2">پنجشنبه</th>
                <th class="py-2">جمعه</th>
            </tr>
            </thead>
            <tbody id="calendar-body">
            </tbody>
        </table>
    </div>
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
            const jalaliMoment = moment(jYear + '-' + (jMonth + 1) + '-01', 'jYYYY-jMM-jDD'); // Use jMonth + 1 for Jalali months
            const firstDay = jalaliMoment.startOf('jMonth');
            const lastDay = jalaliMoment.endOf('jMonth');
            const daysInMonth = lastDay.jDate();
            const startDay = firstDay.day(); // Day of the week (0 = Saturday in Jalali)

            // monthYear.textContent = jalaliMoment.format('jMMMM jYYYY'); // Format in Persian
            monthYear.textContent = `${persianMonths[jMonth]} ${jYear}`; // Use Persian month names
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
                        cell.classList.add('py-2', 'text-center');
                        const today = moment();
                        const isToday = moment(today.format('jYYYY-jMM-jDD'), 'jYYYY-jMM-jDD').isSame(jalaliMoment.format('jYYYY-jMM-') + dayCounter, 'jDD');
                        // if (isToday) {
                        //     cell.classList.add('bg-blue-500','text-white', 'rounded-full');
                        // } else if (j === 6) { // Highlight Saturdays (0-indexed, Saturday is 6)
                        //     cell.classList.add('text-red-500');
                        // }

                        if (isToday) {
                            cell.classList.remove('border-gray-300'); // Remove default gray border
                            cell.classList.add('border-4', 'border-blue-500', 'bg-blue-500', 'text-white', 'rounded'); // Current day styling
                        } else if (j === 6) { // Saturday
                            cell.classList.add('text-red-500'); // Saturday styling (no special border)
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
</div>
