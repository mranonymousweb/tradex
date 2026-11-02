@extends('admin.layout.master')

@section('title', trans('general.authentication') .' '. trans('master.user'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">


        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.authentication') @lang('master.user')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.user.index')}}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            <div class="lg:col-span-3 space-y-6">
                {{-- button fast confirms --}}
                <div class="flex space-x-3">
                    <button data-package_id="2"
                            class="change_package px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded">@lang('general.approve_stage_1')</button>
                    <button data-package_id="3"
                            class="change_package px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded">@lang('general.approve_all')</button>
                    <button data-package_id="1"
                            class="change_package px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded">@lang('general.reject_all_authentication_step')</button>
                </div>


                {{-- base data identity --}}
                <section
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">@lang('general.user_basic_info')</h3>
                    <form action="{{ route('admin.user.update',$user) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 ">

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.name')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{old('name',$user->name)}}" required
                                       oninvalid="this.setCustomValidity('@lang('general.name_required')')"
                                       oninput="setCustomValidity('')"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <span
                                    class="{{ $user->isMAtchIdentity('name') ? 'text-green-500 font-semibold' : 'text-red-500 font-semibold' }}">
                                    {{ $user->isMAtchIdentity('name') ? trans('general.identity_match') : trans('general.identity_not_match') }}
                              </span>

                                @error('name')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.family')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="family" value="{{old('family',$user->family)}}" required
                                       oninvalid="this.setCustomValidity('@lang('general.family_required')')"
                                       oninput="setCustomValidity('')"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <span
                                    class="{{ $user->isMAtchIdentity('family') ? 'text-green-500 font-semibold' : 'text-red-500 font-semibold' }}">
                                    {{ $user->isMAtchIdentity('family') ? trans('general.identity_match') : trans('general.identity_not_match') }}
                              </span>
                                @error('family')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>


                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.national_code')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="national_code" required
                                       oninvalid="this.setCustomValidity('@lang('general.national_code_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{old('national_code',$user->option('national_code'))}}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <span
                                    class="{{ $user->isMAtchIdentity('national_code',true) ? 'text-green-500 font-semibold' : 'text-red-500 font-semibold' }}">
                                    {{ $user->isMAtchIdentity('national_code',true) ? trans('general.identity_match') : trans('general.identity_not_match') }}
                              </span>
                                @error('national_code')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.birthday')
                                    <span class="text-red-500">*</span></label>
                                <input @if(app()->currentLocale() == 'fa') data-jdp type="text" @else type="date"
                                       @endif  name="birthday"
                                       value="{{old('birthday',app()->currentLocale() == 'fa' ? get_created_at($user->option('birthday'),'Y/m/d') : $user->option('birthday'))}}"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.birthday_required')')"
                                       oninput="setCustomValidity('')"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <span
                                    class="{{ $user->isMAtchIdentity('birthday',true) ? 'text-green-500 font-semibold' : 'text-red-500 font-semibold' }}">
                                    {{ $user->isMAtchIdentity('birthday',true) ? trans('general.identity_match') : trans('general.identity_not_match') }}
                              </span>
                                @error('birthday')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>


                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.package')
                                    <span class="text-red-500">*</span></label>
                                <select id="package_id_input" name="package_id"
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @foreach($packages as $package)
                                        <option
                                            @selected(old('package_id',$user->package_id) == $package->id) value="{{ $package->id }}">{{ $package->name }}</option>
                                    @endforeach
                                </select>
                                @error('priority')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="pt-4 flex gap-4">
                            <button type="submit"
                                    class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                @lang('general.save')
                            </button>


                            <a href="{{route('admin.user.details.mobile.inquiry',$user->id)}}"
                               class="w-48 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-green-500 text-white hover:bg-green-600 shadow-sm dark:bg-blue-500 dark:hover:bg-blue-600">
                                @lang('general.mobile_inquiry')
                            </a>

                            <a href="{{route('admin.user.details.identity.inquiry',$user->id)}}"
                               class="w-48 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-green-500 text-white hover:bg-green-600 shadow-sm dark:bg-blue-500 dark:hover:bg-blue-600">
                                @lang('general.identity_inquiry')
                            </a>
                        </div>
                    </form>
                </section>

                {{-- identity files--}}
                <section
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">@lang('general.identity_documents')</h3>
                    <div class="space-y-4">
                        {{--  identity_card image --}}
                        <div class="flex items-center justify-between">
                            <span>@lang('general.identity_card')
                                @if($user->option(\App\Models\UserOption::ID_CARD_IMAGE_STATUS))
                                    <span class="text-xs text-gray-500">({{\App\Models\AuthenticationLog::getStatus($user->option(\App\Models\UserOption::ID_CARD_IMAGE_STATUS))}})</span>
                                @endif
                            </span>
                            <div class="space-x-2 inline">
                                @if($user->option('id_card_image') )
                                    @if(\Illuminate\Support\Facades\Storage::disk('public')->exists(str_replace('storage/','',$user->option('id_card_image'))))
                                        <a href="{{asset($user->option('id_card_image'))}}"
                                           download
                                           class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded">@lang('general.download')</a>
                                        <a href="{{route('admin.user.details.storage.delete',['path'=>$user->option('id_card_image')])}}"
                                           class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded">@lang('general.delete')</a>
                                    @elseif($user->option('id_card_image'))
                                        <p>
                                            @lang('general.file_deleted_from_storage')
                                        </p>
                                    @endif
                                    <a href="{{route('admin.user.details.document.status.update',['user'=>$user->id,'key'=>\App\Models\UserOption::ID_CARD_IMAGE_STATUS,'status'=>\App\Models\AuthenticationLog::STATUS_ACCEPTED])}}"
                                       class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded">@lang('general.accept')</a>
                                    <a href="{{route('admin.user.details.document.status.update',['user'=>$user->id,'key'=>\App\Models\UserOption::ID_CARD_IMAGE_STATUS,'status'=>\App\Models\AuthenticationLog::STATUS_REJECTED])}}"
                                       class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded">@lang('general.reject')</a>
                                @else
                                    <p>
                                        @lang('general.file_not_uploaded')
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- agreement_video --}}
                        <div class="flex items-center justify-between">
                            <span>@lang('general.agreement_video')
                                @if($user->option(\App\Models\UserOption::AGREEMENT_VIDEO_STATUS))
                                    <span class="text-xs text-gray-500">({{\App\Models\AuthenticationLog::getStatus($user->option(\App\Models\UserOption::AGREEMENT_VIDEO_STATUS))}})</span>
                                @endif
                            </span>
                            <div class="space-x-2 inline">
                                @if($user->option('agreement_video'))
                                    @if(\Illuminate\Support\Facades\Storage::disk('public')->exists(str_replace('storage/','',$user->option('agreement_video'))))
                                        <a href="{{asset($user->option('agreement_video'))}}"
                                           download
                                           class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded">@lang('general.download')</a>
                                        <a href="{{route('admin.user.details.storage.delete',['path'=>$user->option('agreement_video')])}}"
                                           class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded">@lang('general.delete')</a>
                                    @elseif($user->option('agreement_video'))
                                        <p>
                                            @lang('general.file_deleted_from_storage')
                                        </p>
                                    @endif
                                    <a href="{{route('admin.user.details.document.status.update',['user'=>$user->id,'key'=>\App\Models\UserOption::AGREEMENT_VIDEO_STATUS,'status'=>\App\Models\AuthenticationLog::STATUS_ACCEPTED])}}"
                                       class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded">@lang('general.accept')</a>
                                    <a href="{{route('admin.user.details.document.status.update',['user'=>$user->id,'key'=>\App\Models\UserOption::AGREEMENT_VIDEO_STATUS,'status'=>\App\Models\AuthenticationLog::STATUS_REJECTED])}}"
                                       class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded">@lang('general.reject')</a>
                                @else
                                    <p>
                                        @lang('general.file_not_uploaded')
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

                {{-- step 2 review guide --}}
                <section
                    class="bg-blue-50 dark:bg-blue-950/30 rounded-xl shadow-md p-6 border border-blue-100 dark:border-blue-900/50">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 mt-0.5">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="flex-1 space-y-4 text-sm leading-7 text-blue-900 dark:text-blue-100">
                            <h3 class="text-lg font-semibold text-blue-950 dark:text-blue-50">
                                @lang('general.admin_auth_step2_review_guide')
                            </h3>
                            <p>@lang('general.admin_auth_step2_review_intro')</p>
                            <ol class="list-decimal list-inside space-y-3 pr-1 marker:font-semibold marker:text-blue-700 dark:marker:text-blue-300">
                                <li>@lang('general.admin_auth_step2_review_step_both_accepted')</li>
                                <li>@lang('general.admin_auth_step2_review_step_both_rejected')</li>
                                <li>@lang('general.admin_auth_step2_review_step_partial')</li>
                            </ol>
                        </div>
                    </div>
                </section>


                {{--  user log --}}
                <section
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">@lang('general.authentication_logs')</h3>
                    <table class="w-full">
                        <thead class="">
                        <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                            <th class="table-header py-4 font-medium">@lang('general.id')</th>
                            <th class="table-header py-4 font-medium">@lang('general.step_level')</th>
                            <th class="table-header py-4 font-medium">@lang('general.status')</th>
                            <th class="table-header py-4 font-medium">@lang('general.created_at')</th>
                            <th class="table-header py-4 font-medium">@lang('general.operation')</th>

                        </tr>
                        </thead>
                        <tbody
                            class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                        @forelse($authenticationLogs as $log)
                            <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    <span>{{$log?->id ?? ''}}</span>
                                </td>
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    {{$log->step}}
                                </td>
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    {{\App\Models\AuthenticationLog::getStatus($log->status)}}
                                </td>
                                <td class="whitespace-nowrap max-xl:min-w-70" dir="ltr">
                                    {{to_jalali($log->created_at)}}
                                </td>
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    <div class="inline">
                                        @if($log->status == \App\Models\AuthenticationLog::STATUS_WAITING)
                                            <a href="{{route('admin.user.details.authentication.update',['user'=>$user->id,'status'=>\App\Models\AuthenticationLog::STATUS_ACCEPTED,'log_id'=>$log->id,'package_id'=>$log->step+1])}}"
                                               class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded">@lang('general.accept')</a>
                                            &nbsp;&nbsp;
                                            <button data-modal-target="reject_log_{{$log->id}}"
                                                    class="modal-trigger px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded">@lang('general.reject')</button>
                                        @else
                                            <span>
                                                <p> - </p>
                                            </span>
                                        @endif
                                    </div>
                                </td>

                            </tr>


                            @push('modals')
                                <div id="reject_log_{{$log->id}}" role="dialog" aria-modal="true"
                                     aria-labelledby="filterModalTitle"
                                     data-modal-id="reject_log_{{$log->id}}"
                                     class="modal hidden fixed inset-0 z-50 overflow-auto backdrop-blur bg-opacity-50">
                                    <div
                                        class="relative p-4 w-full max-w-xl m-auto flex items-center min-h-screen">
                                        <div
                                            class="relative dark:bg-background-dark border-gray-300 border bg-white rounded-lg shadow-lg w-full"
                                            itemscope itemtype="http://schema.org/Place">
                                            <!-- Header -->
                                            <div
                                                class="flex justify-between items-center p-4 dark:border-b-white border-b">
                                                <h3 id="filterModalTitle" class="text-xl  dark:text-white"
                                                    itemprop="name">@lang('general.reject_user_auth_log_with_id')
                                                    &nbsp; {{$log->id}}</h3>
                                                <button data-modal-close
                                                        class="text-gray-500 hover:text-gray-700 cursor-pointer text-3xl"
                                                        aria-label="بستن پنجره فیلتر">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24" stroke-width="1.5"
                                                         stroke="currentColor" class="size-6 dark:text-white">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M6 18 18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <!-- Content -->
                                            <div class="p-4">
                                                <form
                                                    action="{{ route('admin.user.details.authentication.update',$user->id) }}">
                                                    @csrf
                                                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                                                        {{-- --}}
                                                        <div>
                                                            <input type="hidden" name="package_id"
                                                                   value="{{$user->package_id}}">
                                                            <input type="hidden" name="log_id" value="{{$log->id}}">
                                                            <input type="hidden" name="status"
                                                                   value="{{\App\Models\AuthenticationLog::STATUS_REJECTED}}">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                                @lang('general.enter_reject_description')<span
                                                                    class="text-red-500">*</span>
                                                            </label>
                                                            <textarea required type="text" name="description"
                                                                      class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                      placeholder="@lang('general.enter_reject_description')"
                                                                      oninvalid="this.setCustomValidity('@lang('general.enter_reject_description_required')')"
                                                                      oninput="setCustomValidity('')">{{old('description',$log->description ?? null)}}</textarea>
                                                            @error('description')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        {{-- Submit --}}
                                                        <div class="pt-1 flex items-center gap-3">
                                                            <button type="submit"
                                                                    class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                                                @lang('general.save')
                                                            </button>

                                                            <button type="button" data-modal-close
                                                                    class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-red-600 text-white hover:bg-red-700 shadow-sm dark:bg-red-500 dark:hover:bg-red-600">
                                                                @lang('general.cancel_button')
                                                            </button>
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endpush

                        @empty
                            <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                                <td class="whitespace-nowrap" colspan="6">
                                    <span> @lang('general.not_found')</span>
                                </td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </section>

                {{-- KYC Provider Logs --}}
                @if($idAnalyzerPluginActive ?? false)
                    <section
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">@lang('general.kyc_provider_logs')</h3>
                        <table class="w-full">
                            <thead class="">
                            <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                                <th class="table-header py-4 font-medium">@lang('general.id')</th>
                                <th class="table-header py-4 font-medium">@lang('general.provider')</th>
                                <th class="table-header py-4 font-medium">@lang('general.source')</th>
                                <th class="table-header py-4 font-medium">@lang('general.reference')</th>
                                <th class="table-header py-4 font-medium">@lang('general.decision')</th>
                                <th class="table-header py-4 font-medium">@lang('general.status')</th>
                                <th class="table-header py-4 font-medium">@lang('general.processed_at')</th>
                                <th class="table-header py-4 font-medium">@lang('general.details')</th>
                            </tr>
                            </thead>
                            <tbody
                                class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                            @forelse($kycProviderLogs as $log)
                                <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16">
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        <span>{{$log?->id ?? ''}}</span>
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        {{$log?->provider ?? ''}}
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        {{\App\Models\KycProviderLog::getSourceLabel($log?->source)}}
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70" dir="ltr">
                                        {{$log?->reference ?? ''}}
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        {{$log?->decision ?? ''}}
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        {{\App\Models\KycProviderLog::getStatusLabel($log?->status)}}
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70" dir="ltr">
                                        {{to_jalali($log?->processed_at ?? $log?->created_at)}}
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        <button
                                            data-modal-target="kyc_provider_log_{{$log->id}}"
                                            class="modal-trigger px-3 py-1 bg-primary-600 hover:bg-primary-700 text-white rounded text-sm">
                                            @lang('general.see')
                                        </button>
                                    </td>
                                </tr>

                                @push('modals')
                                    @php
                                        $rawPayload = $log?->raw_payload ?? [];
                                        $outputFiles = is_array($rawPayload) ? ($rawPayload['outputFile'] ?? []) : [];
                                        $outputImages = is_array($rawPayload) ? ($rawPayload['outputImage'] ?? []) : [];
                                        $pdfFiles = [];
                                        if (is_array($outputFiles)) {
                                            foreach ($outputFiles as $f) {
                                                if (!is_array($f)) continue;
                                                if (!empty($f['fileUrl'])) {
                                                    $pdfFiles[] = $f;
                                                }
                                            }
                                        }
                                    @endphp
                                    <div id="kyc_provider_log_{{$log->id}}" role="dialog" aria-modal="true"
                                         aria-labelledby="kyc_provider_log_title_{{$log->id}}"
                                         data-modal-id="kyc_provider_log_{{$log->id}}"
                                         class="modal hidden fixed inset-0 z-50 overflow-auto backdrop-blur bg-opacity-50">
                                        <div
                                            class="relative p-4 w-full max-w-2xl m-auto flex items-center min-h-screen">
                                            <div
                                                class="relative dark:bg-background-dark border-gray-300 border bg-white rounded-lg shadow-lg w-full">
                                                <div
                                                    class="flex justify-between items-center p-4 dark:border-b-white border-b">
                                                    <h3 id="kyc_provider_log_title_{{$log->id}}"
                                                        class="text-xl dark:text-white">
                                                        @lang('general.details') - #{{$log->id}}
                                                    </h3>
                                                    <button data-modal-close
                                                            class="text-gray-500 hover:text-gray-700 cursor-pointer text-3xl"
                                                            aria-label="بستن پنجره جزئیات">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                             viewBox="0 0 24 24"
                                                             stroke-width="1.5" stroke="currentColor"
                                                             class="size-6 dark:text-white">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M6 18 18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <div class="p-4 space-y-4">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <div
                                                                class="text-sm text-gray-500">@lang('general.provider')</div>
                                                            <div class="font-semibold">{{$log?->provider ?? ''}}</div>
                                                        </div>
                                                        <div>
                                                            <div
                                                                class="text-sm text-gray-500">@lang('general.status')</div>
                                                            <div
                                                                class="font-semibold">{{\App\Models\KycProviderLog::getStatusLabel($log?->status)}}</div>
                                                        </div>
                                                        <div>
                                                            <div
                                                                class="text-sm text-gray-500">@lang('general.source')</div>
                                                            <div
                                                                class="font-semibold">{{\App\Models\KycProviderLog::getSourceLabel($log?->source)}}</div>
                                                        </div>
                                                        <div>
                                                            <div
                                                                class="text-sm text-gray-500">@lang('general.processed_at')</div>
                                                            <div class="font-semibold"
                                                                 dir="ltr">{{to_jalali($log?->processed_at ?? $log?->created_at)}}</div>
                                                        </div>
                                                    </div>

                                                    @php
                                                        $prettyPayload = json_encode($rawPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                                                        $previewChars = 2000;
                                                        $prettyPayloadPreview = mb_substr($prettyPayload, 0, $previewChars);
                                                        $hasMorePayload = mb_strlen($prettyPayload) > $previewChars;
                                                    @endphp

                                                    <div class="space-y-4">
                                                        @if(!empty($pdfFiles))
                                                            <div class="border rounded-lg p-3">
                                                                <div
                                                                    class="font-semibold mb-2 text-gray-800">@lang('general.output_files')</div>
                                                                <ul class="space-y-2">
                                                                    @foreach($pdfFiles as $f)
                                                                        @php
                                                                            $name = $f['name'] ?? ($f['fileName'] ?? 'file');
                                                                            $url = $f['fileUrl'] ?? null;
                                                                        @endphp
                                                                        <li class="flex items-center justify-between gap-3">
                                                                            <span
                                                                                class="text-sm text-gray-700 break-all">{{$name}}</span>
                                                                            @if($url)
                                                                                <a href="{{ $url }}" target="_blank"
                                                                                   rel="noopener noreferrer"
                                                                                   class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
                                                                                    PDF
                                                                                </a>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif

                                                        <div class="border rounded-lg p-3">
                                                            <div class="flex items-center justify-between gap-3 mb-2">
                                                                <div
                                                                    class="font-semibold text-gray-800">@lang('general.raw_payload')</div>
                                                                <div class="text-xs text-gray-500" dir="ltr">
                                                                    {{ number_format(mb_strlen($prettyPayload)) }} chars
                                                                </div>
                                                            </div>

                                                            <pre
                                                                class="max-h-56 overflow-auto text-xs leading-5 bg-gray-50 p-3 rounded-lg border">
{{ $prettyPayloadPreview }}{{ $hasMorePayload ? "\n...\n" : '' }}
                                                        </pre>

                                                            <details class="mt-3">
                                                                <summary
                                                                    class="cursor-pointer text-primary-600 dark:text-primary-300 font-medium">
                                                                    @lang('general.see') (Full)
                                                                </summary>
                                                                <pre
                                                                    class="max-h-96 overflow-auto text-xs leading-5 bg-gray-50 p-3 rounded-lg border mt-2">
{{ $prettyPayload }}
                                                            </pre>
                                                            </details>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endpush
                            @empty
                                <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                                    <td class="whitespace-nowrap" colspan="8">
                                        <span>@lang('general.not_found')</span>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </section>
                @endif

                {{-- service logs --}}
                <section
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">@lang('general.authentication_requests')</h3>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <table class="w-full">
                            <thead class="">
                            <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                                <th class="table-header py-4 font-medium">@lang('general.id')</th>
                                <th class="table-header py-4 font-medium">@lang('general.identity_match_for')</th>
                                <th class="table-header py-4 font-medium">@lang('general.user_enter_value')</th>
                                <th class="table-header py-4 font-medium">@lang('general.service_value')</th>
                                <th class="table-header py-4 font-medium">@lang('general.request_date')</th>
                                <th class="table-header py-4 font-medium">@lang('general.description')</th>

                            </tr>
                            </thead>
                            <tbody
                                class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                            @forelse($authenticationRequests as $req)
                                <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        <span>{{$req?->id ?? ''}}</span>
                                    </td>

                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        {{ \App\Models\AuthenticationRequest::getMatch($req->key) }}
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        {{$req->user_value}}
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        {{$req->service_value}}
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        {{to_jalali($req->created_at)}}
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        {{trans($req->description)}}
                                    </td>

                                </tr>
                            @empty
                                <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                                    <td class="whitespace-nowrap" colspan="6">
                                        <span> @lang('general.not_found')</span>
                                    </td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </ul>
                </section>


            </div>

            {{-- side bar --}}

            @include('admin.pages.admin.side')

        </div>


    </main>
@endsection
@section('scripts')
    <script>
        let user = @js($user);

        document.querySelectorAll('.change_package').forEach(button => {
            button.addEventListener('click', async (event) => {
                const button = event.currentTarget;
                const package_id = Number(button.dataset.package_id);
                if (![1, 2, 3].includes(package_id)) {
                    mboxError("@lang('general.package_is_not_valid')");
                    return;
                }

                if (package_id == user.package_id) {
                    mboxError("@lang('general.package_is_done_before')");
                    return;
                }
                const data = {package_id};
                await connectServer(data);
            });
        });

        async function connectServer(data) {
            const url = "{{route('admin.user.details.package.update', $user->id)}}";
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    mboxError();
                    return;
                }

                const result = await response.json();
                const package_id = result.response.package_id;
                const package_input = document.getElementById('package_id_input');
                package_input.value = package_id;
                user = result.data
                mboxSuccess();

            } catch (error) {
                console.error('Fetch error:', error.message);
            }
        }
    </script>

@endsection
