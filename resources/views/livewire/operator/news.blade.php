<div>

    <x-sessionMessage name="status"/>

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
                        {{__('اخبار و اطلاعیه')}}
                    </span>
            </li>
        </ol>
    </nav>

        <x-modal name="delete">
            @if($blogId)
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4" dir="rtl">
                        <div class="sm:flex sm:items-center">
                            <div class="mx-auto shrink-0 flex items-center justify-center size-12 rounded-full bg-red-100 sm:mx-0 sm:size-10">
                                <svg class="size-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ms-4 sm:text-start">
                                <h3 class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ __('آیا مطمئن هستید که ') }} <span class="font-medium">{{$title}}</span>  {{__('پاک شود ؟')}}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-row justify-between px-6 gap-x-3 py-4 bg-gray-100">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('لغو') }}
                        </x-secondary-button>
                        <x-danger-button wire:click="delete({{$blogId}})" x-on:click="$dispatch('close')">
                            {{ __('حذف') }}
                        </x-danger-button>
                    </div>
            @endif
        </x-modal>



{{--        <div class="bg-white md:pt-10 pt-16 px-2 mt-10" dir="rtl">--}}
{{--                <div class="flex justify-between items-end max-w-full gap-x-8">--}}
{{--                    <input wire:model.live.debounce.500ms="search" type="text" dir="rtl"--}}
{{--                           placeholder="عنوان اخبار"--}}
{{--                           class="block w-1/3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">--}}
{{--                    @can('create-blog')--}}
{{--                        <a href="{{Illuminate\Support\Facades\URL::signedRoute('blogs.create')}}">--}}
{{--                            <x-primary-button>--}}
{{--                                {{__('درج اخبار جدید')}}--}}
{{--                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"--}}
{{--                                     stroke-width="1.5" stroke="currentColor" class="size-5 mr-2">--}}
{{--                                    <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                                          d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>--}}
{{--                                </svg>--}}
{{--                            </x-primary-button>--}}
{{--                        </a>--}}
{{--                    @endcan--}}
{{--                </div>--}}
{{--                <div--}}
{{--                    class="mx-auto mt-10 grid max-w-3xl grid-cols-1 gap-x-8 gap-y-16  sm:mt-16  lg:mx-0 lg:max-w-none lg:grid-cols-3">--}}
{{--                    @forelse($this->blogs as $blog )--}}
{{--                        <article class="flex max-w-xl flex-col items-start justify-between">--}}
{{--                            <div class="flex items-center justify-between w-full text-sm text-gray-500">--}}
{{--                                {{$blog->created_at->diffForHumans()}}--}}
{{--                            </div>--}}
{{--                            <div class="group relative">--}}
{{--                                <h3 class="mt-3 text-lg/6 font-semibold text-gray-900">--}}
{{--                                    {{$blog->title}}--}}
{{--                                </h3>--}}
{{--                                <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600">{{$blog->body}}</p>--}}
{{--                            </div>--}}
{{--                            <div class="relative mt-8 flex items-center gap-x-4">--}}
{{--                                <x-buttons.show-button href="{{Illuminate\Support\Facades\URL::signedRoute('blogs.show',$blog->id)}}"/>--}}
{{--                                @can('update-blog')--}}
{{--                                    <x-buttons.edit-button--}}
{{--                                        href="{{Illuminate\Support\Facades\URL::signedRoute('blogs.edit',$blog->id)}}"/>--}}
{{--                                @endcan--}}
{{--                                @can('delete-blog')--}}
{{--                                    <x-danger-button wire:click="confirmDelete({{$blog->id}})">--}}
{{--                                        {{__('حذف')}}--}}
{{--                                    </x-danger-button>--}}
{{--                                @endcan--}}
{{--                            </div>--}}
{{--                        </article>--}}
{{--                    @empty--}}
{{--                        {{__('رکوردی یافت نشد...')}}--}}
{{--                    @endforelse--}}
{{--                </div>--}}
{{--                <nav--}}
{{--                    class="flex flex-col md:flex-row mt-8 justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"--}}
{{--                    aria-label="Table navigation">--}}
{{--                    {{$this->blogs->withQueryString()->links(data:['scrollTo'=>false]) }}--}}
{{--                </nav>--}}
{{--            </div>--}}

{{--    <div class="bg-white dark:bg-gray-900 md:pt-10 pt-16 px-4 sm:px-6 lg:px-8 mt-10" dir="rtl">--}}
{{--        <div class="flex flex-col md:flex-row justify-between items-center gap-4 max-w-full mb-8">--}}
{{--            <input wire:model.live.debounce.500ms="search"--}}
{{--                   type="text"--}}
{{--                   dir="rtl"--}}
{{--                   placeholder="جستجو در عنوان اخبار..."--}}
{{--                   class="w-full md:w-1/3 px-4 py-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white"--}}
{{--            >--}}

{{--            @can('create-blog')--}}
{{--                <a href="{{ route('blogs.create') }}">--}}
{{--                    <button--}}
{{--                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"--}}
{{--                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                                  d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>--}}
{{--                        </svg>--}}
{{--                        درج اخبار جدید--}}
{{--                    </button>--}}
{{--                </a>--}}
{{--            @endcan--}}
{{--        </div>--}}

{{--        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">--}}
{{--            @forelse($this->blogs as $blog)--}}
{{--                <article class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow hover:shadow-md transition overflow-hidden flex flex-col h-full">--}}

{{--                    @if($blog->image_url)--}}
{{--                        <img src="{{ $blog->image_url }}"--}}
{{--                             alt="{{ $blog->title }}"--}}
{{--                             class="w-full h-48 object-cover">--}}
{{--                    @else--}}
{{--                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400 text-sm">--}}
{{--                            {{ __('بدون تصویر') }}--}}
{{--                        </div>--}}
{{--                    @endif--}}

{{--                    <div class="p-6 flex flex-col justify-between flex-grow">--}}
{{--                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">--}}
{{--                            <span>{{ $blog->created_at->diffForHumans() }}</span>--}}
{{--                        </div>--}}
{{--                        <div class="group flex-grow">--}}
{{--                            <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-tight mb-2">--}}
{{--                                {{ $blog->title }}--}}
{{--                            </h3>--}}
{{--                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">--}}
{{--                                {{ $blog->body }}--}}
{{--                            </p>--}}
{{--                        </div>--}}
{{--                        <div class="flex gap-2 mt-6">--}}
{{--                            <x-buttons.show-button href="{{ Illuminate\Support\Facades\URL::signedRoute('blogs.show', $blog->id) }}" />--}}
{{--                            @can('update-blog')--}}
{{--                                <x-buttons.edit-button href="{{ Illuminate\Support\Facades\URL::signedRoute('blogs.edit', $blog->id) }}" />--}}
{{--                            @endcan--}}
{{--                            @can('delete-blog')--}}
{{--                                <x-danger-button wire:click="confirmDelete({{ $blog->id }})">--}}
{{--                                    {{ __('حذف') }}--}}
{{--                                </x-danger-button>--}}
{{--                            @endcan--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </article>--}}
{{--            @empty--}}
{{--                <div class="text-center text-gray-500 dark:text-gray-400 col-span-full">--}}
{{--                    {{ __('رکوردی یافت نشد...') }}--}}
{{--                </div>--}}
{{--            @endforelse--}}
{{--        </div>--}}

{{--        <nav class="mt-12 flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0 p-4"--}}
{{--             aria-label="Pagination">--}}
{{--            {{ $this->blogs->withQueryString()->links(data: ['scrollTo' => false]) }}--}}
{{--        </nav>--}}
{{--    </div>--}}
{{--    <div class="bg-white dark:bg-gray-900 pt-20 px-4 sm:px-8 lg:px-16" dir="rtl">--}}
{{--        <!-- Search / Filter -->--}}
{{--        <div class="max-w-6xl mx-auto mb-12 text-center">--}}
{{--            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">آخرین اخبار</h1>--}}
{{--            <p class="text-gray-500 dark:text-gray-400 mb-6">با جدیدترین اخبار و اطلاعیه‌ها همراه باشید</p>--}}
{{--            <input wire:model.live.debounce.500ms="search" type="text" dir="rtl"--}}
{{--                   placeholder="جستجو در عنوان اخبار..."--}}
{{--                   class="w-full md:w-1/2 mx-auto px-4 py-3 text-sm text-gray-900 border border-gray-300 rounded-xl bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white shadow-sm transition" />--}}
{{--        </div>--}}

{{--        <!-- Blog Grid -->--}}
{{--        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">--}}
{{--            @forelse($this->blogs as $blog)--}}
{{--                <article class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl overflow-hidden hover:shadow-md transition flex flex-col">--}}
{{--                    <div class="h-48 w-full overflow-hidden">--}}
{{--                        <img src="{{ $blog->image_url ?? '/placeholder-news.jpg' }}"--}}
{{--                             alt="{{ $blog->title }}"--}}
{{--                             class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">--}}
{{--                    </div>--}}
{{--                    <div class="p-5 flex flex-col flex-grow">--}}
{{--                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">--}}
{{--                            <span>{{ $blog->created_at->translatedFormat('d F Y') }}</span>--}}
{{--                            <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full dark:bg-indigo-700 dark:text-white">--}}
{{--                            {{ $blog->category ?? 'بدون دسته‌بندی' }}--}}
{{--                        </span>--}}
{{--                        </div>--}}
{{--                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">--}}
{{--                            {{ $blog->title }}--}}
{{--                        </h2>--}}
{{--                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 mb-4">--}}
{{--                            {{ $blog->body }}--}}
{{--                        </p>--}}
{{--                        <a href="{{ Illuminate\Support\Facades\URL::signedRoute('blogs.show', $blog->id) }}"--}}
{{--                           class="mt-auto inline-block text-indigo-600 dark:text-indigo-400 hover:underline font-medium text-sm">--}}
{{--                            ادامه خبر →--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </article>--}}
{{--            @empty--}}
{{--                <div class="col-span-full text-center text-gray-500 dark:text-gray-400">--}}
{{--                    {{ __('هیچ خبری یافت نشد...') }}--}}
{{--                </div>--}}
{{--            @endforelse--}}
{{--        </div>--}}

{{--        <!-- Pagination -->--}}
{{--        <div class="mt-16 flex justify-center">--}}
{{--            {{ $this->blogs->withQueryString()->links(data: ['scrollTo' => false]) }}--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="bg-white dark:bg-gray-900  px-4 sm:px-8 lg:px-16" dir="rtl" x-data="{ openModal: false, selectedBlog: null }">
        <!-- Hero Title -->
        <div class="max-w-6xl mx-auto mb-12 text-center">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">مرکز اخبار</h1>
            <p class="text-gray-500 dark:text-gray-400 mb-6">با جدیدترین رویدادها و اطلاعیه‌ها همراه شوید</p>
            <!-- Search -->
            <input wire:model.live.debounce.500ms="search" type="text" dir="rtl"
                   placeholder="جستجو در عنوان اخبار..."
                   class="w-full md:w-1/2 mx-auto px-4 py-3 text-sm border border-gray-300 rounded-xl bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white shadow-sm transition" />
        </div>

        <!-- Blog Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
            @forelse($this->blogs as $blog)
                <article class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl overflow-hidden hover:shadow-md transition flex flex-col">
                    <!-- Image -->
                    <div class="h-48 w-full relative">
                        <img src="{{ $blog->image_url ?? '/placeholder-news.jpg' }}"
                             alt="{{ $blog->title }}"
                             loading="lazy"
                             class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                    </div>

                    <!-- Content -->
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                            <span>{{ $blog->created_at->translatedFormat('d F Y') }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                            {{ $blog->title }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">
                            {{ $blog->body }}
                        </p>
                        <div class="flex items-center mt-4">
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $blog->author->name ?? 'نویسنده ناشناس' }}</span>
                        </div>
                        <button @click="openModal = true; selectedBlog = @js($blog)"
                                class="mt-auto text-indigo-600 dark:text-indigo-400 hover:underline text-sm font-medium text-right">
                            مشاهده خبر →
                        </button>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center text-gray-500 dark:text-gray-400">
                    {{ __('هیچ خبری یافت نشد...') }}
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-16 flex justify-center">
            {{ $this->blogs->withQueryString()->links(data: ['scrollTo' => false]) }}
        </div>

        <!-- Modal Preview -->
        <div x-show="openModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div @click.outside="openModal = false" class="bg-white dark:bg-gray-800 p-6 rounded-lg max-w-2xl w-full overflow-y-auto max-h-[90vh] shadow-lg">
                <button @click="openModal = false" class="text-gray-500 hover:text-gray-800 dark:hover:text-white float-left text-sm">✖</button>
                <template x-if="selectedBlog">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2" x-text="selectedBlog.title"></h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4" x-text="selectedBlog.created_at"></p>
                        <img :src="selectedBlog.image_url" class="w-full h-64 object-cover rounded mb-4" />
                        <p class="text-gray-700 dark:text-gray-300 text-sm" x-text="selectedBlog.body"></p>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>
