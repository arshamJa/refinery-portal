@php use App\Enums\UserPermission;use App\Enums\UserRole; @endphp
<x-app-layout>
    @can('has-permission',UserPermission::SCRIPTORIUM_PERMISSIONS)
        <x-breadcrumb>
            <li class="flex items-center h-full">
                <a href="{{route('dashboard')}}"
                   class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
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
                   class="inline-flex items-center px-2 gap-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    <span>{{__('جدول جلسات')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
            <span
                class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
               {{__('ویرایش جلسه : ')}}{{$meeting->title}}
            </span>
            </li>
        </x-breadcrumb>

        <form action="{{route('meeting.update',$meeting->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <div class="p-4 mb-2 sm:p-8 bg-white dark:bg-gray-800 drop-shadow-xl sm:rounded-lg">

                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 border-b pb-2">
                    {{ __('ویرایش بخش اطلاعات جلسه') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2">
                    {{-- Meeting Title--}}
                    <div>
                        <x-input-label for="title" :value="__('موضوع جلسه')"/>
                        <x-text-input name="title" id="title" value="{{$meeting->title}}" class="block " type="text"
                                      autofocus/>
                        <x-input-error :messages="$errors->get('title')"/>
                    </div>
                    {{-- Meeting Location--}}
                    <div>
                        <x-input-label for="location" :value="__('محل برگزاری جلسه')"/>
                        <x-text-input name="location" id="location" value="{{$meeting->location}}" class="block "
                                      type="text" autofocus/>
                        <x-input-error :messages="$errors->get('location')"/>
                    </div>
                    {{-- Treating--}}
                    <div>
                        <x-input-label for="treat" :value="__('پذیرایی')"/>
                        <label for="yes">{{ __('بلی') }}
                            <input type="radio" name="treat" value="1"
                                {{ old('treat', $meeting->treat) ? 'checked' : '' }}>
                        </label>
                        <label for="no" class="mr-3">{{ __('خیر') }}
                            <input type="radio" name="treat" value="0"
                                {{ old('treat', $meeting->treat) ? '' : 'checked' }}>
                        </label>
                        <x-input-error :messages="$errors->get('treat')"/>
                    </div>
                </div>


                {{-- Time & Date--}}
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
                                      value="{{$meeting->time}}"
                                      class="block" type="text" placeholder="{{__('00:00')}}"
                                      autofocus/>
                        <x-input-error :messages="$errors->get('time')"/>
                    </div>
                </div>


                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 mt-4 border-b pb-2">
                    {{ __('ویرایش بخش دبیرجلسه') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2">

                    @php
                        // Your existing exclusion/filtering logic
                        $selectedParticipantIds = [];
                        if (old('holders')) {
                            $selectedParticipantIds = explode(',', old('holders'));
                        } elseif (isset($meeting)) {
                            $selectedParticipantIds = $meeting->meetingUsers
                                ->where('is_guest', false)->pluck('user_id')->map(fn($id) => (string) $id)
                                ->toArray();
                        }
                        $innerGuestIds = $innerGuests->pluck('user_id')->map(fn($id) => (string) $id)->toArray();
                        $authUserId = (string) auth()->id();
                        $excludedUserIds = array_merge($selectedParticipantIds, $innerGuestIds, [$authUserId]);
                        $filteredUsers = $users->filter(fn($user) => !in_array((string)$user->user_id, $excludedUserIds))->values();

                        $oldBossId = old('boss', $meeting->boss_id ?? null);

                        // Try to find boss user info in filtered users
                        $bossUserInfo = $filteredUsers->firstWhere('user_id', $oldBossId);

                        // If boss not found (because excluded), try to find boss in all users to display their info
                        if (!$bossUserInfo && $oldBossId) {
                            $bossUserInfo = $users->firstWhere('user_id', $oldBossId);
                        }
                    @endphp

                    <div id="boss_dropdown" data-users='@json($filteredUsers)' class="relative w-full col-span-2"
                         style="direction: rtl;">
                        <x-input-label class="mb-1.5" :value="__('رئیس جلسه')"/>
                        <button id="boss-dropdown-btn" type="button"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-right text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                            <span id="boss-selected-text" class="truncate">
                                @if ($bossUserInfo)
                                    <div>
                                        <div><strong>{{ $bossUserInfo->full_name }}</strong></div>
                                        <div class="text-xs text-gray-500">
                                            {{ $bossUserInfo->department->department_name ?? 'نامشخص' }} - {{ $bossUserInfo->position ?? '' }}
                                        </div>
                                    </div>
                                @else
                                    انتخاب کنید
                                @endif
                            </span>
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="boss-dropdown-menu"
                             class="hidden absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg z-10">
                            <div class="px-4 py-2">
                                <input id="boss-dropdown-search" type="text" placeholder="جست و جو"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>
                            <ul id="boss-dropdown-list" class="max-h-48 overflow-auto"></ul>
                            <div id="boss-no-result" class="px-4 py-2 text-gray-500 hidden">موردی یافت نشد</div>
                        </div>

                        <input type="hidden" name="boss" id="boss-hidden-input" value="{{ $oldBossId }}">
                        <x-input-error :messages="$errors->get('boss')" class="mt-2"/>
                    </div>

                    @php
                        $authUserId = auth()->id();
                        $authUserInfo = $users->firstWhere('user_id', $authUserId);
                    @endphp
                    <div id="scriptorium_display" class="relative w-full col-span-2" style="direction: rtl;">
                        <x-input-label class="mb-1.5" :value="__('دبیر جلسه')"/>
                        <div
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-right text-gray-800 bg-gray-50 flex flex-col cursor-default">
                            @if ($authUserInfo)
                                <div>
                                    <div><strong>{{ $authUserInfo->full_name }}</strong></div>
                                    <div class="text-xs text-gray-500">
                                        {{-- Access department relation --}}
                                        {{ $authUserInfo->department->department_name ?? 'نامشخص' }}
                                        - {{ $authUserInfo->position ?? '' }}
                                    </div>
                                </div>
                            @else
                                <div class="text-gray-500">نامشخص</div>
                            @endif
                        </div>
                    </div>
                    <div>
                        <x-input-label for="unit_held" :value="__('کمیته یا واحد برگزار کننده جلسه')"/>
                        <x-text-input name="unit_held" id="unit_held"
                                      value="{{ $meeting->unit_held }}"
                                      class="block" type="text" autofocus/>
                        <x-input-error :messages="$errors->get('unit_held')"/>
                    </div>
                </div>


                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 mt-4 border-b pb-2">
                    {{ __('ویرایش بخش اعضا و مهمان') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-4 mb-2 gap-4">
                    <div class="col-span-2">
                        <div class="mb-2">{{ __('اعضای جلسه فعلی') }}:</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($userIds as $meetingUser)
                                @if($meetingUser->user_id !== $meeting->boss_id)
                                    <div id="user-{{ $meetingUser->user_id }}"
                                         class="flex items-center gap-2 bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-sm">
                                        <span>{{ $meetingUser->user->user_info->full_name }}</span>
                                        <button type="button"
                                                class="text-blue-600 hover:text-blue-800 focus:outline-none focus:ring-1 focus:ring-blue-300 rounded-full p-1.5"
                                                onclick="deleteUser(event, {{ $meeting->id }}, {{ $meetingUser->user_id }})"
                                                title="حذف">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                            <script>
                                function deleteUser(event, meetingId, userId) {
                                    event.preventDefault(); // Prevent any default behavior (like form submission)

                                    if (!confirm("آیا مطمئن هستید که این کاربر را حذف می‌کنید؟")) {
                                        return;
                                    }

                                    fetch(`/meetings/${meetingId}/users/${userId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',  // This will add CSRF token
                                            'Content-Type': 'application/json'
                                        },
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.status) {
                                                document.getElementById(`user-${userId}`).remove();
                                                alert(data.status);
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            alert('مشکلی پیش آمده است.');
                                        });
                                }
                            </script>
                        </div>
                    </div>

                    {{-- Participants Dropdown (multi-select) --}}
                    @php
                        // Parse old input or fallback to meeting participants user_ids as array
                        $selectedParticipantIds = [];
                        if (old('holders')) {
                            $selectedParticipantIds = explode(',', old('holders'));
                        } elseif (isset($meeting)) {
                            $selectedParticipantIds = $meeting->meetingUsers
                                ->where('is_guest', false)
                                ->pluck('user_id')
                                ->map(fn($id) => (string) $id)
                                ->toArray();
                        }
                        // Get inner guests user_ids as strings
                        $innerGuestIds = $innerGuests->pluck('user_id')->map(fn($id) => (string) $id)->toArray();
                        // Combine IDs to exclude both selected participants and inner guests
                        $excludedUserIds = array_merge($selectedParticipantIds, $innerGuestIds);
                        // Filter participants to exclude all excluded user IDs
                        $filteredParticipants = $participants->filter(function ($user) use ($excludedUserIds) {
                            return !in_array((string) $user->user_id, $excludedUserIds);
                        })->values();
                    @endphp
                    <div id="participants_dropdown" data-users='@json($filteredParticipants)'
                         class="relative w-full col-span-2" style="direction: rtl;">
                        <x-input-label class="mb-1.5" :value="__('شرکت‌کنندگان')"/>
                        <button id="participants-dropdown-btn" type="button"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-right text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                            <span id="participants-selected-text" class="truncate">انتخاب شرکت‌کنندگان</span>
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="participants-dropdown-menu"
                             class="hidden absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg z-10">
                            <div class="px-4 py-2">
                                <input id="participants-dropdown-search" type="text" placeholder="جست و جو"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>
                            <ul id="participants-dropdown-list" class="max-h-48 overflow-auto"></ul>
                            <div id="participants-no-result" class="px-4 py-2 text-gray-500 hidden">موردی یافت نشد</div>
                        </div>
                        <div id="participants-selected-container" class="mt-2 flex flex-wrap gap-2"></div>
                        <input type="hidden" name="holders" id="participants-hidden-input"
                               value="{{ old('holders') ?? implode(',', $selectedParticipantIds) }}">
                        <x-input-error :messages="$errors->get('holders')" class="mt-2"/>
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 mb-2 gap-4">

                    <div class="col-span-2 space-y-4 ">
                        <h3 class="text-md font-medium mb-2 text-gray-700">{{ __('لیست مهمان درون سازمانی فعلی:') }}</h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach($innerGuests as $innerGuest)
                                <div id="guest-{{ $innerGuest->user_id }}"
                                     class="flex items-center gap-2 bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-sm">
                                <span class="truncate">
                                    {{ $innerGuest->user->user_info->full_name }} - {{ $innerGuest->department_name }}
                                </span>
                                    <button type="button"
                                            class="text-blue-600 hover:text-blue-800 focus:outline-none focus:ring-1 focus:ring-blue-300 rounded-full p-1.5"
                                            onclick="deleteGuest(event, {{ $meeting->id }}, {{ $innerGuest->user_id }})"
                                            title="حذف مهمان">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <script>
                                function deleteGuest(event, meetingId, guestId) {
                                    event.preventDefault(); // Prevent any default behavior (like form submission)

                                    // Confirmation message before deletion
                                    if (!confirm("آیا مطمئن هستید که این مهمان را حذف می‌کنید؟")) {
                                        return;
                                    }

                                    // Send a DELETE request to the backend
                                    fetch(`/guests/${guestId}/delete`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',  // CSRF token for security
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            meeting_id: meetingId,  // Pass the meeting ID
                                            guest_id: guestId       // Pass the guest ID
                                        })
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.status) {
                                                // If deletion is successful, remove the guest from the DOM
                                                document.getElementById(`guest-${guestId}`).remove();
                                                alert(data.status);
                                            } else {
                                                alert(data.status);
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            alert('مشکلی پیش آمده است.');
                                        });
                                }
                            </script>
                        </div>
                    </div>

                    {{-- Inner Guests Dropdown (multi-select) --}}
                    <div id="innerGuest_dropdown" data-users='@json($filteredParticipants)'
                         class="relative w-full col-span-2"
                         style="direction: rtl;">
                        <x-input-label class="mb-1.5" :value="__('مهمانان داخلی')"/>
                        <button id="innerGuest-dropdown-btn" type="button"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-right text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                            <span id="innerGuest-selected-text" class="truncate">انتخاب مهمانان داخلی</span>
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="innerGuest-dropdown-menu"
                             class="hidden absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg z-10">
                            <div class="px-4 py-2">
                                <input id="innerGuest-dropdown-search" type="text" placeholder="جست و جو"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>
                            <ul id="innerGuest-dropdown-list" class="max-h-48 overflow-auto"></ul>
                            <div id="innerGuest-no-result" class="px-4 py-2 text-gray-500 hidden">موردی یافت نشد</div>
                        </div>
                        <div id="innerGuest-selected-container" class="mt-2 flex flex-wrap gap-2"></div>
                        <input type="hidden" name="innerGuest" id="innerGuest-hidden-input"
                               value="{{ old('innerGuest') ?? '' }}">
                        <x-input-error :messages="$errors->get('innerGuest')" class="mt-2"/>
                    </div>

                    <div class="col-span-2 mt-2">
                        <h3 class="text-md font-medium mb-2 text-gray-700">{{ __('لیست مهمان برون سازمانی فعلی:') }}</h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach($meeting->guest ?? [] as $index => $guest)
                                <div id="guest-{{ $index }}"
                                     class="flex items-center gap-2 bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-sm">
                                    <span class="truncate">
                                        {{ $guest['name'] ?? 'مهمان ناشناس' }} - {{ $guest['companyName'] ?? 'شرکت نامشخص' }}
                                    </span>
                                    <button type="button"
                                            class="text-blue-600 hover:text-blue-800 focus:outline-none focus:ring-1 focus:ring-blue-300 rounded-full p-1.5"
                                            onclick="deleteOuterGuest(event, {{ $meeting->id }}, '{{ $index }}')"
                                            title="حذف مهمان">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <script>
                                function deleteOuterGuest(event, meetingId, guestIndex) {
                                    event.preventDefault(); // Prevent any default behavior (like form submission)

                                    // Confirmation message before deletion
                                    if (!confirm("آیا مطمئن هستید که این مهمان را حذف می‌کنید؟")) {
                                        return;
                                    }

                                    // Send a DELETE request to the backend
                                    fetch(`/meetings/${meetingId}/guests/${guestIndex}/delete`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',  // CSRF token for security
                                        }
                                    })
                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error('Request failed');
                                            }
                                            return response.json();
                                        })
                                        .then(data => {
                                            if (data.status) {
                                                // If deletion is successful, remove the guest from the DOM
                                                const guestElement = document.getElementById(`guest-${guestIndex}`);
                                                if (guestElement) {
                                                    guestElement.remove();
                                                }
                                                alert(data.status);
                                            } else {
                                                alert('حذف مهمان با مشکل مواجه شد.');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            alert('مشکلی پیش آمده است.');
                                        });
                                }
                            </script>

                        </div>
                    </div>

                    <div class="col-span-2 mt-2">
                        {{--  Guests --}}
                        <div class="col-span-2">
                            {{-- Hidden data element for JS --}}
                            <div id="guests-data" data-outer-guests="{{ json_encode(old('guests.outer', [])) }}"></div>
                            <div id="outer-organization-table"
                                 class="overflow-x-auto rounded-lg border border-gray-300 shadow mb-4">
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
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-4">
                    <x-primary-button type="submit" class="ml-2">
                        {{ __('بروزرسانی') }}
                    </x-primary-button>
                    <a href="{{route('dashboard.meeting')}}">
                        <x-cancel-button>
                            {{__('لغو')}}
                        </x-cancel-button>
                    </a>
                </div>
            </div>
        </form>
        <script src="{{ asset('js/outerGuest.js') }}"></script>
    @endcan
</x-app-layout>

