@php use App\Enums\UserPermission; @endphp
<x-app-layout>
    @can('has-permission', UserPermission::PHONE_PERMISSIONS)
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
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                     stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
                <li class="flex items-center h-full">
                    <a href="{{route('phone-list.index')}}"
                       class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                        <span>{{__('دفترچه تلفنی')}}</span>
                    </a>
                </li>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                     stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
                <li>
                <span
                    class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                {{__('ویرایش اطلاعات کارمند')}}
                </span>
                </li>
            </ol>
        </nav>
        <form action="{{ route('operator-phones.update', $record->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="max-w-5xl p-6 bg-white shadow-lg rounded-2xl space-y-8 font-sans pb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('ویرایش اطلاعات کارمند') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-right">

                    {{-- Full Name --}}
                    <div>
                        <x-input-label for="full_name" :value="__('نام و نام خانوادگی')"/>
                        <x-text-input name="full_name" id="full_name"
                                      value="{{ old('full_name', $record->full_name) }}" class="block" type="text"/>
                        <x-input-error :messages="$errors->get('full_name')"/>
                    </div>

                    {{-- Department --}}
                    <div>
                        <x-input-label :value="__('دپارتمان')"/>
                        <x-select-input name="departmentId">
                            <option value="">...</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}"
                                    @selected(old('departmentId', $record->department_id) == $department->id)>
                                    {{ $department->department_name }}
                                </option>
                            @endforeach
                        </x-select-input>
                        <x-input-error :messages="$errors->get('departmentId')" class="my-2"/>
                    </div>

                    {{-- N Code, P Code, Position --}}
                    <div>
                        <x-input-label for="n_code" :value="__('کد ملی')"/>
                        <x-text-input name="n_code" id="n_code"
                                      value="{{ old('n_code', $record->n_code) }}" class="block" type="text"/>
                        <x-input-error :messages="$errors->get('n_code')"/>
                    </div>

                    <div>
                        <x-input-label for="p_code" :value="__('کد پرسنلی')"/>
                        <x-text-input name="p_code" id="p_code"
                                      value="{{ old('p_code', $record->p_code) }}" class="block" type="text"/>
                        <x-input-error :messages="$errors->get('p_code')"/>
                    </div>

                    <div>
                        <x-input-label for="position" :value="__('سمت')"/>
                        <x-text-input name="position" id="position"
                                      value="{{ old('position', $record->position) }}" class="block" type="text"/>
                        <x-input-error :messages="$errors->get('position')"/>
                    </div>

                    {{-- Phones --}}
                    <div>
                        <x-input-label for="phone" :value="__('شماره همراه')"/>
                        <x-text-input name="phone" id="phone"
                                      value="{{ old('phone', $record->phone) }}" class="block" type="text"/>
                        <x-input-error :messages="$errors->get('phone')"/>
                    </div>

                    <div>
                        <x-input-label for="house_phone" :value="__('شماره منزل')"/>
                        <x-text-input name="house_phone" id="house_phone"
                                      value="{{ old('house_phone', $record->house_phone) }}" class="block" type="text"/>
                        <x-input-error :messages="$errors->get('house_phone')"/>
                    </div>

                    <div>
                        <x-input-label for="work_phone" :value="__('شماره محل کار')"/>
                        <x-text-input name="work_phone" id="work_phone"
                                      value="{{ old('work_phone', $record->work_phone) }}" class="block" type="text"/>
                        <x-input-error :messages="$errors->get('work_phone')"/>
                    </div>

                </div>

                {{-- Actions --}}
                <div class="mt-8">
                    <x-primary-button type="submit" class="ml-4">{{ __('ذخیره') }}</x-primary-button>
                    <a href="{{ route('phone-list.index') }}">
                        <x-cancel-button>{{ __('لغو') }}</x-cancel-button>
                    </a>
                </div>
            </div>
        </form>
    @endcan

</x-app-layout>
