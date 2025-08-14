<div>
    @can('admin-role')
        <div class="grid lg:grid-cols-2 mt-16">
            <div class="p-2">
                <x-notifications/>
            </div>
            <div class="px-8 pb-6 pt-2 rounded-xl h-[600px] overflow-y-auto space-y-6">
                <x-meeting-time-table/>
            </div>
        </div>
    @endcan
</div>
