<div>

    <x-sessionMessage name="status"/>



    @can('delete-blog')
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
        @endcan


    <x-template>
        <div class="bg-white md:pt-10 pt-16 px-2" dir="rtl">
                <div class="flex justify-between items-end max-w-full gap-x-8">
                    <input wire:model.live.debounce.500ms="search" type="text" dir="rtl"
                           placeholder="عنوان اخبار"
                           class="block w-1/3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                    @can('create-blog')
                        <a href="{{Illuminate\Support\Facades\URL::signedRoute('blogs.create')}}">
                            <x-primary-button>
                                {{__('درج اخبار جدید')}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </x-primary-button>
                        </a>
                    @endcan
                </div>
                <div
                    class="mx-auto mt-10 grid max-w-3xl grid-cols-1 gap-x-8 gap-y-16  sm:mt-16  lg:mx-0 lg:max-w-none lg:grid-cols-3">
                    @forelse($this->blogs as $blog )
                        <article class="flex max-w-xl flex-col items-start justify-between">
                            <div class="flex items-center justify-between w-full text-sm text-gray-500">
                                {{$blog->created_at->diffForHumans()}}
                            </div>
                            <div class="group relative">
                                <h3 class="mt-3 text-lg/6 font-semibold text-gray-900">
                                    {{$blog->title}}
                                </h3>
                                <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600">{{$blog->body}}</p>
                            </div>
                            <div class="relative mt-8 flex items-center gap-x-4">
                                <x-buttons.show-button href="{{Illuminate\Support\Facades\URL::signedRoute('blogs.show',$blog->id)}}"/>
                                @can('update-blog')
                                    <x-buttons.edit-button
                                        href="{{Illuminate\Support\Facades\URL::signedRoute('blogs.edit',$blog->id)}}"/>
                                @endcan
                                @can('delete-blog')
                                    <x-danger-button wire:click="confirmDelete({{$blog->id}})">
                                        {{__('حذف')}}
                                    </x-danger-button>
                                @endcan
                            </div>
                        </article>
                    @empty
                        {{__('رکوردی یافت نشد...')}}
                    @endforelse
                </div>
                <nav
                    class="flex flex-col md:flex-row mt-8 justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                    aria-label="Table navigation">
                    {{$this->blogs->withQueryString()->links(data:['scrollTo'=>false]) }}
                </nav>
            </div>
    </x-template>

</div>
