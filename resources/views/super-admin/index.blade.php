<x-app-layout>
    @can('super-admin-only')
        <div class="py-10 mt-4 px-4 sm:px-6 lg:px-8" dir="ltr">
            <h2 class="text-2xl font-bold mb-8 text-gray-800">Samael Panel</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Import Users -->
                <div class="bg-white shadow rounded-2xl p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-700">Import Users</h3>
                    <form action="{{ route('import.users') }}" method="POST" enctype="multipart/form-data"
                          class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Upload User File</label>
                            <input type="file" name="file"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <x-input-error :messages="$errors->get('file')" class="mt-2 text-red-600 text-sm"/>
                        </div>
                        <x-primary-button type="submit">Import Users</x-primary-button>
                    </form>
                </div>

            </div>
        </div>
    @endcan
</x-app-layout>
