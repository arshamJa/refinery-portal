<x-app-layout>

    <x-organizationDepartmentHeader/>

    {{-- Relation Department & Organization--}}
    <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="max-w-xl">
            <form action="{{route('departments.organizations.store')}}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <header>
                    <h2 class="text-lg mb-4 font-medium text-gray-900 dark:text-gray-100">
                        {{ __('ارتباط دپارتمان با سامانه') }}
                    </h2>
                </header>
                <div class="my-2">
                    <x-input-label :value="__('دپارتمان')" class="mb-2"/>
                    <div class="custom-select">
                        <div class="select-box">
                            <input type="text" class="tags_input" multiple name="departmentId" hidden>
                            <div class="selected-options"></div>
                            <div class="arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                                </svg>
                            </div>
                        </div>
                        <div class="options">
                            <div class="option-search-tags">
                                <input type="text" class="search-tags" placeholder="جست و جو ...">
                                <button type="button" class="clear">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5"
                                         stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6 18 18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="option all-tags" data-value="All">{{__('انتخاب همه')}}</div>
                            @foreach($departments as $department)
                                <div class="option"
                                     data-value="{{$department->id}}">{{$department->department_name}}</div>
                            @endforeach

                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('departmentId')" class="my-2"/>
                </div>

                <div class="mt-2 mb-4">
                    <x-input-label class="mb-2" :value="__('سامانه')"/>
                    <div class="custom-select">
                        <div class="select-box">
                            <input type="text" class="tags_input" multiple name="organization_ids" hidden>
                            <div class="selected-options"></div>
                            <div class="arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                                </svg>
                            </div>
                        </div>
                        <div class="options">
                            <div class="option-search-tags">
                                <input type="text" class="search-tags" placeholder="جست و جو ...">
                                <button type="button" class="clear">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5"
                                         stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6 18 18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="option all-tags" data-value="All">{{__('انتخاب همه')}}</div>
                            @foreach($organizations as $organization)
                                <div class="option"
                                     data-value="{{$organization->id}}">{{$organization->organization_name}}</div>
                            @endforeach
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('organization_ids')" class="my-2"/>
                </div>

                <x-primary-button type="submit">
                    {{ __('ذخیره') }}
                </x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>
