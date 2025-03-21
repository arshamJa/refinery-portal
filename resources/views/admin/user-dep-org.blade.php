<x-app-layout>
    <div>
        <x-sessionMessage name="status"/>
{{--        <x-template>--}}
            <x-organizationDepartmentHeader/>
{{--            <div class="py-12 bg-gray-100" dir="rtl">--}}
{{--                <div class="max-w-7xl sm:px-6 lg:px-8 space-y-6">--}}
                    {{-- Relation Department & Organization--}}
                    <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <form action="{{route('departments.organizations.store')}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <section>
                                    <header>
                                        <h2 class="text-lg mb-4 font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('ارتباط دپارتمان با سامانه') }}
                                        </h2>
                                    </header>
                                    <div class="my-2">
                                        <x-input-label :value="__('دپارتمان')" class="mb-2"/>
                                        <div class="custom-select">
                                            <div class="select-box">
                                                <input type="text" class="tags_input" multiple name="departmentId" hidden>
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
                                                @foreach($departments as $department)
                                                    <div class="option" data-value="{{$department->id}}">{{$department->department_name}}</div>
                                                @endforeach

                                            </div>
                                        </div>
                                        <x-input-error :messages="$errors->get('departmentId')" class="my-2"/>
                                    </div>


                                    <div class="my-2">
                                        <x-input-label class="mb-2" :value="__('سامانه')"/>
                                        <div class="custom-select">
                                            <div class="select-box">
                                                <input type="text" class="tags_input" multiple name="organization_ids" hidden>
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
                                                @foreach($organizations as $organization)
                                                    <div class="option" data-value="{{$organization->id}}">{{$organization->organization_name}}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <x-input-error :messages="$errors->get('organization_ids')" class="my-2"/>
                                    </div>

