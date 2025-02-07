@php use Illuminate\Support\Facades\DB; @endphp
<div>

    <x-sessionMessage name="status"/>

    <x-template>
        <x-organizationDepartmentHeader/>
        <!-- Start coding here -->
        <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">
            <div
                class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-3/6 flex items-center">
                    <label for="simple-search" class="sr-only">Search</label>
                    <div class="relative w-full">
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
                        <input wire:model.live.debounce.500ms="search" type="text" dir="rtl"
                               placeholder="جست و جو ..."
                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

            </div>
            <div class="overflow-x-auto" dir="rtl">
                <x-table.table>
                    <x-slot name="head">
                        <th scope="col" class="px-4 py-3">{{__('ردیف')}}</th>
                        <th scope="col" class="px-4 py-3">{{__('نام و نام خانوادگی')}}</th>
                        <th scope="col" class="px-4 py-3">{{__('دپارتمان')}}</th>
                        <th scope="col" class="px-4 py-3">{{__('سامانه')}}</th>
                        <th scope="col" class="px-4 py-3">{{__('افزودن سامانه')}}</th>
                    </x-slot>

                    <x-slot name="body">

                        @forelse($this->users as $user)
                            <tr class="border-b text-center" wire:key="{{$user->id}}">
                                <td class="px-4 py-3">{{$user->id}}</td>
                                <td class="px-4 py-3">{{$user->user_info->full_name}}</td>
                                <td class="px-4 py-3">
                                    {{$this->departments->where('id',$user->user_info->department_id)->value('department_name')}}
                                </td>
                                <td class="px-4 py-3">
                                    @foreach($this->organizationUsers->where('user_id',$user->id) as $organization)
                                        @foreach($this->organizations->where('id',$organization->organization_id) as $org)
                                            {{$org->organization_name}}
                                        @endforeach
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
                    </x-slot>
                </x-table.table>
                <nav
                    class="flex flex-col md:flex-row mt-8 justify-between items-start md:items-center space-y-3 md:space-y-0 p-4">
                    {{ $this->users->withQueryString()->links(data:['scrollTo'=>false]) }}
                </nav>
            </div>
        </div>
    </x-template>
</div>
