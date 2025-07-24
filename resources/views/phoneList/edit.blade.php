<x-app-layout>
    <div dir="rtl">
        <form action="{{ route('phone-list.update', ['source' => $source, 'id' => $record->id]) }}" method="POST">
            @csrf
            @method('put')
            <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <div>
                            <p class="text-gray-700 dark:text-gray-300">
                                {{ __('نام و نام خانوادگی :') }}
                                <span class="font-medium">{{ $record->full_name }}</span>
                            </p>

                            @if($source === 'user_info')
                                <p class="text-gray-700 dark:text-gray-300 mt-1 mb-3">
                                    {{ __('دپارتمان :') }}
                                    <span class="font-medium">
                                {{ $record->department?->department_name ?? '—' }}
                            </span>
                                </p>
                            @endif

                            <x-input-label for="phone" :value="__('شماره همراه')"/>
                            <x-text-input name="phone" id="phone" maxlength="11"
                                          value="{{ old('phone', $record->phone) }}" class="block my-2 w-full"
                                          type="text" autofocus/>
                            <x-input-error :messages="$errors->get('phone')" class="my-2"/>

                            <x-input-label for="house_phone" :value="__('شماره منزل')"/>
                            <x-text-input name="house_phone" id="house_phone"
                                          value="{{ old('house_phone', $record->house_phone) }}"
                                          class="block my-2 w-full" type="text"/>
                            <x-input-error :messages="$errors->get('house_phone')" class="my-2"/>

                            <x-input-label for="work_phone" :value="__('شماره محل کار')"/>
                            <x-text-input name="work_phone" id="work_phone"
                                          value="{{ old('work_phone', $record->work_phone) }}"
                                          class="block my-2 w-full" type="text"/>
                            <x-input-error :messages="$errors->get('work_phone')" class="my-2"/>
                        </div>
                    </section>
                </div>

                <div class="mt-8">
                    <x-primary-button type="submit" class="ml-4">
                        {{ __('ذخیره') }}
                    </x-primary-button>
                    <a href="{{ route('phone-list.index') }}">
                        <x-cancel-button>{{ __('لغو') }}</x-cancel-button>
                    </a>
                </div>
            </div>
        </form>

    </div>
</x-app-layout>
