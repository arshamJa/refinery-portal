    <nav class="flex justify-between mb-4 mt-20">
        <ol class="inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0">
            <li>
                <a href="{{route('organization.department.manage')}}" class="{{ (\Illuminate\Support\Facades\Route::is('organization.department.manage')) ? 'active-breadcrumb underline underline-offset-2' : ''  }} inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    <span>   {{__('مدیریت سامانه/دپارتمان')}}</span>
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            <li>
                <a  href="{{route('department.organization.connection')}}" class="{{ (\Illuminate\Support\Facades\Route::is('department.organization.connection')) ? 'active-breadcrumb underline underline-offset-2' : ''  }} inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    {{__('ارتباط دپارتمان با سامانه')}}
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            <li>
                <a href="{{route('organizations')}}" class="{{ (\Illuminate\Support\Facades\Route::is('organizations')) ? 'active-breadcrumb underline underline-offset-2' : ''  }} inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    {{__('جدول سامانه')}}
                </a>
            </li>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3 h-3 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            <li>
                <a href="{{route('departments.index')}}" class="{{ (\Illuminate\Support\Facades\Route::is('departments.index')) ? 'active-breadcrumb underline underline-offset-2' : ''  }} inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                    {{__('جدول دپارتمان')}}
                </a>
            </li>
        </ol>
    </nav>