{{--                                    <div class="my-2">--}}
{{--                                        <select multiple="" name="[]" data-hs-select='{--}}
{{--                                          "hasSearch": true,--}}
{{--                                          "isSearchDirectMatch": false,--}}
{{--                                          "searchPlaceholder": "جست و جو ...",--}}
{{--                                          "searchClasses": "block w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-[1] dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3",--}}
{{--                                          "searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",--}}
{{--                                          "placeholder": "انتخاب سامانه ...",--}}
{{--                                          "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",--}}
{{--                                          "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600",--}}
{{--                                          "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",--}}
{{--                                          "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",--}}
{{--                                          "optionTemplate": "<div class=\"flex items-center\"><div class=\"me-2\" data-icon></div><div><div class=\"hs-selected:font-semibold text-sm text-gray-800 \" data-title></div></div><div class=\"ms-auto\"><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-4 text-blue-600\" xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" viewBox=\"0 0 16 16\"><path d=\"M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z\"/></svg></span></div></div>",--}}
{{--                                          "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"--}}
{{--                                        }' class="hidden">--}}
{{--                                            --}}
{{--                                        </select>--}}
{{--                                    </div>--}}
                                </section>
                                <button type="submit"
                                        class="text-gray-100 bg-primary-100 bg-gray-900 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                    {{ __('ذخیره') }}
                                </button>
                            </form>
                        </div>
                    </div>


                    {{--                --}}{{-- Relation Department & Users--}}
                    {{--                <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">--}}
                    {{--                    <form action="{{route('departments.users')}}" method="post" enctype="multipart/form-data">--}}
                    {{--                        @csrf--}}
                    {{--                        <div class="max-w-xl">--}}
                    {{--                            <section>--}}
                    {{--                                <header>--}}
                    {{--                                    <h2 class="text-lg font-medium mb-4  text-gray-900 dark:text-gray-100">--}}
                    {{--                                        {{ __('ارتباط دپارتمان با کاربر') }}--}}
                    {{--                                    </h2>--}}
                    {{--                                </header>--}}
                    {{--                                <div>--}}
                    {{--                                    <x-input-label :value="__('دپارتمان')" class="mb-2"/>--}}
                    {{--                                    <select name="department_id" data-hs-select='{--}}
                    {{--                                              "hasSearch": true,--}}
                    {{--                                              "searchPlaceholder": "جست و جو ..",--}}
                    {{--                                              "searchClasses": "block w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-[1] dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3",--}}
                    {{--                                              "searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",--}}
                    {{--                                              "placeholder": "انتخاب دپارتمان ...",--}}
                    {{--                                              "toggleTag": "<button type=\"button\" aria-expanded=\"false\"><span class=\"me-2\" data-icon></span><span class=\"text-gray-800 dark:text-neutral-200 \" data-title></span></button>",--}}
                    {{--                                              "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600",--}}
                    {{--                                              "dropdownClasses": "mt-2 max-h-72 pb-1 px-1 space-y-0.5 z-20 w-full bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",--}}
                    {{--                                              "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",--}}
                    {{--                                              "optionTemplate": "<div><div class=\"flex items-center\"><div class=\"me-2\" data-icon></div><div class=\"text-gray-800 dark:text-neutral-200 \" data-title></div></div></div>",--}}
                    {{--                                              "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"--}}
                    {{--                                            }' class="hidden">--}}
                    {{--                                        <option>...</option>--}}
                    {{--                                        @foreach($departments as $department)--}}
                    {{--                                            <option--}}
                    {{--                                                value="{{$department->id}}">{{$department->department_name}}</option>--}}
                    {{--                                        @endforeach--}}
                    {{--                                    </select>--}}
                    {{--                                    <x-input-error :messages="$errors->get('department_id')" class="my-2"/>--}}
                    {{--                                </div>--}}
                    {{--                                <div>--}}
                    {{--                                    <x-input-label :value="__('کاربر')" class="mb-2"/>--}}
                    {{--                                    <select multiple="" name="userInfoIds[]" data-hs-select='{--}}
                    {{--                                          "hasSearch": true,--}}
                    {{--                                          "isSearchDirectMatch": false,--}}
                    {{--                                          "searchPlaceholder": "جست و جو ...",--}}
                    {{--                                          "searchClasses": "block w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-[1] dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3",--}}
                    {{--                                          "searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",--}}
                    {{--                                          "placeholder": "انتخاب کاربر ...",--}}
                    {{--                                          "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",--}}
                    {{--                                          "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600",--}}
                    {{--                                          "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",--}}
                    {{--                                          "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",--}}
                    {{--                                          "optionTemplate": "<div class=\"flex items-center\"><div class=\"me-2\" data-icon></div><div><div class=\"hs-selected:font-semibold text-sm text-gray-800 \" data-title></div></div><div class=\"ms-auto\"><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-4 text-blue-600\" xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" viewBox=\"0 0 16 16\"><path d=\"M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z\"/></svg></span></div></div>",--}}
                    {{--                                          "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"--}}
                    {{--                                        }' class="hidden">--}}
                    {{--                                        @foreach($userInfos as $userInfo)--}}
                    {{--                                            <option--}}
                    {{--                                                value="{{$userInfo->id}}">{{$userInfo->full_name}}</option>--}}
                    {{--                                        @endforeach--}}
                    {{--                                    </select>--}}
                    {{--                                    <x-input-error :messages="$errors->get('userInfoIds')" class="my-2"/>--}}
                    {{--                                </div>--}}
                    {{--                            </section>--}}
                    {{--                            <button type="submit"--}}
                    {{--                                    class="text-gray-100 bg-primary-100 bg-gray-900 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">--}}
                    {{--                                {{ __('ذخیره') }}--}}
                    {{--                            </button>--}}
                    {{--                        </div>--}}
                    {{--                    </form>--}}
                    {{--                </div>--}}


{{--                </div>--}}
{{--            </div>--}}
{{--        </x-template>--}}

    </div>
</x-app-layout>
