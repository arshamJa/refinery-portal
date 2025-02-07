<x-app-layout>
    <x-header header="آپدیت اخبار"/>
    <div class="py-12 " dir="rtl">
        <div class="max-w-7xl sm:px-6 lg:px-8 space-y-6">
            <form action="{{route('blogs.update',$blog->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                    <div class="w-full">
                        <x-input-label for="title" :value="__('عنوان')"/>
                        <x-text-input name="title" id="title" value="{{$blog->title}}" class="block my-2 w-full"
                                      type="text"  autofocus/>
                        <x-input-error :messages="$errors->get('title')" class="mt-2"/>

                        <x-input-label for="body" :value="__('متن')" class="mb-2"/>
                        <textarea type="text" name="body"
                                  class="flex w-full h-auto min-h-[80px] px-3 py-2 text-sm bg-white border rounded-md border-neutral-300 placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                                {{old('body',$blog->body)}}
                            </textarea>
                        <x-input-error :messages="$errors->get('body')" class="mt-2"/>
                        <div class="w-full">
                            @if($blog->blogImages->count() > 0)
                                <livewire:operator.edit-blog-image :blog="$blog->id"/>
                            @endif
                        </div>
                        <x-input-label for="images" :value="__('آپلود عکس')" class="my-2"/>
                        <x-text-input multiple name="images[]" id="images"
                                      value="{{old('images')}}" class="block my-2 p-2 w-full" type="file"
                                      autofocus/>
                        <x-input-error :messages="$errors->get('images')" class="mt-2"/>
                    </div>
                </div>
                <div class="flex items-center space-x-4 gap-2">
                <x-primary-button type="submit">{{__('بروزرسانی')}}</x-primary-button>
                <a href="{{route('blogs.index')}}">
                    <x-secondary-button>{{__('لغو')}}</x-secondary-button>
                </a>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
