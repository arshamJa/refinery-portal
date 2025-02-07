<div class="max-w-6xl mx-auto my-16">
    <h5 class="text-center text-5xl font-bold py-3">{{__('لیست کاربران')}}</h5>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 p-2 ">
        @foreach ($users as $key=> $user)
            {{-- child --}}
            <div class="w-full bg-white flex flex-col justify-between border border-gray-200 rounded-lg p-5 shadow">

                <div class="flex flex-col items-center pb-2">
                    <img src="https://api.dicebear.com/6.x/fun-emoji/svg?seed={{$key}}" alt="image"
                         class="w-20 h-20 mb-2 rounded-full shadow-lg">
                    <span class="text-sm text-gray-500">{{$user->user_info->full_name}}</span>
                </div>
                <div class="flex justify-between mt-4 md:mt-6">
                    <x-secondary-button>
                        Add Friend
                    </x-secondary-button>
                    <x-primary-button wire:click="message({{$user->id}})">
                        {{__('مکالمه')}}
                    </x-primary-button>

                </div>
            </div>
        @endforeach
    </div>
</div>
