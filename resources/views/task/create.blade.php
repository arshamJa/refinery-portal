@php use App\Models\UserInfo; @endphp
<x-app-layout>


    <x-sessionMessage name="status"/>
    <x-template>
        <nav class="flex justify-between mb-4">
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
                    <a href="{{route('meetingsList')}}"
                       class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                        <span>{{__('جدول جلسات')}}</span>
                    </a>
                </li>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                     stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
                <li>
                    <span
                        class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                       {{__('درج اقدامات')}}
                    </span>
                </li>
            </ol>
        </nav>
        <div class="border-2 p-4 bg-gray-100 rounded-md" dir="rtl">
            <form action="{{route('tasks.store', $meetings->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-3 gap-4 max-w-full pb-4 border-b border-gray-300">
                    <p>{{__('واحد/کمیته')}} : {{$meetings->unit_held}}</p>
                    <p>{{__('تهیه کننده')}} : {{$meetings->scriptorium}}</p>
                    <p>{{__('پیوست')}} : {{__('پیوست')}}</p>
                    <p>{{__('تاریخ جلسه')}} : {{$meetings->date}}</p>
                    <p>{{__('زمان جلسه')}} : {{$meetings->time}}</p>
                    <p>{{__('مکان جلسه')}} : {{$meetings->location}}</p>
                </div>
                <div class="flex flex-col gap-y-2 my-2 pb-4 border-b border-gray-300">
                    <span>
                        {{__('موضوع جلسه')}} : {{$meetings->title}}
                    </span>
                    <span>{{__('حاضرین')}} :
                    @foreach($meetingUsers as $meetingUser)
                        {{UserInfo::where('user_id',$meetingUser->user_id)->value('full_name')}} -
                    @endforeach
                    </span>
                </div>

                <div class="max-w-full">
                    <div class="mt-4 grid grid-cols-2 gap-x-3">
                        <div class="my-2 col-span-1">
                            <x-input-label class="mb-2" :value="__('اقدام کننده')"/>
                            <select name="initiator"
                                    class="w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="">...</option>
                                @foreach($meetingUsers as $meetingUser)
                                    <option
                                        value="{{$meetingUser->user_id}}">{{UserInfo::where('user_id',$meetingUser->user_id)->value('full_name')}}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('initiator')" class="my-2"/>
                        </div>
                        <div class="my-2 col-span-1">
                            <x-input-label for="time_out" :value="__('مهلت اقدام')"/>
                            <x-text-input name="time_out" value="{{old('time_out')}}" id="time_out"
                                          class="block my-2 w-full" type="text" autofocus/>
                            <x-input-error :messages="$errors->get('time_out')" class="my-2"/>
                        </div>

{{--                        <div class="my-2 col-span-1">--}}
{{--                            <x-input-label for="title" :value="__('عنوان')" class="mb-2"/>--}}
{{--                            <x-text-input name="title" id="title"--}}
{{--                                          class="block my-2 w-full" type="text" autofocus/>--}}
{{--                            <x-input-error :messages="$errors->get('title')" class="my-2"/>--}}
{{--                        </div>--}}

                        <div class="my-2 col-span-2">
                        <x-input-label for="body" :value="__('خلاصه مذاکرات و تصمیمات اتخاذ شده')" class="mb-2"/>
                        <textarea type="text" name="body"
                                  class="flex w-full h-auto min-h-[80px] px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                            {{old('body')}}
                        </textarea>
                        <x-input-error :messages="$errors->get('body')" class="mt-2"/>
                        </div>

{{--                        <div class="my-2 col-span-1">--}}
{{--                        <x-input-label for="date" :value="__('تاریخ ارسال شده')" class="my-2"/>--}}
{{--                        <x-text-input name="date" id="date"--}}
{{--                                      class="block my-2 w-full" type="text" autofocus/>--}}
{{--                        <x-input-error :messages="$errors->get('date')" class="my-2"/>--}}
{{--                        </div>--}}
                        {{--                            <x-input-label for="file" :value="__('فایل')" class="my-2"/>--}}
                        {{--                            <input name="files" multiple--}}
                        {{--                                   class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"--}}
                        {{--                                   id="file" type="file">--}}
                        {{--                            <x-input-error :messages="$errors->get('files')" class="my-2"/>--}}

                    </div>
                </div>
                <div class="mt-6">
                    <x-primary-button type="submit">
                        {{ __('ارسال') }}
                    </x-primary-button>
                    <a href="{{route('meetingsList')}}">
                        <x-secondary-button>
                            {{__('لغو')}}
                        </x-secondary-button>
                    </a>
                </div>
            </form>
        </div>

        <div class="my-6" dir="rtl">
            <x-table.table>
                <x-slot name="head">
                    <th class="px-4 py-3">{{__('ردیف')}}</th>
                    <th class="px-4 py-3">{{__('خلاصه مذاکرات و تصمیمات اتخاذ شده')}}</th>
                    <th class="px-4 py-3">{{__('مهلت اقدام')}}</th>
                    <th class="px-4 py-3">{{__('اقدام کننده')}}</th>
                    <th class="px-4 text-center">{{__('وضعیت')}}</th>
                </x-slot>
                <x-slot name="body">
                    @foreach($tasks as $task)
                        <tr class="px-4 py-3 border-b text-center">
                            <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$loop->index+1}}</td>
                            <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->body}}</td>
                            <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->time_out}}{{__(' روز')}}</td>
                            <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">{{$task->user->user_info->full_name}}</td>
                            <td class="px-4 py-4 whitespace-no-wrap text-sm leading-5 text-coll-gray-900">
                               {{$task->is_completed ? 'انجام شد' : 'انجام نشده'}}
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-table.table>
{{--            <nav--}}
{{--                class="flex flex-col md:flex-row mt-14 justify-between items-start md:items-center space-y-3 md:space-y-0 p-4">--}}
{{--                {{ $tasks->withQueryString()->links(data:['scrollTo'=>false]) }}--}}
{{--            </nav>--}}
        </div>

    </x-template>
</x-app-layout>
