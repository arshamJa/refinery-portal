@php use App\Enums\UserRole; @endphp
<x-app-layout>
    @can('has-role',UserRole::ADMIN)
        <nav class="flex justify-between mb-4 mt-16">
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
                <li>
            <span
                class="inline-flex items-center gap-1 px-2 py-1.5 font-normal rounded cursor-default focus:outline-none">
                {{__('تنظیمات راهبری')}}
            </span>
                </li>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                     stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
                <li>
                    <a href="{{route('users.index')}}"
                       class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                     <span>
                         {{__('مدیریت کاربران')}}
                        </span>
                    </a>
                </li>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                     stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
                <li>
            <span
                class="inline-flexitems-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                {{__('نمایش اطلاعات')}}
            </span>
                </li>
            </ol>
        </nav>
        <div class="p-6 bg-white shadow-lg rounded-2xl space-y-8 font-sans">
            <!-- Personal Info -->
            <div class="border-b pb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">{{__('اطلاعات شخصی')}}</h2>
                    <div>
                        @can('edit-user',$userInfo->user)
                            <a href="{{ route('users.edit', $userInfo->id) }}" class="ml-2">
                                <x-edit-button>
                                    {{__('ویرایش اطلاعات و دسترسی ها')}}
                                </x-edit-button>
                            </a>
                            <a href="{{ route('reset.password', $userInfo->user->id) }}">
                                <x-secondary-button>
                                    {{__('ویرایش رمز ورود')}}
                                </x-secondary-button>
                            </a>
                        @endif
                        <a href="{{ route('users.index') }}" class="mr-2">
                            <x-primary-button>
                                {{__('مدیریت کاربران')}}
                            </x-primary-button>
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-right text-gray-700">
                    <div><span class="font-medium">{{__('نقش:')}}</span>
                        {{ $userInfo->user->roles->first()->name ?? __('بدون نقش') }}
                    </div>
                    <div>
                        <span class="font-medium">{{__('نام و نام خانوادگی:')}}</span>
                        {{ $userInfo->full_name ?? '' }}
                    </div>
                    <div>
                        <span class="font-medium">{{__('کد پرسنلی:')}}</span>
                        {{ $userInfo->user->p_code ?? '' }}
                    </div>
                    <div>
                        <span class="font-medium">{{__('کد ملی:')}}</span>
                        {{ $userInfo->n_code ?? '' }}
                    </div>
                    <div>
                        <span class="font-medium">{{__('شماره همراه:')}}</span>
                        {{ $userInfo->phone  ?? ''}}
                    </div>
                    <div>
                        <span class="font-medium">{{__('شماره منزل:')}}</span>
                        {{ $userInfo->house_phone ?? '' }}
                    </div>
                    <div>
                        <span class="font-medium">{{__('شماره محل کار:')}}</span>
                        {{ $userInfo->work_phone ?? '' }}
                    </div>
                    <div>
                        <span class="font-medium">{{__('سمت:')}}</span>
                        {{ $userInfo->position ?? '' }}
                    </div>
                    <div>
                        <span class="font-medium">{{__('دپارتمان:')}}</span>
                        {{ $userInfo->department?->department_name ?? 'دپارتمان وجود ندارد' }}
                    </div>
                </div>
            </div>


            <!-- Active Systems -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{__('سامانه های فعال')}}</h2>
                <ul class="list-disc list-inside text-gray-700">
                    @if($user->organizations->isNotEmpty())
                        @foreach($user->organizations as $organization)
                            <li>{{ $organization->organization_name }}</li>
                        @endforeach
                    @else
                        <li class="text-gray-600">{{ __('کاربر هیچ سامانه فعالی ندارد.') }}</li>
                    @endif
                </ul>
            </div>

            <!-- User Access Section -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{__('بخش دسترسی کاربر')}}</h2>
                <ul class="list-disc list-inside text-gray-700">
                    @if($allPermissions->isNotEmpty())
                        @foreach($allPermissions as $permission)
                            <li>{{ $permission->name }}</li>
                        @endforeach
                    @else
                        <li class="text-gray-600">{{ __('کاربر هیچ دسترسی ندارد') }}</li>
                    @endif
                </ul>
            </div>


            <div class="border-t pt-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{__('امضای کاربر')}}</h2>
                @if($userInfo->signature)
                    <img src="{{$userInfo->getImageUrl()}}" class="rounded-md w-24 h-24" alt="">
                @else
                    <li class="text-gray-700">{{ __('کاربر هیچ امضایی ندارد') }}</li>
                @endif
            </div>
        </div>
    @endcan
</x-app-layout>
