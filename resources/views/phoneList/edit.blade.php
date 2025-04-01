<x-app-layout>
    <div dir="rtl">
        <form action="{{route('phone-list.update',$userInfo->id)}}" method="POST">
            @csrf
            @method('put')
            <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <div>
                            <p>
                                {{__('نام و نام خانوادگی : ')}}{{$userInfo->full_name}}
                            </p>
                            <p class="my-4">
                                {{__('دپارتمان : ')}}{{$userInfo->department?->department_name}}
                            </p>

                            <x-input-label for="phone" :value="__('شماره همراه')"/>
                            <x-text-input name="phone" id="phone" maxlength="11"
                                          value="{{$userInfo->phone}}" class="block my-2 w-full"
                                          type="text" autofocus/>
                            <x-input-error :messages="$errors->get('phone')" class="my-2"/>

                            <x-input-label for="house_phone" :value="__('شماره منزل')"/>
                            <x-text-input name="house_phone" id="house_phone" value="{{$userInfo->house_phone}}"
                                          class="block my-2 w-full" type="text" autofocus/>
                            <x-input-error :messages="$errors->get('house_phone')" class="my-2"/>

                            <x-input-label for="work_phone" :value="__('شماره محل کار')"/>
                            <x-text-input name="work_phone" id="work_phone" value="{{$userInfo->work_phone}}"
                                          class="block my-2 w-full" type="text" autofocus/>
                            <x-input-error :messages="$errors->get('work_phone')" class="my-2"/>

                        </div>
                    </section>
                </div>
                <div class="mt-8">
                    <x-primary-button type="submit">
                        {{ __('ذخیره') }}
                    </x-primary-button>
                    <a href="{{route('phone-list.index')}}">
                        <x-secondary-button>
                            {{__('لغو')}}
                        </x-secondary-button>
                    </a>
                </div>
            </div>

        </form>
    </div>


</x-app-layout>
