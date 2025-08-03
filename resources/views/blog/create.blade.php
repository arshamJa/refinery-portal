@php use App\Enums\UserPermission; @endphp
@php use App\Enums\UserRole; @endphp
<x-app-layout>

    <x-breadcrumb>
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
                        stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3.75 6.75h16.5M3.75 12h16.5M12 17.25h8.25"/>
                                </svg>
                </span>
                {{__('اخبار و اطلاعیه')}}
            </span>
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
                           stroke="currentColor" class="w-3.5 h-3.5-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                </span>
                {{__('درج اخبار و اطلاعیه جدید')}}
            </span>
        </li>
    </x-breadcrumb>
    @can('has-permission-and-role', [UserPermission::NEWS_PERMISSIONS,UserRole::ADMIN])

        <div class="p-4 mb-2 sm:p-8 bg-white dark:bg-gray-800 drop-shadow-xl sm:rounded-lg">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 border-b pb-2">
                {{ __('درج خبر') }}
            </h2>
            <form action="{{route('blogs.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="grid gap-4 mb-2 sm:grid-cols-2">
                    <div class="w-full">
                        <x-input-label for="title" :value="__('عنوان')"/>
                        <x-text-input name="title" id="title" value="{{old('title')}}" class="block my-2 w-full"
                                      type="text" autofocus/>
                        <x-input-error :messages="$errors->get('title')" class="mt-2"/>
                    </div>
                    <div class="w-full">
                        <x-input-label for="image" :value="__('آپلود عکس')"/>
                        <x-text-input multiple name="image[]" id="image"
                                      value="{{old('image')}}" class="block my-2 p-2 w-full" type="file"
                                      accept="image/*"
                                      autofocus/>
                        <x-input-error :messages="$errors->get('image')" class="mt-2"/>
                    </div>
                </div>
                <div class="mb-6">
                    <x-input-label for="body" :value="__('متن')" class="mb-2"/>
                    <textarea type="text" name="body" value="{{old('body')}}"
                              class="flex w-full h-auto min-h-[80px] px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                    <x-input-error :messages="$errors->get('body')" class="mt-2"/>
                </div>
                <div class="flex items-center space-x-4 gap-2">
                    <x-primary-button type="submit">{{__('ثبت')}}</x-primary-button>
                    <a href="{{route('blogs.index')}}">
                        <x-cancel-button>{{__('لغو')}}</x-cancel-button>
                    </a>
                </div>
            </form>
        </div>
    @endcan


</x-app-layout>

