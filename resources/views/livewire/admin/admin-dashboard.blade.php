<div>
    <div class="mt-20">

        <div class="grid grid-cols-3 place-content-center w-full mb-10 gap-6">
            <div class="bg-white rounded-lg shadow-lg p-6 overflow-hidden relative">
                <div class="flex items-center justify-between z-10">
                    <div class="flex items-center space-x-3">
                        <span class="text-lg font-bold flex gap-2 text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
                            </svg>
                            {{__('تعداد کاربران')}}
                        </span>
                    </div>
                    <span class="text-3xl font-bold text-blue-600">{{$this->users}}</span>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 overflow-hidden relative">
                <div class="flex items-center justify-between z-10">
                    <div class="flex items-center space-x-3">
                        <span class="text-lg flex gap-2 font-bold  text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                        </svg>
                            {{__('تعداد سامانه')}}</span>
                    </div>
                    <span class="text-3xl font-bold text-blue-600">{{$this->organizations->count()}}</span>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6 overflow-hidden relative">
                <div class="flex items-center justify-between z-10">
                    <div class="flex items-center space-x-3">
                        <span class="text-lg flex gap-2 font-bold  text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"/>
                            </svg>
                            {{__('تعداد دپارتمان')}}
                        </span>
                    </div>
                    <span class="text-3xl font-bold text-blue-600">{{$this->departments}}</span>
                </div>
            </div>
        </div>


        <x-notifications/>
    </div>

</div>
