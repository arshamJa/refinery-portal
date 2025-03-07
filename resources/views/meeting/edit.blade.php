<x-app-layout>


    <x-breadcrumb>
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
                    <a href="{{route('meeting.create')}}"
                       class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                        <span>{{__('ایجاد جلسه جدید')}}</span>
                    </a>
                </li>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                     stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
                <li>
                    <a href="{{route('meeting.table')}}"
                       class="inline-flex items-center px-2 py-1.5 space-x-1.5 rounded-md hover:text-neutral-900 hover:bg-neutral-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
                        </svg>
                        <span>{{__('جدول جلسات')}}</span>
                    </a>
                </li>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                     stroke="currentColor" class="w-3 h-3 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
                <li>
                    <span class="inline-flex items-center px-2 py-1.5 font-normal rounded cursor-default active-breadcrumb focus:outline-none">
                       {{__('ویرایش جلسه : ')}}{{$meeting->title}}
                    </span>
                </li>
    </x-breadcrumb>


        <div class="bg-gray-100" dir="rtl">
                <form action="{{route('meeting.update',$meeting->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="p-4 mb-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-12">
                            <div class="sm:col-span-4">
                                <x-input-label for="title" :value="__('موضوع جلسه')"/>
                                <x-text-input name="title" id="title" value="{{$meeting->title}}"
                                              class="block my-2 w-full" type="text" autofocus/>
                                <x-input-error :messages="$errors->get('title')" class="my-2"/>
                            </div>

                            <div class="sm:col-span-4">
                                <x-input-label for="unit_organization" :value="__('انتخاب واحد سازمانی')"/>
                                <x-text-input name="unit_organization" id="unit_organization"
                                              value="{{$meeting->unit_organization}}"
                                              class="block my-2 w-full" type="text" autofocus/>
                                <x-input-error :messages="$errors->get('unit_organization')" class="my-2"/>
                            </div>

                            <div class="sm:col-span-4">
                                <x-input-label for="scriptorium" :value="__('نام دبیر جلسه')"/>
                                <x-text-input name="scriptorium" id="scriptorium"
                                              value="{{$meeting->scriptorium}}"
                                              class="block my-2 w-full" type="text" autofocus/>
                                <x-input-error :messages="$errors->get('scriptorium')" class="my-2"/>
                            </div>

                            <div class="sm:col-span-4">
                                <x-input-label for="location" :value="__('محل برگزاری جلسه')"/>
                                <x-text-input name="location" id="location"
                                              value="{{$meeting->location}}"
                                              class="block my-2 w-full" type="text" autofocus/>
                                <x-input-error :messages="$errors->get('location')" class="my-2"/>
                            </div>

                            <div class="sm:col-span-4">
                                <x-input-label for="date" :value="__('تاریخ جلسه')" class="mb-1.5"/>
                                <div class="flex items-center">
                                    <label for="month" class="block text-gray-700 text-sm">سال:</label>
                                    <select name="year" id="year" dir="ltr"
                                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">...</option>
                                        @for($i = 1400; $i <= 1440; $i++)
                                            <option value="{{$i}}" @if (old('year') == $i) selected @endif>
                                                {{$i}}
                                            </option>
                                        @endfor
                                    </select>
                                    @php
                                        $persian_months = ["فروردین", "اردیبهشت", "خرداد", "تیر", "مرداد", "شهریور","مهر", "آبان", "آذر", "دی", "بهمن", "اسفند"];
                                    @endphp
                                    <label for="month" class="block text-gray-700 text-sm">ماه:</label>
                                    <select name="month" id="month" dir="ltr"
                                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">...</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" @if (old('month') == $i) selected @endif>
                                                {{ $persian_months[$i - 1] }}
                                            </option>
                                        @endfor
                                    </select>
                                    <label for="day" class="block text-gray-700 text-sm">روز:</label>
                                    <select name="day" id="day" dir="ltr"
                                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">...</option>
                                        @for($i = 1; $i <= 31; $i++)
                                            <option value="{{$i}}" @if (old('day') == $i) selected @endif>
                                                {{$i}}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('year')" class="my-2"/>
                                <x-input-error :messages="$errors->get('month')" class="my-2"/>
                                <x-input-error :messages="$errors->get('day')" class="my-2"/>
                                {{--                    <x-text-input name="date" id="date"--}}
                                {{--                                  value="{{old('date')}}"--}}
                                {{--                                  class="block my-2 w-full" type="text"--}}
                                {{--                                  placeholder="{{__('0000/00/00')}}" autofocus/>--}}
                                {{--                    <x-input-error :messages="$errors->get('date')" class="my-2"/>--}}
                            </div>

                            <div class="sm:col-span-4">
                                <x-input-label for="time" :value="__('ساعت جلسه')"/>
                                <x-text-input name="time" id="time"
                                              value="{{$meeting->time}}"
                                              class="block my-2 w-full" type="text" autofocus/>
                                <x-input-error :messages="$errors->get('time')" class="my-2"/>
                            </div>

                            <div class="sm:col-span-4">
                                <x-input-label for="unit_held" :value="__('کمیته یا واحد برگزار کننده جلسه')"/>
                                <x-text-input name="unit_held" id="unit_held"
                                              value="{{$meeting->unit_held}}"
                                              class="block my-2 w-full" type="text" autofocus/>
                                <x-input-error :messages="$errors->get('unit_held')" class="my-2"/>
                            </div>
                            <div class="sm:col-span-8">
                                {{__('اعضای جلسه فعلی')}}:
                                <br>
                                <livewire:edit-meeting-user  :meeting="$meeting->id"/>
                            </div>

                            <div class="sm:col-span-8">
                                <x-input-label for="holders" class="mb-2"
                                               :value="__('انتخاب اعضای جدید')"/>
                                <div class="custom-select">
                                    <div class="select-box">
                                        <input type="text" class="tags_input" multiple name="holders" hidden>
                                        <div class="selected-options"></div>
                                        <div class="arrow">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5"
                                                 stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="options">
                                        <div class="option-search-tags">
                                            <input type="text" class="search-tags" placeholder="جست و جو ...">
                                            <button type="button" class="clear">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                     viewBox="0 0 24 24" stroke-width="1.5"
                                                     stroke="currentColor" class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M6 18 18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="option all-tags" data-value="All">{{__('انتخاب همه')}}</div>
                                        @foreach($users as $user)
                                            <div class="option"
                                                 data-value="{{$user->id}}">{{$user->full_name}}</div>
                                        @endforeach
                                        <div class="no-result-message" style="display:none;">No result match</div>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('holders')" class="my-2"/>
                            </div>

                            <div class="sm:col-span-4">
                                <x-input-label for="treat" :value="__('پذیرایی')"/>
                                <label for="yes">{{__('بلی')}}
                                    <input type="radio" name="treat" value="true">
                                </label>
                                <label for="no" class="mr-3">{{__('خیر')}}
                                    <input type="radio" name="treat" value="false">
                                </label>
                                <x-input-error :messages="$errors->get('treat')" class="my-2"/>
                            </div>

                            <div class="sm:col-span-4">
                                <div class="row my-2 py-2" x-data="handler()">
                                    <div class="col">
                                        <table class="table table-bordered align-items-center table-sm">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('نام و نام خانوادگی')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <template x-for="(field, index) in fields" :key="index">
                                                <tr>
                                                    <td x-text="index + 1"></td>
                                                    <td>
                                                        <x-text-input x-model="field.guest" type="text"
                                                                      name="guest[]"
                                                                      class="block my-2 w-full mr-1" autofocus/>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="mr-2"
                                                                @click="removeField(index)">&times;
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-right">
                                                    <button type="button" class="btn btn-info"
                                                            @click="addNewField()">{{__('افزودن مهمان')}}</button>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <script>
                                    function handler() {
                                        return {
                                            fields: [],
                                            addNewField() {
                                                this.fields.push({
                                                    guest: ''
                                                });
                                            },
                                            removeField(index) {
                                                this.fields.splice(index, 1);
                                            }
                                        }
                                    }
                                </script>

                                <x-input-error :messages="$errors->get('guest')" class="my-2"/>
                            </div>

                            <div class="sm:col-span-4">
                                <x-input-label for="applicant" :value="__('نام درخواست دهنده جلسه')"/>
                                <x-text-input name="applicant" value="{{$meeting->applicant}}"
                                              id="applicant" class="block my-2 w-full" type="text" autofocus/>
                                <x-input-error :messages="$errors->get('applicant')" class="my-2"/>

                            </div>
                            <div class="sm:col-span-4">
                                <x-input-label for="position_organization" :value="__('سمت سازمانی')"/>
                                <x-text-input name="position_organization" id="position_organization"
                                              value="{{$meeting->position_organization}}"
                                              class="block my-2 w-full" type="text" autofocus/>
                                <x-input-error :messages="$errors->get('position_organization')" class="my-2"/>
                            </div>
                            <div class="sm:col-span-4">
                                <x-input-label for="signature" :value="__('امضا')"/>
                                <x-text-input name="signature" id="signature" class="block my-2 p-2 w-full"
                                              type="file"
                                              autofocus/>
                                <x-input-error :messages="$errors->get('signature')" class="my-2"/>
                            </div>
                            <div class="sm:col-span-4">
                                <x-input-label for="reminder" :value="__('زمان جهت یادآوری')"/>
                                <x-text-input name="reminder" value="{{$meeting->reminder}}"
                                              id="reminder" class="block my-2 w-full" type="text"
                                              autofocus/>
                                <x-input-error :messages="$errors->get('reminder')" class="my-2"/>
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-primary-button type="submit">
                                {{ __('ذخیره') }}
                            </x-primary-button>
                            <a href="{{route('meeting.table')}}">
                                <x-secondary-button>
                                    {{__('لغو')}}
                                </x-secondary-button>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
</x-app-layout>

