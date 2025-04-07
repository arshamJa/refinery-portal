<x-app-layout>
    <x-sessionMessage name="status"/>
    <x-organizationDepartmentHeader/>


    <div class="pt-4 sm:px-10 sm:pt-6 border shadow-md rounded-md">
        <form method="GET" action="{{ route('organization.department.manage') }}">
            @csrf
            <div class="grid grid-cols-2 items-end gap-4">
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500"
                             fill="currentColor" viewbox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <x-text-input type="text" name="search"/>
                </div>
            </div>
            <div class="w-full flex gap-4 items-center pl-4 py-2 mt-1">
                <x-search-button>{{__('جست و جو')}}</x-search-button>
                @if ($originalUsersCount != $filteredUsersCount)
                    <x-view-all-link
                        href="{{route('organization.department.manage')}}">  {{__('نمایش همه')}}</x-view-all-link>
                @endif
            </div>
        </form>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead
                class="text-sm text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                @foreach (['ردیف', 'نام و نام خانوادگی', 'دپارتمان','سامانه','افزودن سامانه'] as $th)
                    <th class="px-4 py-3">{{ __($th) }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr class="border-b text-center">
                    <td class="px-4 py-3">{{$loop->index+1}}</td>
                    <td class="px-4 py-3">{{$user->user_info->full_name}}</td>
                    <td class="px-4 py-3">
                        {{$user->user_info->department->department_name}}
                    </td>
                    <td class="px-4 py-3">
                        @foreach($user->organizations as $org)
                            {{$org->organization_name}} ,
                        @endforeach
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{Illuminate\Support\Facades\URL::signedRoute('addOrganization',$user->id)}}">
                            <x-primary-button>  {{__('درج سامانه')}}</x-primary-button>
                        </a>
                    </td>
                </tr>
            @empty
                <tr class="border-b text-center">
                    <td colspan="6" class="py-6">
                        {{__('رکوردی یافت نشد...')}}
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <span class="p-2 mx-2">
                  {{ $users->withQueryString()->links(data:['scrollTo'=>false]) }}
            </span>
    </div>
</x-app-layout>
