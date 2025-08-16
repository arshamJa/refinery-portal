@php use App\Enums\UserRole; @endphp
<x-app-layout>
    @can('admin-role')
        <nav class="flex justify-between mb-4 mt-16">
            <ol class="inline-flex items-center mb-3 space-x-1 text-xs text-neutral-500 [&_.active-breadcrumb]:text-neutral-600 [&_.active-breadcrumb]:font-medium sm:mb-0">
                <li class="flex items-center h-full">
                    <a href="{{route('dashboard')}}"
                       class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13.6986 3.68267C12.7492 2.77246 11.2512 2.77244 10.3018 3.68263L4.20402 9.52838C3.43486 10.2658 3 11.2852 3 12.3507V19C3 20.1046 3.89543 21 5 21H8.04559C8.59787 21 9.04559 20.5523 9.04559 20V13.4547C9.04559 13.2034 9.24925 13 9.5 13H14.5456C14.7963 13 15 13.2034 15 13.4547V20C15 20.5523 15.4477 21 16 21H19C20.1046 21 21 20.1046 21 19V12.3507C21 11.2851 20.5652 10.2658 19.796 9.52838L13.6986 3.68267Z"
                                fill="currentColor"></path>
                        </svg>
                        <span>{{__('داشبورد')}}</span>
                    </a>
                </li>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                     stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
                <li>
                    <a href="{{route('users.index')}}"
                       class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">

                        <span> {{__('جدول کاربران')}}</span>
                    </a>
                    </span>
                </li>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                     stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
                <li>
                <span
                    class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
               <span>{{__('ویرایش اطلاعات')}}</span>
            </span>
                </li>
            </ol>
        </nav>
        <form action="{{route('users.update',$userInfo->id)}}" method="POST">
            @csrf
            @method('put')
            <div class="p-6 bg-white shadow-lg rounded-2xl space-y-8 font-sans">
                <div class="border-b pb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">{{__('ویرایش اطلاعات')}}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 sm:grid-cols-2 gap-4 text-right">
                        <!-- Name -->
                        <div>
                            <x-input-label for="full_name" :value="__('نام و نام خانوادگی')"/>
                            <x-text-input name="full_name" id="full_name"
                                          value="{{ old('full_name', $userInfo->full_name) }}"
                                          class="block my-2 w-full"
                                          type="text" autofocus/>
                            <x-input-error :messages="$errors->get('full_name')" class="my-2"/>
                        </div>
                        <!-- Personnel Code -->
                        <div>
                            <x-input-label for="p_code" :value="__('شماره پرسنلی')"/>
                            <x-text-input name="p_code" id="p_code"
                                          value="{{ old('p_code', $userInfo->user->p_code) }}"
                                          class="block my-2 w-full" maxlength="6"
                                          type="text" autofocus/>
                            <x-input-error :messages="$errors->get('p_code')" class="my-2"/>
                        </div>
                        <!-- National ID -->
                        <div>
                            <x-input-label for="n_code" :value="__('کد ملی')"/>
                            <x-text-input name="n_code" id="n_code"
                                          value="{{ old('n_code', $userInfo->n_code) }}"
                                          class="block my-2 w-full" maxlength="10"
                                          type="text" autofocus/>
                            <x-input-error :messages="$errors->get('n_code')" class="my-2"/>
                        </div>
                        <!-- Phone Numbers -->
                        <div>
                            <x-input-label for="phone" :value="__('شماره همراه')"/>
                            <x-text-input name="phone" id="phone" maxlength="11"
                                          value="{{old('phone',$userInfo->phone)}}" class="block my-2 w-full"
                                          type="text" autofocus/>
                            <x-input-error :messages="$errors->get('phone')" class="my-2"/>
                        </div>
                        <div>
                            <x-input-label for="house_phone" :value="__('شماره منزل')"/>
                            <x-text-input name="house_phone" id="house_phone"
                                          value="{{old('house_phone',$userInfo->house_phone)}}"
                                          class="block my-2 w-full" type="text" autofocus/>
                            <x-input-error :messages="$errors->get('house_phone')" class="my-2"/>
                        </div>
                        <div>
                            <x-input-label for="work_phone" :value="__('شماره محل کار')"/>
                            <x-text-input name="work_phone" id="work_phone"
                                          value="{{old('work_phone',$userInfo->work_phone)}}"
                                          class="block my-2 w-full" type="text" autofocus/>
                            <x-input-error :messages="$errors->get('work_phone')" class="my-2"/>
                        </div>
                        <!-- Position -->
                        <div>
                            <x-input-label for="position" :value="__('سمت')"/>
                            <x-text-input name="position" id="position"
                                          value="{{old('position',$userInfo->position)}}"
                                          class="block my-2 w-full" type="text" autofocus/>
                            <x-input-error :messages="$errors->get('position')" class="my-2"/>
                        </div>
                    </div>
                </div>
                <!-- Department & Password Section -->
                <div class="border-b pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 sm:grid-cols-2 gap-4 text-right">
                        <!-- Department -->
                        <div>
                            <x-input-label for="department" :value="__('انتخاب دپارتمان')"/>
                            <select dir="ltr" name="department" id="role"
                                    class="block w-full mt-1.5 text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="">...</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department', $userInfo->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('department')" class="my-2"/>
                        </div>
                        <div id="organizations_dropdown" data-users='@json($organization)' class="relative w-full"
                             style="direction: rtl;">
                            <x-input-label class="mb-1.5" :value="__('سامانه ها')"/>
                            <button id="organizations-dropdown-btn" type="button"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-right text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 flex justify-between items-center">
                                <span id="organizations-selected-text" class="truncate">انتخاب سامانه ها</span>
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div id="organizations-dropdown-menu"
                                 class="hidden absolute mt-2 w-full bg-white border border-gray-300 rounded-lg shadow-lg z-10">
                                <div class="px-4 py-2">
                                    <input id="organizations-dropdown-search" type="text" placeholder="جست و جو"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                </div>
                                <ul id="organizations-dropdown-list" class="max-h-48 overflow-auto"></ul>
                                <div id="organizations-no-result" class="px-4 py-2 text-gray-500 hidden">موردی یافت
                                    نشد
                                </div>
                            </div>
                            <div id="organizations-selected-container" class="mt-2 flex flex-wrap gap-2"></div>
                            <input type="hidden" name="organization" id="organizations-hidden-input"
                                   value='{{ json_encode(explode(",", old("organization", ""))) }}'>
                            <x-input-error :messages="$errors->get('organization')" class="mt-2"/>
                        </div>
                    </div>
                </div>

                <!-- Role & Permissions -->
                <div class="border-b pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 sm:grid-cols-2 gap-4 text-right">
                        <div class="col-span-4">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                                {{ __('تعیین بخش دسترسی کاربر:') }}
                            </h2>
                        </div>

                        {{-- Case 1: Super Admin (can edit anyone, including themselves) --}}
                        @if(auth()->user()->hasRole(UserRole::SUPER_ADMIN->value))
                            <div
                                x-data='{
                    selectedRole: @json(optional($user->roles->first())->id ?? ""),
                    rolePermissions: @json($rolePermissionsMap ?? []),
                    selectedPermissions: @json(old("permissions", $user->permissions->pluck("name")->toArray())),
                    updateSelectedPermissions() {
                        if (this.rolePermissions[this.selectedRole]) {
                            this.selectedPermissions = this.selectedPermissions.filter(
                                p => this.rolePermissions[this.selectedRole].includes(p)
                            );
                        } else {
                            this.selectedPermissions = [];
                        }
                    }
                }'
                                x-init="updateSelectedPermissions()"
                                x-cloak
                                class="col-span-4 grid grid-cols-1 md:grid-cols-4 sm:grid-cols-2 gap-4 text-right"
                            >
                                <!-- Role Selector -->
                                <div>
                                    <x-input-label for="role" :value="__('نقش')"/>
                                    <x-select-input name="role" x-model="selectedRole" @change="updateSelectedPermissions()">
                                        <option value="">...</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}"
                                                @selected(optional($user->roles->first())->id == $role->id)>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </x-select-input>
                                    <x-input-error :messages="$errors->get('role')" class="my-2"/>
                                </div>

                                <!-- Permissions -->
                                <div class="col-span-4">
                                    <x-input-label :value="__('تعیین دسترسی ها:')"/>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                                        @foreach($permissions as $permission)
                                            <div x-show="rolePermissions[selectedRole] && rolePermissions[selectedRole].includes('{{ $permission->name }}')">
                                                <label class="flex items-center space-x-2 space-x-reverse">
                                                    <input type="checkbox"
                                                           name="permissions[]"
                                                           value="{{ $permission->name }}"
                                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                           x-model="selectedPermissions">
                                                    <span class="text-sm">{{ $permission->name }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Case 2: Admin editing their own account (role locked) --}}
                        @elseif(auth()->id() === $userInfo->user->id && auth()->user()->hasRole(UserRole::ADMIN->value))
                            <div>
                                <x-input-label for="role" :value="__('نقش')"/>
                                <x-text-input value="{{ auth()->user()->getTranslatedRole() }}"
                                              class="block my-2 w-full bg-gray-100" disabled/>
                                <input type="hidden" name="role" value="{{ optional($user->roles->first())->id }}">
                            </div>
                            <div class="col-span-4">
                                <x-input-label :value="__('تعیین دسترسی ها:')"/>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                                    @foreach($permissions as $permission)
                                        <label class="flex items-center space-x-2 space-x-reverse">
                                            <input type="checkbox"
                                                   name="permissions[]"
                                                   value="{{ $permission->name }}"
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                @checked(in_array($permission->name, old('permissions', $user->permissions->pluck('name')->toArray())))>
                                            <span class="text-sm">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Case 3: Admin editing another user (role + permissions editable) --}}
                        @else
                            <div
                                x-data='{
                    selectedRole: @json(optional($user->roles->first())->id ?? ""),
                    rolePermissions: @json($rolePermissionsMap ?? []),
                    selectedPermissions: @json(old("permissions", $user->permissions->pluck("name")->toArray())),
                    updateSelectedPermissions() {
                        if (this.rolePermissions[this.selectedRole]) {
                            this.selectedPermissions = this.selectedPermissions.filter(
                                p => this.rolePermissions[this.selectedRole].includes(p)
                            );
                        } else {
                            this.selectedPermissions = [];
                        }
                    }
                }'
                                x-init="updateSelectedPermissions()"
                                x-cloak
                                class="col-span-4 grid grid-cols-1 md:grid-cols-4 sm:grid-cols-2 gap-4 text-right"
                            >
                                <!-- Role Selector -->
                                <div>
                                    <x-input-label for="role" :value="__('نقش')"/>
                                    <x-select-input name="role" x-model="selectedRole" @change="updateSelectedPermissions()">
                                        <option value="">...</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}"
                                                @selected(optional($user->roles->first())->id == $role->id)>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </x-select-input>
                                    <x-input-error :messages="$errors->get('role')" class="my-2"/>
                                </div>
                                <!-- Permissions -->
                                <div class="col-span-4">
                                    <x-input-label :value="__('تعیین دسترسی ها:')"/>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                                        @foreach($permissions as $permission)
                                            <div x-show="rolePermissions[selectedRole] && rolePermissions[selectedRole].includes('{{ $permission->name }}')">
                                                <label class="flex items-center space-x-2 space-x-reverse">
                                                    <input type="checkbox"
                                                           name="permissions[]"
                                                           value="{{ $permission->name }}"
                                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                           x-model="selectedPermissions">
                                                    <span class="text-sm">{{ $permission->name }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>


                <!-- Action Buttons -->
                <div class="flex space-x-4 space-x-reverse">
                    <x-accept-button type="submit">
                        {{__('ذخیره تغییرات')}}
                    </x-accept-button>
                    <a href="{{ route('users.index') }}">
                        <x-cancel-button>
                            {{__('لغو')}}
                        </x-cancel-button>
                    </a>
                </div>
            </div>
        </form>
        <div class="p-6 bg-white shadow-lg rounded-2xl space-y-8 font-sans my-4">
            <div class="pb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ __('لیست سامانه‌های در دسترس:') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 sm:grid-cols-2 gap-4 text-right text-gray-700">
                    @forelse($relatedOrganizations as $org)
                        <div
                            class="flex items-center justify-between bg-white border border-gray-200 shadow-sm px-4 py-3 rounded-xl">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium truncate">{{ $org->organization_name }}</span>
                            </div>
                            <form
                                action="{{ route('userOrganizationDelete', ['user' => $userInfo->user->id, 'organization' => $org->id]) }}"
                                method="POST"
                                onsubmit="return confirm('آیا مطمئن هستید که می‌خواهید این سازمان را از کاربر حذف کنید؟');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">
                                    {{ __('حذف') }}
                                </x-danger-button>
                            </form>
                        </div>
                    @empty
                        <div class="col-span-full text-gray-500 text-sm text-center py-4">
                            {{ __('هیچ سامانه‌ای به این کاربر اختصاص داده نشده است.') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endcan
</x-app-layout>
