<x-app-layout>

    <x-header header="نمایش اطلاعات"/>

    <div class="py-12" dir="rtl">
        <div class="max-w-7xl space-y-6">
            <div class="p-4 sm:p-8 shadow sm:rounded-lg">
                <section class="space-y-6">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('اطلاعات شخصی') }}
                        </h2>
                    </header>
                    <div class="space-y-2">
                        <p><strong>{{ __('نقش') }}:</strong>
                            @if($userRoles->isNotEmpty())
                                {{ $userRoles->pluck('name')->implode(', ') }}
                            @else
                                {{ __('بدون نقش') }}
                            @endif
                        </p>
                        <p><strong>{{ __('نام و نام خانوادگی') }}:</strong> {{ $userInfo->full_name }}</p>
                        <p><strong>{{ __('کدپرسنلی') }}:</strong> {{ $userInfo->user->p_code }}</p>
                        <p><strong>{{ __('کد ملی') }}:</strong> {{ $userInfo->n_code }}</p>
                        <p><strong>{{ __('شماره همراه') }}:</strong> {{ $userInfo->phone }}</p>
                        <p><strong>{{ __('شماره منزل') }}:</strong> {{ $userInfo->house_phone }}</p>
                        <p><strong>{{ __('شماره محل کار') }}:</strong> {{ $userInfo->work_phone }}</p>
                        <p><strong>{{ __('سمت') }}:</strong> {{ $userInfo->position }}</p>
                        <p><strong>{{ __('دپارتمان') }}:</strong>
                            @if($userInfo->department_id)
                                {{ $userInfo->department->department_name }}
                            @else
                                {{ __('دپارتمان وجود ندارد') }}
                            @endif
                        </p>
                    </div>
                </section>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <section class="space-y-6">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('سامانه های فعال') }}
                        </h2>
                    </header>
                    <div>
                        @if($user->organizations->isNotEmpty())
                            {{ $user->organizations->pluck('organization_name')->implode(' - ') }}
                        @else
                            <p>{{ __('کاربر هیچ سامانه فعالی ندارد.') }}</p>
                        @endif
                    </div>
                </section>
            </div>
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <section class="space-y-6">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('بخش دسترسی کاربر') }}
                        </h2>
                    </header>

                    <div>
                        @if($user->permissions->isNotEmpty())
                            {{ $user->permissions->pluck('name')->implode(', ') }}
                        @else
                            <p>{{ __('کاربر هیچ دسترسی ندارد') }}</p>
                        @endif
                    </div>
                </section>
                <x-secondary-button>
                    <a href="{{ route('users.index') }}" class="flex items-center">
                        {{ __('بازگشت') }}
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
