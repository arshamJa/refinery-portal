<x-app-layout>
    <div class="p-4 md:mr-64 h-auto pt-20">
        <section class="bg-white">
            <div class="max-w-2xl px-4 py-8 mx-auto lg:py-16">
                <h2 class="mb-4 text-xl font-bold text-gray-900">{{__('نمایش اطلاعات')}}</h2>
                    <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                        <div class="w-full">
                            <p class="border-b-2">
                                <span>{{__('واحد')}}</span>
                                <br>
                                <span>{{$userInfo->unit}}</span>
                            </p>
                        </div>
                        <div class="w-full">
                            <p class="border-b-2">
                                <span>{{__('نام و نام خانوادگی')}}</span>
                                <br>
                                <span>{{$userInfo->full_name}}</span>
                            </p>
                        </div>
                        <div class="w-full">
                            <p class="border-b-2">
                                <span>{{__('تلفن محل کار')}}</span>
                                <br>
                                <span>{{$userInfo->work_phone}}</span>
                            </p>
                        </div>
                        <div class="w-full">
                            <p class="border-b-2">
                                <span>{{__('تلفن منزل')}}</span>
                                <br>
                                <span>{{$userInfo->house_phone}}</span>
                            </p>
                        </div>
                        <div class="w-full">
                            <p class="border-b-2">
                                <span>{{__('تلفن همراه')}}</span>
                                <br>
                                <span>{{$userInfo->phone}}</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{route('phones.index')}}"
                           class="text-gray-100 bg-primary-100 bg-gray-900 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            {{__('بازگشت')}}
                        </a>
                    </div>

            </div>
        </section>
    </div>
</x-app-layout>
