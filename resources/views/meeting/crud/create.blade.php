<x-app-layout>
    <nav class="flex justify-between mb-4 mt-20">
        <ol class="inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0">
            <li class="flex items-center h-full">
                <a href="{{route('dashboard')}}"
                   class="inline-flex items-center gap-1 px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z"
                            fill="currentColor"></path>
                    </svg>
                    <span>{{__('داشبورد')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
                <a href="{{route('dashboard.meeting')}}"
                   class="inline-flex items-center gap-1 px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    <span>{{__('جلسات')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
                        <span
                            class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                           {{__('ایجاد جلسه جدید')}}
                        </span>
            </li>
        </ol>
        {{--        <a href="{{route('meeting.table')}}">--}}
        {{--            <x-primary-button>{{__('جدول جلسات')}}</x-primary-button>--}}
        {{--        </a>--}}
    </nav>
    <form action="{{route('meeting.store')}}" method="post" enctype="multipart/form-data">
        @csrf


        <div class="p-4 mb-2 sm:p-8 bg-white dark:bg-gray-800 drop-shadow-xl sm:rounded-lg">
{{--                        Meeting Information--}}
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 border-b pb-2">
                    {{ __('بخش اطلاعات جلسه') }}
                </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2">

{{--                                Meeting Title--}}
                <div>
                    <x-input-label for="title" :value="__('موضوع جلسه')"/>
                    <x-text-input name="title" id="title"
                                  value="{{old('title')}}" class="block "
                                  type="text" autofocus/>
                    <x-input-error :messages="$errors->get('title')"/>
                </div>
{{--                                Meeting Location--}}
                <div>
                    <x-input-label for="location" :value="__('محل برگزاری جلسه')"/>
                    <x-text-input name="location" id="location"
                                  value="{{old('location')}}"
                                  class="block " type="text" autofocus/>
                    <x-input-error :messages="$errors->get('location')"/>
                </div>
{{--                                Treating--}}
                <div>
                    <x-input-label for="treat" :value="__('پذیرایی')"/>
                    <label for="yes">
                        {{ __('بلی') }}
                        <input type="radio" name="treat" value="true" {{ old('treat') === 'true' ? 'checked' : '' }}>
                    </label>
                    <label for="no" class="mr-3">
                        {{ __('خیر') }}
                        <input type="radio" name="treat" value="false" {{ old('treat') === 'false' ? 'checked' : '' }}>
                    </label>
                    <x-input-error :messages="$errors->get('treat')"/>
                </div>
            </div>

{{--                        Time & Date--}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2 ">
                <div>
                    <x-input-label for="year" :value="__('سال')"/>
                    <x-select-input name="year" id="year" dir="ltr">
                        <option value="">...سال</option>
                        @for($i = 1404; $i <= 1430; $i++)
                            <option value="{{$i}}" @if(old('year', $year ?? '') == $i) selected @endif>
                                {{$i}}
                            </option>
                        @endfor
                    </x-select-input>
                    <x-input-error :messages="$errors->get('year')"/>
                </div>
                <div>
                    @php
                        $persian_months = ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور","مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"];
                    @endphp
                    <x-input-label for="month" :value="__('ماه')"/>
                    <x-select-input name="month" id="month" dir="ltr">
                        <option value="">...ماه</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" @if(old('month', $month ?? '') == $i) selected @endif>
                                {{ $persian_months[$i - 1] }}
                            </option>
                        @endfor
                    </x-select-input>
                    <x-input-error :messages="$errors->get('month')"/>
                </div>
                <div>
                    <x-input-label for="day" :value="__('روز')"/>
                    <x-select-input name="day" id="day" dir="ltr">
                        <option value="">...روز</option>
                        @for($i = 1; $i <= 31; $i++)
                            <option value="{{$i}}" @if(old('day', $day ?? '') == $i) selected @endif>
                                {{$i}}
                            </option>
                        @endfor
                    </x-select-input>
                    <x-input-error :messages="$errors->get('day')"/>
                </div>
                <div>
                    <x-input-label for="time" :value="__('ساعت جلسه')"/>
                    <x-text-input name="time" id="time"
                                  value="{{old('time')}}"
                                  class="block" type="text" placeholder="{{__('00:00')}}"
                                  autofocus/>
                    <x-input-error :messages="$errors->get('time')"/>
                </div>
            </div>


            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 mt-4 border-b pb-2">
                {{ __('بخش دبیرجلسه') }}
            </h2>
{{--                        Scriptorium Information--}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2">
                <div x-data="dropdown()" class="relative w-full col-span-2" x-init="initOptions({{ $users }})">
                    <x-input-label for="boss" class="mb-2" :value="__('رئیس جلسه')"/>

                    <!-- Dropdown Button -->
                    <button type="button" @click="open = !open" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-left text-gray-800 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                        <span class="text-right w-full" x-text="selected ? selected.full_name + ' - ' + (selected.department ? selected.department.department_name : 'No department') + ' - ' + selected.position : '...'"></span>
                        <!-- Down Arrow Icon -->
                        <svg x-show="!open" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        <svg x-show="open" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" class="absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto z-10">
                        <!-- Search Input -->
                        <div class="px-4 py-2">
                            <input type="text" x-model="search" placeholder="جست و جو" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Dropdown Options -->
                        <ul class="space-y-1">
                            <!-- Null Option -->
                            <li @click="select(null)" class="px-4 py-2 text-gray-600 hover:bg-blue-50 cursor-pointer rounded-lg">
                                <span>...</span>
                            </li>

                            <!-- User List -->
                            <template x-for="user in filteredOptions" :key="user.id">
                                <li @click="select(user)" class="px-4 py-2 text-gray-700 hover:bg-blue-50 cursor-pointer rounded-lg">
                                    <span x-text="user.full_name + ' - ' + (user.department ? user.department.department_name : 'No department') + ' - ' + user.position"></span>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <!-- Hidden input to store the selected user's ID -->
                    <input type="hidden" name="boss" x-bind:value="selected ? selected.id : ''">

                    <x-input-error :messages="$errors->get('boss')" class="mt-2"/>

                    <script>
                        function dropdown() {
                            return {
                                open: false,
                                search: '',
                                selected: null,
                                options: [],
                                initOptions(data) {
                                    this.options = data;
                                },
                                select(user) {
                                    this.selected = user;
                                    this.open = false;
                                    this.search = '';
                                },
                                get filteredOptions() {
                                    if (!this.search) return this.options;
                                    return this.options.filter(user =>
                                        user.full_name.toLowerCase().includes(this.search.toLowerCase()) ||
                                        (user.department && user.department.department_name.toLowerCase().includes(this.search.toLowerCase())) ||
                                        (user.position && user.position.toLowerCase().includes(this.search.toLowerCase()))
                                    );
                                }
                            }
                        }
                    </script>
                </div>


                <div>
                    <x-input-label for="scriptorium" :value="__('نام دبیر جلسه')"/>
                    <x-text-input name="scriptorium" id="scriptorium"
                                  value="{{ old('scriptorium', auth()->user()->full_name() ?? '') }}"
                                  class="block " type="text" autofocus/>
                    <x-input-error :messages="$errors->get('scriptorium')"/>
                </div>
                <div>
                    <x-input-label for="unit_organization" :value="__('واحد سازمانی')"/>
                    <x-text-input name="unit_organization" id="unit_organization"
                                  value="{{ old('unit_organization', auth()->user()->user_info->department->department_name ?? '') }}"
                                  class="block " type="text" autofocus/>
                    <x-input-error :messages="$errors->get('unit_organization')"/>
                </div>

                <div>
                    <x-input-label for="position_organization" :value="__('سمت دبیر جلسه')"/>
                    <x-text-input name="position_organization" id="position_organization"
                                  value="{{ old('position_organization', auth()->user()->user_info->position ?? '') }}"
                                  class="block " type="text" autofocus/>
                    <x-input-error :messages="$errors->get('position_organization')"/>
                </div>
                <div>
                    <x-input-label for="unit_held" :value="__('کمیته یا واحد برگزار کننده جلسه')"/>
                    <x-text-input name="unit_held" id="unit_held"
                                  value="{{old('applicant')}}"
                                  class="block" type="text" autofocus/>
                    <x-input-error :messages="$errors->get('unit_held')"/>
                </div>
                <div>
                    <x-input-label for="applicant" :value="__('نام درخواست دهنده جلسه')"/>
                    <x-text-input name="applicant" id="applicant"
                                  value="{{old('applicant')}}"
                                  class="block" type="text" autofocus/>
                    <x-input-error :messages="$errors->get('applicant')"/>
                </div>

            </div>


            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 mt-4 border-b pb-2">
                {{ __('بخش اعضا و مهمان') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2 mb-2">
{{--                            Participants Information--}}
                <div class="col-span-2">
                    <x-input-label for="holders" class="mb-2"
                                   :value="__('انتخاب اعضای جلسه یا حاضرین در جلسه')"/>
                    <div class="custom-select">
                        <div class="select-box">
                            <input type="text" class="tags_input" multiple name="holders" hidden>
                            <div class="selected-options"></div>
                            <div class="arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                                </svg>
                            </div>
                        </div>
                        <div class="options">
                            <div class="option-search-tags">
                                <input type="text" class="search-tags" placeholder="جست و جو ...">
                                <button type="button" class="clear">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5"
                                         stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6 18 18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="option all-tags" data-value="All">{{__('انتخاب همه')}}</div>
                            @foreach($users->where('user_id','!=',auth()->user()->id) as $user)
                                <div class="option" data-value="{{$user->user_id}}">{{$user->full_name}}</div>
                            @endforeach
                            <div class="no-result-message" style="display:none;">No result match</div>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('holders')"/>
                </div>
{{--                            Guests--}}
                <div class="col-span-2">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">{{__('لیست مهمانان')}}</label>
                        <div class="flex items-center space-x-4 mb-4">
                            <input type="checkbox" id="outer-organization-checkbox" class="h-4 w-4 text-blue-600"/>
                            <label for="outer-organization-checkbox"
                                   class="cursor-pointer text-sm font-medium text-gray-700">{{__('برون سازمانی')}}</label>
                        </div>
                        <!-- Table for inner_organization (default table) -->
                        <div id="inner-organization-table"
                             class="overflow-x-auto rounded-lg border border-gray-300 shadow mb-4">
                            <table id="guests-inner-table"
                                   class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-right">#</th>
                                    <th class="px-4 py-2 text-right">نام و نام خانوادگی</th>
                                    <th class="px-4 py-2 text-right">واحد سازمانی</th>
                                    <th class="px-4 py-2 text-center">عملیات</th>
                                </tr>
                                </thead>
                                <tbody id="guests-inner-tbody" class="divide-y divide-gray-100">
                                <!-- Dynamic rows for inner organization will be inserted here -->
                                </tbody>
                                <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-left">
                                        <button type="button" id="add-inner-guest-btn"
                                                class="inline-flex items-center px-4 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                            افزودن مهمان
                                        </button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Table for outer_organization (will appear when checkbox is checked) -->
                        <div id="outer-organization-table"
                             class="overflow-x-auto rounded-lg border border-gray-300 shadow mb-4 hidden">
                            <table id="guests-outer-table"
                                   class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-right">#</th>
                                    <th class="px-4 py-2 text-right">نام و نام خانوادگی</th>
                                    <th class="px-4 py-2 text-right">نام شرکت</th>
                                    <th class="px-4 py-2 text-center">عملیات</th>
                                </tr>
                                </thead>
                                <tbody id="guests-outer-tbody" class="divide-y divide-gray-100">
                                <!-- Dynamic rows for outer organization will be inserted here -->
                                </tbody>
                                <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-left">
                                        <button type="button" id="add-outer-guest-btn"
                                                class="inline-flex items-center px-4 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                            افزودن مهمان
                                        </button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <x-input-error :messages="$errors->get('guest')" class="mt-2"/>
                    </div>
                </div>

                <script>
                    // Initialize empty arrays for guests
                    let innerGuests = @json(old('guests.inner', [])); // Use old data if available
                    let outerGuests = @json(old('guests.outer', [])); // Use old data if available

                    // Get references to the DOM elements
                    const innerGuestsTbody = document.getElementById('guests-inner-tbody');
                    const outerGuestsTbody = document.getElementById('guests-outer-tbody');
                    const addInnerGuestBtn = document.getElementById('add-inner-guest-btn');
                    const addOuterGuestBtn = document.getElementById('add-outer-guest-btn');
                    const outerOrganizationCheckbox = document.getElementById('outer-organization-checkbox');
                    const innerOrganizationTable = document.getElementById('inner-organization-table');
                    const outerOrganizationTable = document.getElementById('outer-organization-table');

                    // Function to add a new inner organization guest
                    function addInnerGuest() {
                        const index = innerGuests.length;
                        const guest = {name: '', department: ''}; // Default empty values
                        innerGuests.push(guest);
                        renderInnerGuests(); // Re-render the inner guest table
                    }

                    // Function to add a new outer organization guest
                    function addOuterGuest() {
                        const index = outerGuests.length;
                        const guest = {name: '', companyName: ''}; // Default empty values
                        outerGuests.push(guest);
                        renderOuterGuests(); // Re-render the outer guest table
                    }

                    // Function to remove an inner organization guest by index
                    function removeInnerGuest(index) {
                        innerGuests.splice(index, 1);
                        renderInnerGuests();
                    }

                    // Function to remove an outer organization guest by index
                    function removeOuterGuest(index) {
                        outerGuests.splice(index, 1);
                        renderOuterGuests();
                    }

                    // Function to update an inner organization guest's data
                    function updateInnerGuest(index, field, value) {
                        innerGuests[index][field] = value;
                    }

                    // Function to update an outer organization guest's data
                    function updateOuterGuest(index, field, value) {
                        outerGuests[index][field] = value;
                    }

                    // Function to render all inner organization guests as table rows
                    function renderInnerGuests() {
                        innerGuestsTbody.innerHTML = '';
                        innerGuests.forEach((guest, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                <td class="px-4 py-2">${index + 1}</td>
                <td class="px-4 py-2"><input type="text" name="guests[inner][${index}][name]" value="${guest.name}" class="w-full" placeholder="نام مهمان" /></td>
                <td class="px-4 py-2"><input type="text" name="guests[inner][${index}][department]" value="${guest.department}" class="w-full" placeholder="واحد سازمانی" /></td>
                <td class="px-4 py-2 text-center"><button type="button" class="text-red-600" onclick="removeInnerGuest(${index})">×</button></td>
            `;
                            innerGuestsTbody.appendChild(row);
                        });
                    }

                    // Function to render all outer organization guests as table rows
                    function renderOuterGuests() {
                        outerGuestsTbody.innerHTML = '';
                        outerGuests.forEach((guest, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                <td class="px-4 py-2">${index + 1}</td>
                <td class="px-4 py-2"><input type="text" name="guests[outer][${index}][name]" value="${guest.name}" class="w-full" placeholder="نام مهمان" /></td>
                <td class="px-4 py-2"><input type="text" name="guests[outer][${index}][companyName]" value="${guest.companyName}" class="w-full" placeholder="نام شرکت" /></td>
                <td class="px-4 py-2 text-center"><button type="button" class="text-red-600" onclick="removeOuterGuest(${index})">×</button></td>
            `;
                            outerGuestsTbody.appendChild(row);
                        });
                    }

                    // Event listener for adding guests
                    addInnerGuestBtn.addEventListener('click', addInnerGuest);
                    addOuterGuestBtn.addEventListener('click', addOuterGuest);

                    // Toggle the visibility of the outer organization table based on checkbox
                    outerOrganizationCheckbox.addEventListener('change', () => {
                        if (outerOrganizationCheckbox.checked) {
                            outerOrganizationTable.classList.remove('hidden');
                        } else {
                            outerOrganizationTable.classList.add('hidden');
                        }
                    });

                    // Initial render of tables
                    renderInnerGuests();
                </script>

                <div class="col-span-1 mt-12">
                    <x-primary-button type="submit">
                        {{ __('ارسال') }}
                    </x-primary-button>
                    <a href="{{route('dashboard.meeting')}}">
                        <x-secondary-button>
                            {{__('لغو')}}
                        </x-secondary-button>
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>

