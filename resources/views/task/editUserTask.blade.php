{{--<x-app-layout>--}}
{{--    <div class="py-12 bg-gray-100" dir="rtl">--}}
{{--        <div class="max-w-7xl sm:px-6 lg:px-8 space-y-6">--}}
{{--            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">--}}
{{--                {{__('قسمت مورد نیاز به ویرایش')}} :--}}
{{--                <br>--}}
{{--                {{$task->request_task}}--}}

{{--                <form action="{{route('updateUserTask',$task->id)}}" method="post" enctype="multipart/form-data">--}}
{{--                    @csrf--}}
{{--                    @method('PUT')--}}
{{--                    <div class="max-w-xl mt-8">--}}
{{--                        <div class="mt-6 border-t border-gray-100">--}}

{{--                            <x-input-label for="title" :value="__('عنوان')" class="my-2"/>--}}
{{--                            <x-text-input value="{{$task->title}}" name="title" id="title"--}}
{{--                                          class="block my-2 w-full" type="text" autofocus/>--}}
{{--                            <x-input-error :messages="$errors->get('title')" class="my-2"/>--}}

{{--                            <x-input-label for="body" :value="__('متن')" class="mb-2"/>--}}
{{--                            <textarea type="text" name="body"--}}
{{--                                      class="flex w-full h-auto min-h-[80px] p-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">--}}
{{--                                {{value($task->body)}}--}}
{{--                            </textarea>--}}
{{--                            <x-input-error :messages="$errors->get('body')" class="mt-2"/>--}}

{{--                            <x-input-label for="sent_date" :value="__('تاریخ ارسال شده')" class="my-2"/>--}}
{{--                            <x-text-input value="{{$task->sent_date}}" name="sent_date" id="sent_date"--}}
{{--                                          class="block my-2 w-full" type="text" autofocus/>--}}
{{--                            <x-input-error :messages="$errors->get('sent_date')" class="my-2"/>--}}

{{--                            <x-input-label for="time_out" :value="__('مهلت انجام وظیفه')" class="my-2"/>--}}
{{--                            <x-text-input value="{{$task->time_out}}" name="time_out" id="time_out"--}}
{{--                                          class="block my-2 w-full" type="text" autofocus/>--}}
{{--                            <x-input-error :messages="$errors->get('time_out')" class="my-2"/>--}}


{{--                            <x-input-label for="file" :value="__('فایل')" class="my-2"/>--}}
{{--                            <input  name="files" multiple--}}
{{--                                   class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"--}}
{{--                                   id="file" type="file">--}}
{{--                            <x-input-error :messages="$errors->get('files')" class="my-2"/>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="mt-6">--}}
{{--                        <x-primary-button type="submit">--}}
{{--                            {{ __('اعمال تغییرات') }}--}}
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


{{--</x-app-layout>--}}
