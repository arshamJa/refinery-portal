<div>

    <x-header header="ارتباط سامانه با دپارتمان"/>
        <div class="py-12" dir="rtl">
            <div class="max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <form wire:submit="create">
                    @foreach($this->departments as $department)
                        <div class="p-4 mb-4 bg sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                            <div class="max-w-xl">
                                <section>
                                    <header>
                                        <h2 class="text-lg mb-4 font-medium text-gray-900 dark:text-gray-100">
                                            {{$department->department_name}}
                                        </h2>
                                    </header>
                                    <div>
                                        {{$department->organizations->value('organization_name')}}
                                    </div>
                                </section>
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>
</div>
