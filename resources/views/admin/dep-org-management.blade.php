<x-app-layout>


    <x-organizationDepartmentHeader/>

        <form method="GET" action="{{ route('organization.department.manage') }}">
            @csrf
            <div class="grid gap-4 px-3 sm:px-0 lg:grid-cols-6 items-end">
                <div>
                    <x-input-label for="full_name">{{ __('نام و نام حانوادگی') }}</x-input-label>
                    <x-text-input type="text" name="full_name" id="full_name" value="{{ request('full_name') }}"/>
                </div>
                <div>
                    <x-input-label for="department_name">{{ __('دپارتمان') }}</x-input-label>
                    <x-text-input type="text" name="department_name" id="department_name" value="{{ request('department_name') }}"/>
                </div>
                <div>
                    <x-input-label for="organization">{{ __('سامانه') }}</x-input-label>
                    <x-text-input type="text" name="organization" id="organization" value="{{ request('organization') }}"/>
                </div>
                <div class="col-span-6 lg:col-span-3 flex justify-start lg:justify-end flex-row gap-4 mt-4 lg:mt-0">
                    <x-search-button>{{__('جست و جو')}}</x-search-button>
                    @if ($originalUsersCount != $filteredUsersCount)
                        <x-view-all-link
                            href="{{route('organization.department.manage')}}">  {{__('نمایش همه')}}</x-view-all-link>
                    @endif
                </div>
            </div>
        </form>



    <div class="relative overflow-x-auto shadow-md sm:rounded-lg my-12">
        <x-table.table>
            <x-slot name="head">
                <x-table.row class="border-b whitespace-nowrap border-gray-200 dark:border-gray-700">
                        @foreach (['ردیف', 'نام و نام خانوادگی', 'دپارتمان','سامانه','افزودن سامانه'] as $th)
                            <x-table.heading
                                class="px-6 py-3 {{ !$loop->first ? 'border-r border-gray-200 dark:border-gray-700' : '' }}">
                                {{ __($th) }}
                            </x-table.heading>
                        @endforeach
                </x-table.row>
            </x-slot>
            <x-slot name="body">
                @forelse($users as $user)
                    <x-table.row class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-50">
                        <x-table.cell>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</x-table.cell>
                        <x-table.cell>{{$user->user_info->full_name}}</x-table.cell>
                        <x-table.cell>{{$user->user_info->department->department_name ?? 'وجود ندارد'}}</x-table.cell>
                        <x-table.cell>
                            @foreach($user->organizations as $org)
                                {{$org->organization_name}} ,
                            @endforeach
                        </x-table.cell>
                        <x-table.cell>
                            <a href="{{Illuminate\Support\Facades\URL::signedRoute('addOrganization',$user->id)}}">
                                <x-secondary-button>{{__('درج سامانه')}}</x-secondary-button>
                            </a>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="6" class="py-6">
                            {{__('رکوردی یافت نشد...')}}
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-table.table>
    </div>
    <div class="mt-2">
        {{ $users->withQueryString()->links(data:['scrollTo'=>false]) }}
    </div>

    <div class="pt-4 overflow-x-auto overflow-y-hidden sm:pt-6 bg-white pb-10">
        <x-table.table>
            <x-slot name="head">

            </x-slot>
            <x-slot name="body">


            </x-slot>
        </x-table.table>
    </div>

</x-app-layout>
