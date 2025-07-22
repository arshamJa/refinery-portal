<x-app-layout>

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
            <span
                class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                {{__('ساخت کاربر جدبد')}}
            </span>
        </li>
    </x-breadcrumb>


@can('users-info')
        <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 drop-shadow-md sm:rounded-lg">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 border-b pb-2">
                    {{ __('ساخت کاربر جدید') }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2">
                    <div>
                        <x-input-label for="role" :value="__('نقش')"/>
                        <x-select-input name="role">
                            <option>...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected(old('role') == $role->id)>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('role')" class="my-2"/>
                    </div>
                    <div>
                        <x-input-label for="full_name" :value="__('نام و نام خانوادگی')"/>
                        <x-text-input name="full_name" id="full_name" value="{{old('full_name')}}" class="block"
                                      type="text"
                                      autofocus/>
                        <x-input-error :messages="$errors->get('full_name')"/>
                    </div>
                    <div>
                        <x-input-label for="p_code" :value="__('شماره پرسنلی')"/>
                        <x-text-input name="p_code" id="p_code" value="{{old('p_code')}}" class="block" type="text"
                                      maxlength="6" autofocus/>
                        <x-input-error :messages="$errors->get('p_code')"/>
                    </div>
                    <div>
                        <x-input-label for="n_code" :value="__('کد ملی')"/>
                        <x-text-input name="n_code" id="n_code" value="{{old('n_code')}}" class="block" type="text"
                                      maxlength="10" autofocus/>
                        <x-input-error :messages="$errors->get('n_code')"/>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2">
                    <div>
                        <x-input-label for="phone" :value="__('تلفن همراه')"/>
                        <x-text-input name="phone" id="phone" value="{{old('phone')}}" class="block" type="text"
                                      maxlength="11" autofocus/>
                        <x-input-error :messages="$errors->get('phone')"/>
                    </div>
                    <div>
                        <x-input-label for="house_phone" :value="__('تلفن منزل')"/>
                        <x-text-input name="house_phone" id="house_phone" value="{{old('house_phone')}}" class="block"
                                      type="text" autofocus/>
                        <x-input-error :messages="$errors->get('house_phone')"/>
                    </div>
                    <div>
                        <x-input-label for="work_phone" :value="__('تلفن محل کار')"/>
                        <x-text-input name="work_phone" id="work_phone" value="{{old('work_phone')}}" class="block"
                                      type="text" autofocus/>
                        <x-input-error :messages="$errors->get('work_phone')"/>
                    </div>
                    <div>
                        <x-input-label for="position" :value="__('سمت')"/>
                        <x-text-input name="position" id="position" value="{{old('position')}}" class="block"
                                      type="text"
                                      autofocus/>
                        <x-input-error :messages="$errors->get('position')"/>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2">
                    <div>
                        <x-input-label :value="__('دپارتمان')"/>
                        <x-select-input name="departmentId">
                            <option value="">...</option>
                            @foreach($departments as $department)
                                <option
                                    value="{{$department->id}}" @selected(old('departmentId') == $department->id)>{{$department->department_name}}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('departmentId')" class="my-2"/>
                    </div>
                    <div id="organizations_dropdown" data-users='@json($organization)' class="relative w-full col-span-2" style="direction: rtl;">
                        <x-input-label class="mb-1.5" :value="__('سازمان‌ها')"/>
                        <button id="organizations-dropdown-btn" type="button"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-right text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                            <span id="organizations-selected-text" class="truncate">انتخاب سازمان‌ها</span>
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="organizations-dropdown-menu"
                             class="hidden absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg z-10">
                            <div class="px-4 py-2">
                                <input id="organizations-dropdown-search" type="text" placeholder="جست و جو"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>
                            <ul id="organizations-dropdown-list" class="max-h-48 overflow-auto"></ul>
                            <div id="organizations-no-result" class="px-4 py-2 text-gray-500 hidden">موردی یافت نشد</div>
                        </div>
                        <div id="organizations-selected-container" class="mt-2 flex flex-wrap gap-2"></div>
                        <input type="hidden" name="organization" id="organizations-hidden-input"
                               value='{{ json_encode(explode(",", old("organization", ""))) }}'>
                        <x-input-error :messages="$errors->get('organization')" class="mt-2"/>
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('رمز')"/>
                        <x-text-input name="password" id="password" class="block" type="text"  autofocus/>
                        <x-input-error :messages="$errors->get('password')" class="my-2"/>
                    </div>
                    <div>
                        <x-input-label for="password_confirmation" :value="__('تکرار رمز')"/>
                        <x-text-input name="password_confirmation" id="password_confirmation" class="block" type="text" autofocus />
                        <x-input-error :messages="$errors->get('password_confirmation')"/>
                    </div>
                    <div>
                        <x-input-label for="signature" :value="__('امضا')"/>
                        <x-text-input name="signature" id="signature" value="{{old('signature')}}"
                                      class="block p-2" type="file" autofocus/>
                        <x-input-error :messages="$errors->get('signature')"/>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2 mb-4">
                    <div class="col-span-4">
                        <x-input-label :value="__('تعیین بخش دسترسی:')"/>
                        @foreach($permissions as $permission)
                            <div>
                                <input type="checkbox" name="permissions[{{$permission->name}}]"
                                       value="{{$permission->name}}" @checked(old('permissions.' . $permission->name))>
                                <label>{{ $permission->name }}</label>
                            </div>
                        @endforeach
                        <x-input-error :messages="$errors->get('permissions')"/>

                    </div>
                </div>

                <div class="flex gap-4">
                    <x-primary-button type="submit">
                        {{ __('ذخیره') }}
                    </x-primary-button>
                    <a href="{{route('users.index')}}">
                        <x-cancel-button>
                            {{__('لغو')}}
                        </x-cancel-button>
                    </a>
                </div>
            </div>
        </form>
    @endcan
</x-app-layout>
