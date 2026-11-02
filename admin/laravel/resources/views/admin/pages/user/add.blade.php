@extends('admin.layout.master')

@section('title', trans('master.add_user'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <!-- Header Page -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    @lang('master.add_user')
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @lang('general.create_user_account_profile_and_assign_system_access')
                </p>
            </div>
            <div>
                <a href="{{ route('admin.user.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold rounded-xl bg-white text-gray-700 hover:text-gray-900 hover:bg-gray-100 border border-gray-200 shadow-sm transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 rtl:ml-2 rtl:mr-0 transform rtl:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <!-- Form Wrapper -->
        <div class="max-w-full mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                <form action="{{ route('admin.user.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <!-- Form Body -->
                    <div class="p-6 md:p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.name') <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                       class="w-full px-4 py-2.5 border rounded-xl text-sm transition-all duration-200 bg-gray-50 dark:bg-gray-700/50 @error('name') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-gray-300 dark:border-gray-600 focus:ring-blue-500/20 focus:border-blue-500 @enderror dark:text-white placeholder-gray-400 focus:outline-none focus:ring-4"
                                       placeholder="@lang('general.name')">
                                @error('name')
                                <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Family -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.family') <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="family" value="{{ old('family') }}"
                                       class="w-full px-4 py-2.5 border rounded-xl text-sm transition-all duration-200 bg-gray-50 dark:bg-gray-700/50 @error('family') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-gray-300 dark:border-gray-600 focus:ring-blue-500/20 focus:border-blue-500 @enderror dark:text-white placeholder-gray-400 focus:outline-none focus:ring-4"
                                       placeholder="@lang('general.family')">
                                @error('family')
                                <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.email') <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="w-full px-4 py-2.5 border rounded-xl text-sm transition-all duration-200 bg-gray-50 dark:bg-gray-700/50 @error('email') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-gray-300 dark:border-gray-600 focus:ring-blue-500/20 focus:border-blue-500 @enderror dark:text-white placeholder-gray-400 focus:outline-none focus:ring-4"
                                       placeholder="example@domain.com">
                                @error('email')
                                <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mobile -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.mobile') <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="mobile" value="{{ old('mobile') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700/50 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 dark:text-white placeholder-gray-400 transition-all duration-200"
                                       placeholder="@lang('general.mobile')">
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.password') <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password"
                                       class="w-full px-4 py-2.5 border rounded-xl text-sm transition-all duration-200 bg-gray-50 dark:bg-gray-700/50 @error('password') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-gray-300 dark:border-gray-600 focus:ring-blue-500/20 focus:border-blue-500 @enderror dark:text-white placeholder-gray-400 focus:outline-none focus:ring-4"
                                       placeholder="••••••••">
                                @error('password')
                                <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.confirm_password') <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation"
                                       class="w-full px-4 py-2.5 border rounded-xl text-sm transition-all duration-200 bg-gray-50 dark:bg-gray-700/50 @error('password_confirmation') border-red-500 focus:ring-red-500/20 focus:border-red-500 @else border-gray-300 dark:border-gray-600 focus:ring-blue-500/20 focus:border-blue-500 @enderror dark:text-white placeholder-gray-400 focus:outline-none focus:ring-4"
                                       placeholder="••••••••">
                                @error('password_confirmation')
                                <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Country -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.country') <span class="text-red-500">*</span>
                                </label>
                                <select name="country_id" required
                                        oninvalid="this.setCustomValidity('@lang('general.country_required')')"
                                        oninput="setCustomValidity('')"
                                        class="select2 w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl text-sm bg-gray-50 dark:bg-gray-700/50 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 dark:text-white transition-all duration-200">
                                    <option value="" disabled {{ old('country_id') ? '' : 'selected' }}>Select a country</option>
                                    @foreach($countries as $key => $value)
                                        <option @selected(old('country_id', $user->country_id ?? null) == $key) value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Actions Footer Card -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
                        <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 rounded-xl shadow-sm transition-all duration-150 focus:outline-none focus:ring-4 focus:ring-blue-500/30">
                            @lang('general.save')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
