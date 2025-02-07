<div class="mt-4 flex gap-x-3">


    @foreach($this->blogImages as $blogImage)
        <div class="flex flex-col gap-3 items-center justify-center">
            <img src="{{ url('storage/'.$blogImage->image) }}" class="mb-4" alt=""/>
            <x-danger-button wire:click.prevent="deleteImage({{$blogImage->id}})">
                {{__('حذف')}}
            </x-danger-button>
        </div>
    @endforeach


</div>
