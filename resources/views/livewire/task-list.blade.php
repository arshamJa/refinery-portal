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

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                <li class="flex items-center h-full">
                    <a href="{{route('attended.meetings')}}"
                       class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                        <span> {{__('لیست جلساتی که در آن شرکت کردم')}}</span>
                    </a>
                </li>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                     stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
                <li class="flex items-center h-full">
                    <span class="active-breadcrumb">{{__('اقدامات')}}</span>
                </li>
            </ol>
        </nav>
        @foreach($this->tasks as $task)
            <div class="space-y-4 p-4 rounded-md mt-2 bg-cyan-100">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{__('موضوع جلسه : ')}}{{$task->meeting->title}}</h4>

                <dl>
                    <dt class="text-base font-medium text-gray-900 dark:text-white">{{__('مهلت اقدام : ')}}{{$task->time_out}}</dt>
                    <dd class="mt-1 text-base font-normal text-gray-500 dark:text-gray-400">{{$task->body}}</dd>
                </dl>

                @if(!$task->is_completed)
                    <x-primary-button wire:click="finishTask({{$task->id}})">{{__('اتمام کار و ارسال')}}</x-primary-button>
                    <div class="p-1 mt-2 border-t-2">
                        <x-input-label for="body" :value="__('در صورت نیاز به ویرایش اقدامات، در کادر زیر درج کنید')" class="mb-2"/>
                        <textarea type="text" wire:model="body"
                                  class="flex w-full h-auto min-h-[80px] px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                        <x-secondary-button wire:click="editTask({{$task->id}})" class="mt-2">{{__('ارسال به دبیر جلسه')}}</x-secondary-button>
                    </div>
                @else
                    <p class="mt-4">{{__('اقدامات انجام و به دبیر جلسه ارسال شده است')}}</p>
                @endif
            </div>
        @endforeach
</div>
