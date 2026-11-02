@extends('admin.layout.master')

@section('title', trans('general.edit') .' '. trans('master.user'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">

        <!-- Header Section -->
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                    @lang('general.edit') @lang('master.user')
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">@lang('general.update_user_details_and_permissions')</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{route('admin.user.index')}}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 shadow-sm transition-all dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Form Area -->
            <div class="lg:col-span-3">
                <form action="{{ route('admin.user.update',$user) }}" method="post" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                        <!-- Form Header -->
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                            <h3 class="font-semibold text-gray-800 dark:text-white">@lang('general.personal_information')</h3>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                            <!-- Name Field -->
                            <div class="space-y-2">
                                <label class="flex justify-between text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span>@lang('general.name') <span class="text-red-500">*</span></span>
                                    @php $matchName = $user->isMAtchIdentity('name'); @endphp
                                    <span class="text-[10px] uppercase px-2 py-2 rounded-full {{ $matchName ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ $matchName ? trans('general.identity_match') : trans('general.identity_not_match') }}
                                </span>
                                </label>
                                <input type="text" name="name" value="{{old('name',$user->name)}}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all @error('name') border-red-500 @enderror">
                                @error('name') <p class="text-xs text-red-500 mt-1">{{$message}}</p> @enderror
                            </div>

                            <!-- Family Field -->
                            <div class="space-y-2">
                                <label class="flex justify-between text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <span>@lang('general.family') <span class="text-red-500">*</span></span>
                                    @php $matchFamily = $user->isMAtchIdentity('family'); @endphp
                                    <span class="text-[10px] uppercase px-2 py-2 rounded-full {{ $matchFamily ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ $matchFamily ? trans('general.identity_match') : trans('general.identity_not_match') }}
                                </span>
                                </label>
                                <input type="text" name="family" value="{{old('family',$user->family)}}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all @error('family') border-red-500 @enderror">
                                @error('family') <p class="text-xs text-red-500 mt-1">{{$message}}</p> @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">@lang('general.email') <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{old('email',$user->email)}}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all">
                                @error('email') <p class="text-xs text-red-500 mt-1">{{$message}}</p> @enderror
                            </div>

                            <!-- Mobile Field -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">@lang('general.mobile') <span class="text-red-500">*</span></label>
                                <input type="text" name="mobile" value="{{old('mobile',$user->mobile)}}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all">
                                @error('mobile') <p class="text-xs text-red-500 mt-1">{{$message}}</p> @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">@lang('general.password')</label>
                                <input type="password" name="password" placeholder="••••••••"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all">
                                <p class="text-[11px] text-gray-400">@lang('general.leave_blank_to_keep_current')</p>
                            </div>

                            <!-- Confirm Password -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">@lang('general.confirm_password')</label>
                                <input type="password" name="password_confirmation" placeholder="••••••••"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all">
                            </div>

                            <!-- Country Selection -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">@lang('general.country') <span class="text-red-500">*</span></label>
                                <select name="country_id" class="select2 w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                                    @foreach($countries as $key=>$value)
                                        <option @selected(old('country_id',$user->country_id ?? null) == $key) value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status Selection -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">@lang('general.status') <span class="text-red-500">*</span></label>
                                <select name="status"
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all @error('status') border-red-500 @enderror">
                                    @foreach(\App\Models\User::receiveStatus() as $key => $value)
                                        <option @selected(old('status', $user->status) == $key) value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('status') <p class="text-xs text-red-500 mt-1">{{$message}}</p> @enderror
                            </div>
                        </div>

                        <!-- Form Footer / Actions -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end gap-3">
                            @can('admin.user.update')
                                <button type="submit"
                                        class="px-6 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-lg shadow-md hover:shadow-lg transition-all transform active:scale-95">
                                    @lang('general.save_changes')
                                </button>
                            @endcan
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <aside class="lg:col-span-1">
                @include('admin.pages.admin.side')
            </aside>

        </div>
    </main>
@endsection
