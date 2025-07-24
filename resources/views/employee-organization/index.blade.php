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
            <li>
                    <span
                        class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                        {{__('سامانه ها')}}
                    </span>
            </li>
        </ol>
    </nav>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" dir="rtl">

        <!-- 🔍 Search Form -->
        <form method="GET" action="{{ route('employee.organization') }}"
             >
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">

                <!-- Search Field -->
                <div>
                    <x-input-label for="search" value="{{ __('جست و جو') }}"/>
                    <x-search-input>
                        <x-text-input type="text" id="search" name="search" value="{{ $search }}" class="block ps-10"
                                      placeholder="{{ __('عبارت مورد نظر را وارد کنید...') }}"/>
                    </x-search-input>
                </div>
                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 mt-4 md:mt-0">
                    <x-search-button>{{ __('جست و جو') }}</x-search-button>
                    @if($search !== '')
                        <x-view-all-link href="{{ route('employee.organization') }}">
                            {{ __('نمایش همه') }}
                        </x-view-all-link>
                    @endif
                </div>
            </div>
        </form>

        <!-- 🏢 Organization Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($organizations as $organization)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all p-6 flex flex-col items-center text-center">
                    @if($organization->image)
                        <img src="{{ $organization->getImageUrl() }}"
                             class="w-20 h-20 rounded-full object-cover mb-4 border border-gray-300 shadow"
                             alt="{{ $organization->organization_name }}">
                    @else
                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 14l6.16-3.422A12.083 12.083 0 0120 13.5c0 2.485-2.015 4.5-4.5 4.5S11 15.985 11 13.5c0-.497.072-.977.21-1.435L12 14z"/>
                            </svg>
                        </div>
                    @endif

                    <h2 class="text-lg font-bold text-gray-800 mb-1">{{ $organization->organization_name }}</h2>
                    @if($organization->url)
                        <a href="{{ $organization->url }}" target="_blank"
                           class="text-sm text-blue-600 hover:underline break-words">
                            {{ $organization->url }}
                        </a>
                    @else
                        <span class="text-sm text-gray-400">{{ __('بدون لینک') }}</span>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 py-12">
                    <p class="text-lg font-medium">{{ __('هیچ سازمانی یافت نشد.') }}</p>
                </div>
            @endforelse
        </div>
    </div>


</x-app-layout>
