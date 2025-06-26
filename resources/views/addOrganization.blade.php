<x-app-layout>
    <nav class="flex justify-between mb-4 mt-20">
        <ol class="inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0">
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
            <li class="flex items-center h-full">
                <a href="{{route('organization.department.manage')}}"
                   class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    <span>{{__('مدیریت سامانه/دپارتمان')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                 stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
            <li>
            <span
                class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                {{__('افزودن سامانه برای ')}} <span class="underline underline-offset-2 mr-1">{{auth()->user()->user_info->full_name}}</span>
            </span>
            </li>
        </ol>
    </nav>
    <x-sessionMessage name="status"/>



    <div class="max-w-7xl space-y-6">

        {{-- Relation Department & Organization--}}
        <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">
                <form action="{{route('addOrganization.store',$id)}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <section>
                        <div>
                            <x-input-label :value="__('سامانه')" class="mb-2"/>
                            <select multiple="" name="organization_ids[]" data-hs-select='{
                                          "hasSearch": true,
                                          "isSearchDirectMatch": false,
                                          "searchPlaceholder": "جست و جو ...",
                                          "searchClasses": "block w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 before:absolute before:inset-0 before:z-[1] dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3",
                                          "searchWrapperClasses": "bg-white p-2 -mx-1 sticky top-0 dark:bg-neutral-900",
                                          "placeholder": "انتخاب سامانه ...",
                                          "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                                          "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600",
                                          "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                                          "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                                          "optionTemplate": "<div class=\"flex items-center\"><div class=\"me-2\" data-icon></div><div><div class=\"hs-selected:font-semibold text-sm text-gray-800 \" data-title></div></div><div class=\"ms-auto\"><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-4 text-blue-600\" xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" viewBox=\"0 0 16 16\"><path d=\"M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z\"/></svg></span></div></div>",
                                          "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                                        }' class="hidden">
                                @foreach($organizations as $organization)
                                    <option
                                        value="{{$organization->id}}">{{$organization->organization_name}}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('organization_ids')" class="my-2"/>
                        </div>
                    </section>
                    <x-primary-button type="submit" class="mt-6 ml-2">
                        {{ __('ذخیره') }}
                    </x-primary-button>
                    <x-secondary-button>
                        <a href="{{route('organization.department.manage')}}" class="flex">
                            {{__('بازگشت')}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5"
                                 stroke="currentColor" class="size-4 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/>
                            </svg>
                        </a>
                    </x-secondary-button>
                </form>
            </div>
        </div>
        <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">
                <section>
                    <header>
                        <h2 class="text-lg mb-4 font-medium text-gray-900 dark:text-gray-100">
                            {{ __('لیست سامانه های در دسترس کاربر') }}
                        </h2>
                    </header>
                    <div>
                        @foreach($users as $user)
                            <div class="my-1" wire:key="{{$user->id}}">
                                @foreach($user->organizations as $org)
                                    {{$org->organization_name}}
                                    <form action="{{route('addOrganization.delete',
                                                [ 'id' => $user->id , 'organizations' => $org->id ])}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button type="submit">{{__('حذف')}}</x-danger-button>
                                    </form>
                                    <br>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </section>

            </div>
        </div>
    </div>


</x-app-layout>
