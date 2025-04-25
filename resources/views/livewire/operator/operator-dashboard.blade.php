<div>
    <div class="grid lg:grid-cols-2 mt-20">
        <div class="p-2">
            <x-notifications/>
        </div>

        <div class="px-8 py-6 rounded-xl h-[600px] overflow-y-auto space-y-6">
            @if ($this->getTodaysMeeting->isNotEmpty())
                <h1 class="text-2xl font-semibold text-[#4332BD] text-center mb-4">
                    {{ __('لیست جلسات امروز') }}
                </h1>

                <div class="grid gap-5">
                    @foreach ($this->getTodaysMeeting as $meeting)
                        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-200">
                            <div class="space-y-4">
                                <h2 class="text-xl font-semibold text-[#4332BD]">{{ $meeting->title }}</h2>

                                <div class="flex flex-col space-y-2 text-sm text-gray-700">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </div>
                                        <span class="font-medium">{{ $meeting->time }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                            </svg>
                                        </div>
                                        <span class="font-mono">{{ $meeting->date }}</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                            </svg>
                                        </div>
                                        <span>{{ $meeting->location }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 mt-12">
                    <p>{{ __('هیچ جلسه‌ای برای امروز ثبت نشده است.') }}</p>
                </div>
            @endif
        </div>


    </div>
</div>
