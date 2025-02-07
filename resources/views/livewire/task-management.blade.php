<div>
{{--    <div class="py-12 bg-gray-100" dir="rtl">--}}
{{--        <div class="max-w-7xl sm:px-6 lg:px-8 space-y-6">--}}
{{--            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">--}}
{{--                <form wire:submit="send" enctype="multipart/form-data">--}}
{{--                    <div class="max-w-xl">--}}
{{--                        <div class="px-4 sm:px-0">--}}
{{--                            <h3 class="text-base/7 font-semibold text-gray-900">{{__('صورتجلسه')}}</h3>--}}
{{--                        </div>--}}
{{--                        <div class="mt-6 border-t border-gray-100">--}}
{{--                            <div class="my-2">--}}
{{--                                <x-input-label class="mb-2" :value="__('لیست جلسات')"/>--}}
{{--                                <select wire:model.live="meeting_id" class="w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">--}}
{{--                                    <option value="">...</option>--}}
{{--                                    @foreach($this->meetings as $meeting)--}}
{{--                                        <option value="{{$meeting->id}}">{{$meeting->title}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                                <x-input-error :messages="$errors->get('meeting_id')" class="my-2"/>--}}
{{--                            </div>--}}
{{--                            <div class="my-2">--}}
{{--                                <x-input-label class="mb-2" :value="__('لیست کاربران')"/>--}}
{{--                                <select wire:model="user_id" class="w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">--}}
{{--                                    <option value="">...</option>--}}
{{--                                    @foreach($this->meetingUsers as $meetingUser)--}}
{{--                                        <option value="{{$meetingUser->user_id}}"> {{$this->users->where('user_id',$meetingUser->user_id)->value('full_name')}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                                <x-input-error :messages="$errors->get('user_id')" class="my-2"/>--}}
{{--                            </div>--}}

{{--                            <x-input-label for="title" :value="__('عنوان')" class="my-2"/>--}}
{{--                            <x-text-input wire:model="title" id="title"--}}
{{--                                          class="block my-2 w-full" type="text" autofocus/>--}}
{{--                            <x-input-error :messages="$errors->get('title')" class="my-2"/>--}}

{{--                            <x-input-label for="body" :value="__('متن')" class="mb-2"/>--}}
{{--                            <textarea type="text" wire:model="body"--}}
{{--                                      class="flex w-full h-auto min-h-[80px] px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"></textarea>--}}
{{--                            <x-input-error :messages="$errors->get('body')" class="mt-2"/>--}}

{{--                            <x-input-label for="date" :value="__('تاریخ ارسال شده')" class="my-2"/>--}}
{{--                            <x-text-input wire:model="date" id="date"--}}
{{--                                          class="block my-2 w-full" type="text" autofocus/>--}}
{{--                            <x-input-error :messages="$errors->get('date')" class="my-2"/>--}}

{{--                            <x-input-label for="day" :value="__('مهلت انجام وظیفه')" class="my-2"/>--}}
{{--                            <x-text-input wire:model="day" id="day"--}}
{{--                                          class="block my-2 w-full" type="text" autofocus/>--}}
{{--                            <x-input-error :messages="$errors->get('day')" class="my-2"/>--}}


{{--                            <x-input-label for="file" :value="__('فایل')" class="my-2"/>--}}
{{--                            <input wire:model="files" multiple--}}
{{--                                   class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"--}}
{{--                                   id="file" type="file">--}}
{{--                            <x-input-error :messages="$errors->get('files')" class="my-2"/>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="mt-6">--}}
{{--                        <x-primary-button type="submit">--}}
{{--                            {{ __('ارسال') }}--}}
{{--                        </x-primary-button>--}}
{{--                        <a href="{{Illuminate\Support\Facades\URL::signedRoute('tasks.index')}}">--}}
{{--                            <x-secondary-button>--}}
{{--                                {{__('لغو')}}--}}
{{--                            </x-secondary-button>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}


</div>
