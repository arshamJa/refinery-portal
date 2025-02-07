@props(['name'])

@if (session($name))
    <div
        x-data="{ showMessage: true }" x-show="showMessage" x-transition x-cloak
        x-init="setTimeout(() => showMessage = false, 3000)"
        dir="rtl" class="fixed top-5 right-5 z-[99] max-w-xs bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-neutral-800 dark:border-neutral-700">
        <div class="flex p-4">
            <div class="shrink-0">
                <svg class="shrink-0 size-4 text-yellow-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"></path>
                </svg>
            </div>
            <div class="ms-3">
                <p class="text-sm text-gray-700 dark:text-neutral-400">
                    {{ session($name) }}
                </p>
            </div>
        </div>
    </div>
@endif
