<div class="mb-2 grid grid-cols-3 items-center gap-2">
    @foreach($this->userIds as $userId)
        <div class="mt-2">
            {{\App\Models\UserInfo::where('user_id',$userId->user_id)->value('full_name')}}
            <x-danger-button wire:click="deleteUser({{$userId->user_id}})">
                {{__('حذف')}}
            </x-danger-button>
        </div>
    @endforeach
</div>
