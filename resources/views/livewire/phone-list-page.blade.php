@php use App\Enums\UserRole;use App\Models\User; @endphp
<div>

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
            <li>
                <a href="{{route('dashboard')}}"
                   class="inline-flex items-center px-2 py-1.5 cursor-default active-breadcrumb space-x-1.5 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M14.25 9.75v-4.5m0 4.5h4.5m-4.5 0 6-6m-3 18c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 0 1 4.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 0 0-.38 1.21 12.035 12.035 0 0 0 7.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 0 1 1.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 0 1-2.25 2.25h-2.25Z"/>
                    </svg>
                    <span>{{__('دفترچه تلفنی')}}</span>
                </a>
            </li>
        </ol>
    </nav>

    <div class="bg-white px-3 relative shadow-md sm:rounded-lg overflow-hidden">

        <form wire:submit="search" class="space-y-4">
           <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 px-3 pt-3">
                {{-- Global Search Input --}}
                <div class="col-span-2">
                    <x-input-label for="search">{{ __('جست و جو') }}</x-input-label>
                    <x-text-input type="text" wire:model.defer="query" id="search" class="w-full" placeholder="عبارت خود را بنویسید..."/>
                </div>

                {{-- Role Filter --}}
                <div class="col-span-2">
                    <x-input-label for="role">{{ __('نقش') }}</x-input-label>
                    <select wire:model.defer="role" id="role"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                        <option value="">{{ __('همه نقش ها') }}</option>
                        @foreach($this->roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="w-full flex gap-4 items-center px-3 pb-3">
                <x-search-button>{{ __('جست و جو') }}</x-search-button>

                @if ($this->originalUsersCount != $this->filteredUsersCount)
                    <x-view-all-link href="{{ route('phone-list.index') }}">{{ __('نمایش همه') }}</x-view-all-link>
                @endif
            </div>
        </form>

        <div class="pt-4 overflow-x-auto overflow-y-hidden sm:pt-6 bg-white pb-10">
            <x-table.table>
                <x-slot name="head">
                    @php
                        $columns = ['ردیف', 'دپارتمان', 'نام و نام خانوادگی', 'تلفن محل کار'];

                        if(auth()->user()->hasAnyRoles([UserRole::SUPER_ADMIN->value, UserRole::ADMIN->value])) {
                            array_splice($columns, 1, 0, ['نقش']); // insert 'نقش' at index 1
                        }
                    @endphp

                    @foreach ($columns as $th)
                        <x-table.heading>{{ __($th) }}</x-table.heading>
                    @endforeach
                    {{--                    @can('viewAny',User::class)--}}
                    <x-table.heading>{{ __('تلفن همراه') }}</x-table.heading>
                    <x-table.heading>{{ __('تلفن منزل') }}</x-table.heading>
                    <x-table.heading>{{ __('قابلیت') }}</x-table.heading>
                    {{--                    @endcan--}}
                </x-slot>
                <x-slot name="body">
                    @forelse($this->userInfos as $userInfo)
                        <x-table.row>
                            <x-table.cell>{{ ($this->userInfos->currentPage() - 1) * $this->userInfos->perPage() + $loop->iteration }}</x-table.cell>
                            <x-table.cell>
                                @if(auth()->user()->hasAnyRoles([UserRole::SUPER_ADMIN->value, UserRole::ADMIN->value]))
                                    {{ $userInfo->user->roles->pluck('name')->implode(', ') ?: __('بدون نقش') }}
                                @endif
                            </x-table.cell>
                            <x-table.cell>{{ $userInfo->department->department_name ?? 'بدون واحد'}}</x-table.cell>
                            <x-table.cell> {{ $userInfo->full_name }}</x-table.cell>
                            <x-table.cell>{{ $userInfo->work_phone }}</x-table.cell>
                            {{--                            @can('viewAny',User::class)--}}
                            <x-table.cell> {{ $userInfo->phone }}</x-table.cell>
                            <x-table.cell>{{ $userInfo->house_phone }}</x-table.cell>
                            <x-table.cell>
                                <button wire:click="editUserInfo({{ $userInfo->id }})">
                                    {{ __('ویرایش') }}
                                </button>
                                {{--                                <a href="#">--}}
                                {{--                                    <x-primary-button>--}}
                                {{--                                        {{__('ویرایش')}}--}}
                                {{--                                    </x-primary-button>--}}
                                {{--                                </a>--}}
                            </x-table.cell>
                            {{--                                <x-dropdown>--}}
                            {{--                                    <x-slot name="trigger">--}}
                            {{--                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"--}}
                            {{--                                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"--}}
                            {{--                                             class="size-6 hover:cursor-pointer">--}}
                            {{--                                            <path stroke-linecap="round" stroke-linejoin="round"--}}
                            {{--                                                  d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>--}}
                            {{--                                        </svg>--}}
                            {{--                                    </x-slot>--}}
                            {{--                                    <x-slot name="content">--}}
                            {{--                                        <x-dropdown-link--}}
                            {{--                                            href="#">--}}
                            {{--                                            {{__('نمایش')}}--}}
                            {{--                                        </x-dropdown-link>--}}
                            {{--                                        <x-dropdown-link--}}
                            {{--                                            href="#">--}}
                            {{--                                            {{__('ویرایش')}}--}}
                            {{--                                        </x-dropdown-link>--}}
                            {{--                                    </x-slot>--}}
                            {{--                                </x-dropdown>--}}
                            {{--                            @endcan--}}
                        </x-table.row>

                    @empty
                        <x-table.row>
                            <x-table.cell colspan="7" class="py-6">
                                {{__('رکوردی یافت نشد...')}}
                            </x-table.cell>
                        </x-table.row>
                    @endforelse
                </x-slot>
            </x-table.table>
            <span class="p-2 mx-2">
                {{ $this->userInfos->withQueryString()->links(data: ['scrollTo' => false]) }}
            </span>
        </div>
    </div>
    <x-modal name="update">
        @if($editingId)
            <form wire:submit="updatePhone">
                <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-start">
                    {{__('ویرایش اطلاعات')}}
                </div>
                <div class="px-6 py-4" dir="rtl">
                    <div class="mt-4 text-sm text-gray-600">
                        <div class="w-full">
                            <x-input-label for="full_name" :value="__('نام و نام خانوادگی:')"/>
                            <p>{{$full_name}}</p>

                            <x-input-label for="phone" :value="__('تلفن همراه')"/>
                            <x-text-input type="text" id="phone" wire:model.defer="phone" class="w-full"/>
                            <x-input-error :messages="$errors->get('phone')" class="my-2"/>

                            <x-input-label for="house_phone" :value="__('تلفن منزل')"/>
                            <x-text-input type="text" id="house_phone" wire:model="house_phone" class="w-full"/>
                            <x-input-error :messages="$errors->get('house_phone')" class="my-2"/>

                            <x-input-label for="work_phone" :value="__('تلفن محل کار')"/>
                            <x-text-input type="text" id="work_phone" wire:model="work_phone" class="w-full"/>
                            <x-input-error :messages="$errors->get('work_phone')" class="my-2"/>

                        </div>
                    </div>
                </div>
                <div class="flex flex-row justify-between px-6 py-4 bg-gray-100">
                    <x-secondary-button wire:click="closeModal">
                        {{ __('لفو') }}
                    </x-secondary-button>
                    <x-primary-button type="submit">
                        {{ __('ثبت') }}
                    </x-primary-button>
                </div>
            </form>
        @endif
    </x-modal>


</div>
