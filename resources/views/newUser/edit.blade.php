<x-app-layout>

    <x-header header="ویرایش کاربر"/>
    <div class="py-12 bg-gray-100" dir="rtl">
        <div class="max-w-7xl sm:px-6 lg:px-8 space-y-6">
            <form action="{{route('newUser.update',$userInfo->id)}}" method="POST">
                @csrf
                @method('put')
                <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <h2 class="text-lg mb-4 font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('اطلاعات شخصی') }}
                                </h2>
                            </header>
                            <div>
                                <x-input-label for="role" :value="__('نقش')"/>
                                <select dir="ltr" name="role"  id="role" class="my-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option selected>...</option>
                                    <option value="ادمین">{{__('ادمین')}}</option>
                                    <option value="اپراتور">{{__('اپراتور')}}</option>
                                    <option value="کاربر">{{__('کارمند')}}</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="my-2"/>

                                <x-input-label for="full_name" :value="__('نام و نام خانوادگی')"/>
                                <x-text-input name="full_name" id="full_name" value="{{$userInfo->full_name}}"
                                              class="block my-2 w-full"
                                              type="text" autofocus/>
                                <x-input-error :messages="$errors->get('full_name')" class="my-2"/>

                                <x-input-label for="p_code" :value="__('شماره پرسنلی')"/>
                                <x-text-input name="p_code" id="p_code" value="{{$userInfo->user->p_code}}"
                                              class="block my-2 w-full" maxlength="6"
                                              type="text" autofocus/>
                                <x-input-error :messages="$errors->get('p_code')" class="my-2"/>

                                <x-input-label for="n_code" :value="__('کد ملی')"/>
                                <x-text-input name="n_code" id="n_code" value="{{$userInfo->n_code}}"
                                              class="block my-2 w-full" maxlength="10"
                                              type="text"  autofocus/>
                                <x-input-error :messages="$errors->get('n_code')" class="my-2"/>

                                <x-input-label for="phone" :value="__('شماره همراه')"/>
                                <x-text-input name="phone" id="phone" maxlength="11"
                                              value="{{$userInfo->phone}}" class="block my-2 w-full"
                                              type="text"  autofocus/>
                                <x-input-error :messages="$errors->get('phone')" class="my-2"/>

                                <x-input-label for="house_phone" :value="__('شماره منزل')"/>
                                <x-text-input name="house_phone" id="house_phone" value="{{$userInfo->house_phone}}"
                                              class="block my-2 w-full" type="text"  autofocus/>
                                <x-input-error :messages="$errors->get('house_phone')" class="my-2"/>

                                <x-input-label for="work_phone" :value="__('شماره محل کار')"/>
                                <x-text-input name="work_phone" id="work_phone" value="{{$userInfo->work_phone}}"
                                              class="block my-2 w-full" type="text"  autofocus/>
                                <x-input-error :messages="$errors->get('work_phone')" class="my-2"/>

                                <x-input-label for="position" :value="__('سمت')"/>
                                <x-text-input name="position" id="position" value="{{$userInfo->position}}"
                                              class="block my-2 w-full" type="text"  autofocus/>
                                <x-input-error :messages="$errors->get('position')" class="my-2"/>

                                @if($userInfo->department->department_name)
                                    {{__('دپارتمان فعلی')}} : {{$userInfo->department?->department_name}}
                                @else
                                    <span class="text-red-500">{{__('کاربر در هیچ دپارتمانی نبوده')}}</span>
                                @endif
                                <br>

                                <x-input-label :value="__('دپارتمان جدید')" class="mb-2"/>
                                <select name="departmentId" data-hs-select='{
                                              "hasSearch": true,
                                              "searchPlaceholder": "جست و جو ..",
                                              "searchClasses": "block w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-[1] dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3",
                                              "searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",
                                              "placeholder": "انتخاب دپارتمان ...",
                                              "toggleTag": "<button type=\"button\" aria-expanded=\"false\"><span class=\"me-2\" data-icon></span><span class=\"text-gray-800 dark:text-neutral-200 \" data-title></span></button>",
                                              "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600",
                                              "dropdownClasses": "mt-2 max-h-72 pb-1 px-1 space-y-0.5 z-20 w-full bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                                              "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                                              "optionTemplate": "<div><div class=\"flex items-center\"><div class=\"me-2\" data-icon></div><div class=\"text-gray-800 dark:text-neutral-200 \" data-title></div></div></div>",
                                              "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                                            }' class="hidden">
                                    <option></option>
                                    @foreach($departments as $department)
                                        <option
                                            value="{{$department->id}}">{{$department->department_name}}</option>
                                    @endforeach
                                </select>
                                <x-input-label for="password" :value="__('رمز')"/>
                                <x-text-input name="password" id="password"  maxlength="8"  value="{{old('password')}}"
                                              class="block my-2 w-full" type="text"  autofocus/>
                                <x-input-error :messages="$errors->get('password')" class="my-2"/>
                            </div>
                        </section>
                    </div>
                </div>



                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section class="space-y-6 mb-4">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{__('تعیین بخش دسترسی هر کاربر:')}}
                                </h2>
                            </header>
                            <div class="flex gap-x-2">
                                <x-input-label for="news" class="mr-2"/>{{__('اخبار و اطلاعیه')}}
                                <input type="checkbox" value="blog" name="blog" @checked(old('blog'))  class="w-4 h-4 text-gray-900 focus:ring-gray-700 rounded-xl translate-y-px">

                                <x-input-label for="phoneList" class="mr-2"/>{{__('دفترچه تلفنی')}}
                                <input type="checkbox" value="phoneList" name="phoneList" @checked(old('phoneList'))  class="w-4 h-4 text-gray-900 focus:ring-gray-700 rounded-xl translate-y-px">

                                <x-input-label for="chat" class="mr-2"/>{{__('چت')}}
                                <input type="checkbox" value="chat" name="chat" @checked(old('chat'))  class="w-4 h-4 text-gray-900 focus:ring-gray-700 rounded-xl translate-y-px">

                                <x-input-label for="dictionary" class="mr-2"/>{{__('دیکشنری')}}
                                <input type="checkbox" value="dictionary" name="dictionary" @checked(old('dictionary'))  class="w-4 h-4 text-gray-900 focus:ring-gray-700 rounded-xl translate-y-px">

                            </div>

                        </section>
                    </div>
                    <x-primary-button type="submit">
                        {{ __('ذخیره') }}
                    </x-primary-button>
                    <a href="{{route('newUser.index')}}">
                    <x-secondary-button>
                        {{__('لغو')}}
                    </x-secondary-button>
                    </a>

                </div>
            </form>
        </div>

    </div>


</x-app-layout>
