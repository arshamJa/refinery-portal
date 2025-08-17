@php use App\Enums\UserPermission; @endphp
<x-app-layout>
    @can('has-permission',UserPermission::NEWS_PERMISSIONS)
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
                        {{__('ویرایش خبر')}}
                    </span>
                </li>
            </ol>
        </nav>

        <div class="p-4 mb-2 sm:p-8 bg-white dark:bg-gray-800 drop-shadow-xl sm:rounded-lg">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 border-b pb-2">
                {{ __('ویرایش خبر') }}
            </h2>
            <form action="{{route('blogs.update',$blog->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="grid gap-4 mb-2 sm:grid-cols-2">
                    <!-- Title -->
                    <div class="w-full">
                        <x-input-label for="title" :value="__('عنوان')"/>
                        <x-text-input name="title" id="title" value="{{ $blog->title }}"
                                      class="block my-2 w-full" type="text" autofocus/>
                        <x-input-error :messages="$errors->get('title')" class="mt-2"/>
                    </div>

                    <!-- Upload Images -->
                    <div class="w-full">
                        <x-input-label for="images" :value="__('آپلود عکس')" class="mb-2"/>
                        <x-text-input multiple name="images[]" id="images" type="file"
                                      class="block my-2 p-2 w-full" autofocus/>
                        <x-input-error :messages="$errors->get('images')" class="mt-2"/>
                    </div>
                </div>

                <!-- Body Textarea -->
                <div class="mb-6">
                    <x-input-label for="body" :value="__('متن')" class="mb-2"/>
                    <textarea name="body"
                              class="flex w-full h-auto min-h-[100px] px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                        {{ old('body', $blog->body) }}
                    </textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2"/>
                </div>
                <div class="flex items-center space-x-4 gap-2">
                    <x-primary-button type="submit">{{__('بروزرسانی')}}</x-primary-button>
                    <a href="{{route('blogs.index')}}">
                        <x-cancel-button>{{__('لغو')}}</x-cancel-button>
                    </a>
                </div>
            </form>

            <!-- Image list outside and below the form -->
            <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($blog->images as $blogImage)
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-xl p-3 flex flex-col items-center justify-between hover:shadow-md transition">
                        <div class="w-full h-40 overflow-hidden rounded-lg mb-3">
                            <img src="{{ url('storage/' . $blogImage->image) }}"
                                 alt=""
                                 class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>
                        <form action="{{ route('blogs.images.destroy', $blogImage->id) }}" method="POST"
                              onsubmit="return confirm('آیا از حذف این عکس مطمئن هستید؟');" class="w-full">
                            @csrf
                            @method('DELETE')
                            <x-danger-button type="submit" class="w-full">{{ __('حذف') }}</x-danger-button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endcan
</x-app-layout>
