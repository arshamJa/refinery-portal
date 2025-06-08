<x-app-layout>

    @can('users-info')
        <form action="{{route('users.update',$userInfo->id)}}" method="POST">
            @csrf
            @method('put')
            <div class="max-w-5xl p-6 bg-white shadow-lg rounded-2xl space-y-8 font-sans">
                <div class="border-b pb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">{{__('ویرایش اطلاعات')}}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-right">
                        <!-- Role -->
                        <div>
                            <x-input-label for="role" :value="__('نقش')"/>
                            <select dir="ltr" name="role" id="role"
                                    class="my-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                {{--                            <option selected>...</option>--}}
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="my-2"/>
                        </div>

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
                            <x-text-input name="p_code" id="p_code" value="{{$userInfo->user->p_code}}"
                                          class="block my-2 w-full" maxlength="6"
                                          type="text" autofocus/>
                            <x-input-error :messages="$errors->get('p_code')" class="my-2"/>
                        </div>

                        <!-- National ID -->
                        <div>
                            <x-input-label for="n_code" :value="__('کد ملی')"/>
                            <x-text-input name="n_code" id="n_code" value="{{$userInfo->n_code}}"
                                          class="block my-2 w-full" maxlength="10"
                                          type="text" autofocus/>
                            <x-input-error :messages="$errors->get('n_code')" class="my-2"/>
                        </div>

                        <!-- Phone Numbers -->
                        <div>
                            <x-input-label for="phone" :value="__('شماره همراه')"/>
                            <x-text-input name="phone" id="phone" maxlength="11"
                                          value="{{$userInfo->phone}}" class="block my-2 w-full"
                                          type="text" autofocus/>
                            <x-input-error :messages="$errors->get('phone')" class="my-2"/>
                        </div>
                        <div>
                            <x-input-label for="house_phone" :value="__('شماره منزل')"/>
                            <x-text-input name="house_phone" id="house_phone" value="{{$userInfo->house_phone}}"
                                          class="block my-2 w-full" type="text" autofocus/>
                            <x-input-error :messages="$errors->get('house_phone')" class="my-2"/>
                        </div>
                        <div>
                            <x-input-label for="work_phone" :value="__('شماره محل کار')"/>
                            <x-text-input name="work_phone" id="work_phone" value="{{$userInfo->work_phone}}"
                                          class="block my-2 w-full" type="text" autofocus/>
                            <x-input-error :messages="$errors->get('work_phone')" class="my-2"/>
                        </div>

                        <!-- Position -->
                        <div>
                            <x-input-label for="position" :value="__('سمت')"/>
                            <x-text-input name="position" id="position" value="{{$userInfo->position}}"
                                          class="block my-2 w-full" type="text" autofocus/>
                            <x-input-error :messages="$errors->get('position')" class="my-2"/>
                        </div>
                    </div>
                </div>
                <!-- Department & Password Section -->
                <div class="border-b pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-right text-gray-700">


                        <!-- Department -->
                        <div class="col-span-3 md:w-1/3 w-full">

                            <x-input-label for="department" :value="__('انتخاب دپارتمان')"/>
                            <select dir="ltr" name="department" id="role"
                                    class="my-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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

                        <!-- Password -->
                        <div class="col-span-1">
                            <x-input-label for="password" :value="__('رمز ورود')"/>
                            <x-text-input name="password" id="password" maxlength="8" value="{{old('password')}}"
                                          class="block my-2 w-full" type="text" autofocus/>
                            <x-input-error :messages="$errors->get('password')" class="my-2"/>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="pb-6 border-b">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">تعیین بخش دسترسی هر کاربر:</h2>
                    <div class="space-y-3 text-right text-gray-700">
                        @foreach($permissions as $permission)
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    {{ $user->permissions->contains($permission->id) ? 'checked' : '' }}>
                                <label class="text-sm">{{ $permission->name }}</label>
                            </div>
                        @endforeach
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
    @endcan
</x-app-layout>
