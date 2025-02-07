<x-app-layout>
    <div class="p-4 md:mr-64 h-auto pt-20">
        <section class="bg-white">
            <div class="max-w-2xl px-4 py-8 mx-auto lg:py-16">
                <h2 class="mb-4 text-xl font-bold text-gray-900">{{__('ویرایش اطلاعات')}}</h2>
                <form action="{{route('phones.update',$userInfo->id)}}" method="post">
                    @csrf
                    @method('put')
                    <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                        <div class="w-full">
                            <x-input-label for="unit" :value="__('واحد')"/>
                            <x-text-input name="unit" id="unit" value="{{$userInfo->unit}}" class="block mt-1 w-full"
                                          type="text" required autofocus/>
                            <x-input-error :messages="$errors->get('unit')" class="mt-2"/>
                        </div>
                        <div class="w-full">
                            <x-input-label for="full_name" :value="__('نام و نام خانوادگی')"/>
                            <x-text-input name="full_name" id="full_name" value="{{$userInfo->full_name}}" class="block mt-1 w-full"
                                          type="text" required autofocus/>
                            <x-input-error :messages="$errors->get('full_name')" class="mt-2"/>
                        </div>
                        <div class="w-full">
                            <x-input-label for="work_phone" :value="__('تلفن محل کار')"/>
                            <x-text-input name="work_phone" id="work_phone" value="{{$userInfo->work_phone}}" class="block mt-1 w-full"
                                          type="text" required autofocus/>
                            <x-input-error :messages="$errors->get('work_phone')" class="mt-2"/>
                        </div>
                        <div class="w-full">
                            <x-input-label for="house_phone" :value="__('تلفن منزل')"/>
                            <x-text-input name="house_phone" id="house_phone" value="{{$userInfo->house_phone}}" class="block mt-1 w-full"
                                          type="text" required autofocus/>
                            <x-input-error :messages="$errors->get('house_phone')" class="mt-2"/>
                        </div>
                        <div class="w-full">
                            <x-input-label for="phone" :value="__('تلفن همراه')"/>
                            <x-text-input name="phone" id="phone" value="{{$userInfo->phone}}" class="block mt-1 w-full"
                                          type="text" required autofocus/>
                            <x-input-error :messages="$errors->get('phone')" class="mt-2"/>
                        </div>

                    </div>
                    <div class="flex items-center space-x-4">
                        <button type="submit"
                                class="text-gray-100 bg-primary-100 bg-gray-900 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            {{__('ثبت')}}
                        </button>
                        <a href="{{route('phones.index')}}"
                           class="text-gray-100 bg-primary-100 bg-gray-900 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            {{__('لغو')}}
                        </a>
                    </div>
                </form>
            </div>
        </section>
    </div>
</x-app-layout>
