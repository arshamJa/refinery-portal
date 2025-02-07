<x-app-layout>
    <x-header header="نمایش اخبار"/>


    @if($blogImages->count() > 0)
        <!-- Slider -->
        <div data-hs-carousel='{
    "isAutoHeight": true,
    "loadingClasses": "opacity-0",
    "dotsItemClasses": "hs-carousel-active:bg-gray-900 hs-carousel-active:border-gray-700 size-3 border border-gray-900 rounded-full cursor-pointer dark:border-neutral-600 dark:hs-carousel-active:bg-blue-500 dark:hs-carousel-active:border-blue-500"
  }' class="relative max-w-3xl mt-4 mx-auto">
            <div class="hs-carousel relative overflow-hidden w-full bg-white rounded-lg">
                <div
                    class="hs-carousel-body flex flex-nowrap overflow-hidden transition-[height,transform] duration-700 opacity-0">
                    @foreach($blogImages as $blogImage)
                        <div class="hs-carousel-slide h-96">
                            <div class="flex justify-center h-full bg-gray-200 p-6 dark:bg-neutral-700">
                        <span class="self-center px-3 text-4xl text-gray-800 transition duration-700 dark:text-white">
                              <img class="h-96" src="{{ url('storage/'.$blogImage->image) }}" alt=""/>
                        </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="button"
                    class="hs-carousel-prev hs-carousel-disabled:opacity-50 hs-carousel-disabled:pointer-events-none absolute inset-y-0 start-0 inline-flex justify-center items-center w-[46px] h-full text-gray-800  focus:outline-none rounded-s-lg dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10">
                <span class="text-2xl" aria-hidden="true">
                  <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                       viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"></path>
                  </svg>
                </span>
                <span class="sr-only">Previous</span>
            </button>
            <button type="button"
                    class="hs-carousel-next hs-carousel-disabled:opacity-50 hs-carousel-disabled:pointer-events-none absolute inset-y-0 end-0 inline-flex justify-center items-center w-[46px] h-full text-gray-800  focus:outline-none rounded-e-lg dark:text-white dark:hover:bg-white/10 dark:focus:bg-white/10">
                <span class="sr-only">Next</span>
                <span class="text-2xl" aria-hidden="true">
                <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                  <path d="m9 18 6-6-6-6"></path>
                </svg>
                </span>
            </button>

            <div
                class="hs-carousel-pagination flex mt-2 justify-center relative bottom-0 start-0 end-0 space-x-2"></div>
        </div>
        <!-- End Slider -->
    @endif

    <div class="py-12" dir="rtl">


        <div class="max-w-7xl sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                <div>
                    <h5 class="text-xl mt-4 font-bold block mb-3 leading-none tracking-tight text-neutral-900">{{$blog->title}}</h5>
                    <p class="mb-4 text-sm text-neutral-500">{{$blog->body}}</p>
                </div>
            </div>
            <div class="flex items-center space-x-4 gap-2">
                <a href="{{route('blogs.index')}}" class="flex">
                    <x-secondary-button class="mr-2">
                        {{__('بازگشت')}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-4 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/>
                        </svg>
                    </x-secondary-button>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

