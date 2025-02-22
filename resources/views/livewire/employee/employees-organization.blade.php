<div>
    {{--     <x-template>--}}
    <div class="pt-4 w-1/2 sm:pt-6 mt-14">
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
            <input type="text" wire:model.live="search" id="simple-search" dir="rtl"
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full"
                   placeholder="جست و جو سامانه ..." required="">
        </div>
    </div>
    <div class="mt-4 w-full grow grid grid-cols-3 gap-1 pb-4">
        @foreach($this->organizationUsers as $organizationUser)
            @foreach($this->organizations->where('id',$organizationUser->organization_id) as $organization)
                <div class="h-20 p-2">
                    <div class="flex justify-start items-center border-gray-900">
                        @if($organization->image)
                            <img src="{{$organization->getImageUrl()}}" class="rounded-md w-20 h-20" alt="">
                        @endif
                        <div class="flex flex-col gap-y-2 mr-4">
                            <p class="text-gray-500 dark:text-gray-400">
                                {{$organization->organization_name}}
                            </p>
                            <h3 class=" text-xl font-bold dark:text-white">
                                <x-nav-link href="{{$organization->url}}" target="_blank"
                                            class="hover:cursor-pointer">
                                    {{$organization->url}}
                                </x-nav-link>
                            </h3>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
    {{--     </x-template>--}}
</div>
