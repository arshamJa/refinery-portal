<x-app-layout>

    <x-header header="نمایش اطلاعات"/>


    <div class="py-12 bg-gray-100" dir="rtl">
        <div class="max-w-7xl sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg mb-4 font-medium text-gray-900 dark:text-gray-100">
                                {{ __('اطلاعات شخصی') }}
                            </h2>
                        </header>
                        <div>
                            <p class="mb-2">
                                {{__('نقش')}} : {{$userInfo->user->role}}
                            </p>

                            <p class="mb-2">
                                {{__('نام و نام خانوادگی')}} : {{$userInfo->full_name}}
                            </p>

                            <p class="mb-2">
                                {{__('کدپرسنلی')}} : {{$userInfo->user->p_code}}
                            </p>

                            <p class="mb-2">
                                {{__('کد ملی')}} : {{$userInfo->n_code}}
                            </p>


                            <p class="mb-2">
                                {{__('شماره همراه')}} : {{$userInfo->phone}}
                            </p>


                            <p class="mb-2">
                                {{__('شماره منزل')}} : {{$userInfo->house_phone}}
                            </p>
                            <p class="mb-2">
                                {{__('شماره محل کار')}} : {{$userInfo->work_phone}}
                            </p>

                            <p class="mb-2">
                                {{__('سمت')}} : {{$userInfo->position}}
                            </p>

                            <p class="mb-2">
                                {{__('دپارتمان')}} :
                                @if($userInfo->department_id)
                                    {{$userInfo->department->department_name}}
                                @else
                                    {{__('دپارتمان وجود ندارد')}}
                                @endif
                            </p>

                        </div>
                    </section>
                </div>
            </div>

            <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('سامانه های فعال') }}
                            </h2>
                            <p class="mt-2">
                            @foreach($users as $user)
                                @foreach ($user->organizations as $organization)
                                    {{$organization->organization_name}} -
                                @endforeach
                            @endforeach
                            </p>
                        </header>
                    </section>
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl mb-4">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{__(' بخش دسترسی کاربر')}}
                            </h2>
                        </header>
                        {{__('چت')}} = {{$userInfo->is_chat_allowed ? 'فعال' : 'غیر فعال'}}
                        <br>
                        {{__('اخبار و اطلاعیه')}} = {{$userInfo->is_blog_allowed ? 'فعال' : 'غیر فعال'}}
                        <br>
                        {{__('دیکشنری')}} = {{$userInfo->is_dictionary_allowed ? 'فعال' : 'غیر فعال'}}
                        <br>
                        {{__('دفترچه تلفنی')}} = {{$userInfo->is_phoneList_allowed ? 'فعال' : 'غیر فعال'}}
                    </section>
                </div>
                <x-secondary-button>
                    <a href="{{route('newUser.index')}}" class="flex">
                        {{__('بازگشت')}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-4 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/>
                        </svg>
                    </a>
                </x-secondary-button>
            </div>
        </div>
    </div>
</x-app-layout>
