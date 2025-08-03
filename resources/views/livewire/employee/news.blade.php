@php use App\Enums\UserPermission; @endphp
@php use App\Enums\UserRole; @endphp
<div>


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
                        class="inline-flex items-center gap-1 px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                       <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5"
                                 stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z"/>
                                </svg>
                       </span>
                        {{__('صفحه اخبار و اطلاعیه')}}
                    </span>
            </li>
        </ol>
    </nav>


    <div class="bg-white dark:bg-gray-900" dir="rtl">

        <form wire:submit="filterNews"
              class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4 bg-white border-gray-200 rounded-t-xl">
            <div class="grid gap-4 px-3 w-full sm:px-0 lg:grid-cols-6 items-end">
                <!-- Search Input -->
                <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                    <x-input-label for="search" value="{{ __('جست و جو') }}"/>
                    <x-search-input>
                        <x-text-input type="text" id="search" wire:model="search" class="block ps-10"
                                      placeholder="{{ __('عنوان خبر را وارد کنید...') }}"/>
                    </x-search-input>
                </div>

                <!-- Sort Select -->
                <div class="col-span-6 sm:col-span-1">
                    <x-input-label for="sort" value="{{ __('مرتب‌سازی') }}"/>
                    <x-select-input id="sort" wire:model="sort">
                        <option value="newest">{{ __('جدیدترین‌ها') }}</option>
                        <option value="oldest">{{ __('قدیمی‌ترین‌ها') }}</option>
                    </x-select-input>
                </div>

                <!-- Search + Show All Buttons -->
                <div class="col-span-6 sm:col-span-2 flex justify-start flex-row gap-4 mt-4 lg:mt-0">
                    <x-search-button>{{ __('جست و جو') }}</x-search-button>
                    @if($search !== '')
                        <x-view-all-link href="{{ route('blogs.index') }}">
                            {{ __('نمایش همه') }}
                        </x-view-all-link>
                    @endif
                </div>
            </div>
        </form>

        <!-- Blog List -->
        <div class="flex flex-col space-y-8 max-w-7xl mx-auto">
            @forelse($this->blogs as $blog)
                <article
                    class="flex flex-row-reverse bg-white dark:bg-gray-900 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700 hover:shadow-md transition overflow-hidden"
                    dir="rtl">
                    <!-- Image on the right -->
                    <div class="w-48 flex-shrink-0 relative">
                        <img
                            src="{{ optional($blog->images->first())->image_url ?? '/placeholder-news.jpg' }}"
                            alt="{{ $blog->title }}"
                            loading="lazy"
                            class="h-full w-full object-cover transition-transform duration-300 hover:scale-105">
                    </div>

                    <!-- Content -->
                    <div class="p-5 flex flex-col justify-between flex-grow">
                        <div>
                            @php
                                $createdAt = $blog->created_at;
                                list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali($createdAt->year, $createdAt->month, $createdAt->day, '/'));
                                $newDate = sprintf('%04d/%02d/%02d', $ja_year, $ja_month, $ja_day);
                                $time = $createdAt->format('H:i');
                            @endphp
                            <div
                                class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                                <span>{{ $newDate }} - {{ $time }}</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-2 mb-2">
                                {{ $blog->title }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 mb-4">
                                {{ $blog->body }}
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-4">
                            <x-primary-button wire:click="showBlog({{ $blog->id }})">
                                {{ __('مشاهده خبر') }}
                            </x-primary-button>
                            @can('has-permission-and-role', [UserPermission::NEWS_PERMISSIONS,UserRole::ADMIN])
                                <a href="{{ route('blogs.edit', $blog->id) }}">
                                    <x-edit-button>
                                        {{__('ویرایش')}}
                                    </x-edit-button>
                                </a>
                                <x-cancel-button wire:click="confirmDelete({{$blog->id}})">
                                    {{__('حذف')}}
                                </x-cancel-button>
                            @endcan
                        </div>
                    </div>
                </article>
            @empty
                <div class="text-center text-gray-500 dark:text-gray-400">
                    {{ __('هیچ خبری یافت نشد...') }}
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-16">
            {{ $this->blogs->withQueryString()->links(data: ['scrollTo' => false]) }}
        </div>

    </div>


    @can('has-permission-and-role', [UserPermission::NEWS_PERMISSIONS,UserRole::ADMIN])
        <x-modal name="delete" maxWidth="4xl" :closable="false">
            @if($blogId)
                <!-- Header -->
                <div class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-red-600">
                        {{ __('حذف خبر') }}
                    </h2>
                    <a href="{{route('blogs.index')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-6 h-6 text-gray-500 hover:text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>

                <!-- Body -->
                <div class="p-6 text-sm text-gray-800 dark:text-gray-200 space-y-4" dir="rtl">
                    <div class="flex items-center space-x-4 space-x-reverse">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100">
                            <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v3.75M3.697 16.126C2.831 17.626 3.914 19.5 5.644 19.5h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L3.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ __('آیا مطمئن هستید که') }} <span
                                    class="font-semibold text-red-700">"{{ $title }}"</span> {{ __('حذف شود؟') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer / Form -->
                <div class="flex justify-between items-center px-6 py-4 bg-gray-100 border-t border-gray-200">
                    <form method="POST" action="{{ route('blogs.destroy', $blogId) }}">
                        @csrf
                        @method('DELETE')
                        <x-primary-button type="submit">
                            {{ __('حذف نهایی') }}
                        </x-primary-button>
                    </form>

                    <a href="{{route('blogs.index')}}">
                        <x-cancel-button>
                            {{ __('لغو') }}
                        </x-cancel-button>
                    </a>
                </div>
            @endif
        </x-modal>
    @endcan
    <x-modal name="view" maxWidth="4xl" :closable="false">
        @if($selectedBlog)
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">
                    {{ __('مشاهده خبر') }}
                </h2>
                <a href="{{route('blogs.index')}}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </a>
            </div>
            <!-- Body -->

            <div class="p-6 max-h-[85vh] overflow-y-auto text-sm text-gray-800 dark:text-gray-200 space-y-6"
                 dir="rtl">

                <!-- Slider Section -->
                @if($selectedBlog->images && $selectedBlog->images->isNotEmpty())
                    <div x-data="{ active: 0 }" class="relative w-full max-h-80 rounded-lg overflow-hidden">
                        <div class="relative w-full h-64 md:h-80 overflow-hidden rounded-lg">
                            @foreach($selectedBlog->images as $index => $image)
                                <img
                                    x-show="active === {{ $index }}"
                                    src="{{ $image->image_url }}"
                                    alt="blog-image-{{ $index }}"
                                    class="absolute inset-0 w-full h-full object-cover transition-all duration-700 ease-in-out"
                                    x-transition:enter="transform ease-out duration-500"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                >
                            @endforeach
                        </div>

                        <!-- Slider Controls -->
                        <div class="absolute inset-0 flex items-center justify-between px-4">
                            <button
                                @click="active = (active === 0) ? {{ $selectedBlog->images->count() - 1 }} : active - 1"
                                class="bg-white/70 hover:bg-white text-gray-800 rounded-full p-2 shadow">
                                ‹
                            </button>
                            <button
                                @click="active = (active === {{ $selectedBlog->images->count() - 1 }}) ? 0 : active + 1"
                                class="bg-white/70 hover:bg-white text-gray-800 rounded-full p-2 shadow">
                                ›
                            </button>
                        </div>

                        <!-- Dots -->
                        <div class="absolute bottom-4 w-full flex justify-center gap-2">
                            @foreach($selectedBlog->images as $index => $image)
                                <button
                                    class="w-2.5 h-2.5 rounded-full"
                                    :class="active === {{ $index }} ? 'bg-white' : 'bg-white/50'"
                                    @click="active = {{ $index }}">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Blog Content -->
                <div class="space-y-2">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $selectedBlog->title }}
                    </h3>
                    @php
                        $createdAt = $selectedBlog->created_at;
                        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali($createdAt->year, $createdAt->month, $createdAt->day, '/'));
                        $newDate = sprintf('%04d/%02d/%02d', $ja_year, $ja_month, $ja_day);
                        $time = $createdAt->format('H:i');
                    @endphp
                    <div
                        class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                        <span>{{ $newDate }} - {{ $time }}</span>
                    </div>
                </div>

                <div class="text-sm leading-relaxed text-gray-700 dark:text-gray-300 whitespace-pre-line">
                    {{ $selectedBlog->body }}
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end px-6 py-4 bg-gray-100 border-t border-gray-200">
                <a href="{{route('blogs.index')}}">
                    <x-cancel-button>
                        {{ __('بستن') }}
                    </x-cancel-button>
                </a>

            </div>
        @endif
    </x-modal>


</div>
