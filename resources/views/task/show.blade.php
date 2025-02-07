<x-app-layout>

    <x-header header="نمایش اطلاعات"/>
    <x-sessionMessage name="status"/>

    <div>
        <div class="py-12 bg-gray-100" dir="rtl">
            <div class="max-w-7xl sm:px-6 lg:px-8 space-y-6">

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <div>
                            <dl class="divide-y divide-gray-100">
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm/6 font-medium text-gray-900">{{__('عنوان')}}</dt>
                                    <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{$task->title}}</dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm/6 font-medium text-gray-900">{{__('بدنه')}}</dt>
                                    <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{$task->body}}</dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm/6 font-medium text-gray-900">{{__('تاریخ ارسال')}}</dt>
                                    <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{$task->sent_date}}</dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm/6 font-medium text-gray-900">{{__('مهلت انجام (روز)')}}</dt>
                                    <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{$task->time_out}}</dd>
                                </div>
                                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm/6 font-medium text-gray-900">{{__('پیوست')}}</dt>
                                    <dd class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                        <ul role="list"
                                            class="divide-y divide-gray-100 rounded-md border border-gray-200">
                                            @foreach($taskUsers->where('task_id', $task->id) as $taskUser)
                                                <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm/6">
                                                    <div class="flex w-0 flex-1 items-center">
                                                        <svg class="size-5 shrink-0 text-gray-400" viewBox="0 0 20 20"
                                                             fill="currentColor" aria-hidden="true" data-slot="icon">
                                                            <path fill-rule="evenodd"
                                                                  d="M15.621 4.379a3 3 0 0 0-4.242 0l-7 7a3 3 0 0 0 4.241 4.243h.001l.497-.5a.75.75 0 0 1 1.064 1.057l-.498.501-.002.002a4.5 4.5 0 0 1-6.364-6.364l7-7a4.5 4.5 0 0 1 6.368 6.36l-3.455 3.553A2.625 2.625 0 1 1 9.52 9.52l3.45-3.451a.75.75 0 1 1 1.061 1.06l-3.45 3.451a1.125 1.125 0 0 0 1.587 1.595l3.454-3.553a3 3 0 0 0 0-4.242Z"
                                                                  clip-rule="evenodd"/>
                                                        </svg>
                                                        <div class="ml-4 flex min-w-0 flex-1 gap-2 mr-1">
                                                            <span
                                                                class="truncate font-medium"> {{__('فایل')}} {{$loop->index+1}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4 shrink-0">
                                                        <a href="#"
                                                           class="font-medium text-indigo-600 hover:text-indigo-500">
                                                            <x-secondary-button>
                                                                {{__('دانلود')}}
                                                            </x-secondary-button>
                                                        </a>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </dd>
                                </div>
                            </dl>
                            @if($task->is_completed)
                                <p class="mb-4">{{__('کار انجام شد')}}</p>
                                <a href="{{('tasks.index')}}" class="mt-4">
                                    <x-secondary-button>
                                        {{__('بازگشت')}}
                                    </x-secondary-button>
                                </a>
                            @else
                                <div class="flex gap-x-2 items-center">
                                    <a href="{{route('tasks.index')}}">
                                        <x-secondary-button>
                                            {{__('بازگشت')}}
                                        </x-secondary-button>
                                    </a>
                                    <form action="{{route('tasks.complete',$task->id)}}" method="post">
                                        @csrf
                                        <x-primary-button type="submit">
                                            {{__('اتمام کار')}}
                                        </x-primary-button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                  @if(!$task->request_task)
                        <form action="{{route('editTask',$task->id)}}" method="post" class="mt-8">
                            @csrf
                            <x-input-label for="body" :value="__('تایپ قسمت مورد نیاز به ویرایش در کادر زیر')" class="mb-2"/>
                            <textarea type="text" name="body" placeholder="{{__('تایپ کنید...')}}"
                                      class="flex w-full h-auto min-h-[80px] px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                            <x-input-error :messages="$errors->get('body')" class="mt-2"/>
                            <button>Send</button>
                        </form>
                  @endif
                </div>


            </div>
        </div>
    </div>


</x-app-layout>
