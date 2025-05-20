<x-app-layout>
    <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 drop-shadow-md sm:rounded-lg">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 border-b pb-2">
                {{ __('ساخت کاربر جدید') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2">
                <div>
                    <x-input-label for="role" :value="__('نقش')"/>
                    <x-select-input name="role">
                        <option>...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" @selected(old('role') == $role->id)>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </x-select-input>
                    <x-input-error :messages="$errors->get('role')" class="my-2"/>
                </div>
                <div>
                    <x-input-label for="full_name" :value="__('نام و نام خانوادگی')"/>
                    <x-text-input name="full_name" id="full_name" value="{{old('full_name')}}" class="block" type="text"
                                  autofocus/>
                    <x-input-error :messages="$errors->get('full_name')"/>
                </div>
                <div>
                    <x-input-label for="p_code" :value="__('شماره پرسنلی')"/>
                    <x-text-input name="p_code" id="p_code" value="{{old('p_code')}}" class="block" type="text"
                                  maxlength="6" autofocus/>
                    <x-input-error :messages="$errors->get('p_code')"/>
                </div>
                <div>
                    <x-input-label for="n_code" :value="__('کد ملی')"/>
                    <x-text-input name="n_code" id="n_code" value="{{old('n_code')}}" class="block" type="text"
                                  maxlength="10" autofocus/>
                    <x-input-error :messages="$errors->get('n_code')"/>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2">
                <div>
                    <x-input-label for="phone" :value="__('تلفن همراه')"/>
                    <x-text-input name="phone" id="phone" value="{{old('phone')}}" class="block" type="text"
                                  maxlength="11" autofocus/>
                    <x-input-error :messages="$errors->get('phone')"/>
                </div>
                <div>
                    <x-input-label for="house_phone" :value="__('تلفن منزل')"/>
                    <x-text-input name="house_phone" id="house_phone" value="{{old('house_phone')}}" class="block"
                                  type="text" autofocus/>
                    <x-input-error :messages="$errors->get('house_phone')"/>
                </div>
                <div>
                    <x-input-label for="work_phone" :value="__('تلفن محل کار')"/>
                    <x-text-input name="work_phone" id="work_phone" value="{{old('work_phone')}}" class="block"
                                  type="text" autofocus/>
                    <x-input-error :messages="$errors->get('work_phone')"/>
                </div>
                <div>
                    <x-input-label for="position" :value="__('سمت')"/>
                    <x-text-input name="position" id="position" value="{{old('position')}}" class="block" type="text"
                                  autofocus/>
                    <x-input-error :messages="$errors->get('position')"/>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2">
                <div>
                    <x-input-label :value="__('دپارتمان')"/>
                    <x-select-input name="departmentId">
                        <option>...</option>
                        @foreach($departments as $department)
                            <option value="{{$department->id}}" @selected(old('departmentId') == $department->id)>{{$department->department_name}}</option>
                        @endforeach
                    </x-select-input>
                    <x-input-error :messages="$errors->get('departmentId')" class="my-2"/>
                </div>
                <div>
                    <x-input-label for="password" :value="__('رمز')"/>
                    <x-text-input name="password" id="password" class="block" type="text" maxlength="8" autofocus/>
                    <x-input-error :messages="$errors->get('password')" class="my-2"/>
                </div>
                <div>
                    <x-input-label for="signature" :value="__('امضا')"/>
                    <x-text-input name="signature" id="signature" value="{{old('signature')}}"
                                  class="block p-2" type="file" autofocus/>
                    <x-input-error :messages="$errors->get('signature')"/>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-2 mb-4">
                <div class="col-span-4">
                    <x-input-label :value="__('تعیین بخش دسترسی:')"/>
                    @foreach($permissions as $permission)
                        <div>
                            <input type="checkbox" name="permissions[{{$permission->name}}]"
                                   value="{{$permission->name}}" @checked(old('permissions.' . $permission->name))>
                            <label>{{ $permission->name }}</label>
                        </div>
                    @endforeach
                    <x-input-error :messages="$errors->get('permissions')"/>

                </div>
            </div>

            <div class="flex gap-4">
                <x-primary-button type="submit">
                    {{ __('ذخیره') }}
                </x-primary-button>
                <a href="{{route('users.index')}}">
                    <x-cancel-button >
                        {{__('لغو')}}
                    </x-cancel-button>
                </a>
            </div>
        </div>
    </form>

</x-app-layout>
