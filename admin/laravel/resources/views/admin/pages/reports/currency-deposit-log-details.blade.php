@extends('admin.layout.master')

@section('title', trans('general.currency_deposit_log_details'))

@php
    $amlBotActive = resolve(\App\Services\Plugins\AmlBotPlugin::class)->isActivePlugin();
    $riskPercent = $amlBotActive ? data_get($log->validation_payload, 'risk_score_percent') : null;
    $threshold = $amlBotActive
        ? data_get(
            $log->validation_payload,
            'threshold',
            resolve(\App\Services\Plugins\AmlBotPlugin::class)->getRiskThresholdPercent()
        )
        : null;
    $suggestedUserId = old('user_id', $log->user_id ?: $matchedWallet?->user_id);
    $canCredit = $log->canAdminCredit();
    $canReject = $log->canAdminReject();
    $creditViaApprove = $log->canAdminApprove();
@endphp

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <nav class="flex mb-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                        <span>@lang('general.reports')</span>
                        <span class="mx-2">/</span>
                        <a href="{{ route('admin.reports.currency.deposit.log') }}" class="hover:text-primary-600">
                            @lang('general.currency_deposit_logs')
                        </a>
                        <span class="mx-2">/</span>
                        <span class="text-primary-600">#{{ $log->id }}</span>
                    </nav>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        @lang('general.currency_deposit_log_details')
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @lang('general.created_at'): {{ $log->getCreateAt() }}
                        ·
                        {{ toCurrency($log->amount) }} {{ $log->symbol }}
                        @if($log->network)
                            · {{ $log->network }}
                        @endif
                    </p>
                </div>
                <a href="{{ route('admin.reports.currency.deposit.log') }}"
                   class="inline-flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm transition-all dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto space-y-6">

            @if($amlBotActive)
                {{-- Status banner (address validation / AML) --}}
                <div class="rounded-2xl border px-5 py-4 {{ $log->getValidationStatusBannerClass() }}">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider opacity-70">@lang('general.deposit_current_status')</span>
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $log->getValidationStatusBadgeClass() }}">
                                    {{ $log->getValidationStatusLabel() }}
                                </span>
                                @if($log->is_used)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200">
                                        @lang('general.deposit_credited_badge')
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm leading-relaxed font-medium max-w-3xl">
                                {{ $log->getValidationStatusHint() }}
                            </p>
                            <p class="text-xs leading-relaxed opacity-80 max-w-3xl">
                                @lang('general.deposit_validation_help')
                            </p>
                        </div>
                        <div class="shrink-0 text-sm sm:text-end space-y-1">
                            <p><span class="opacity-70">@lang('general.deposit_validation_type'):</span> <span class="font-semibold">{{ $log->getValidationTypeLabel() }}</span></p>
                            @if($riskPercent !== null && $riskPercent !== '')
                                <p>
                                    <span class="opacity-70">@lang('general.deposit_risk_score'):</span>
                                    <span class="font-semibold">{{ $riskPercent }}%</span>
                                    <span class="opacity-60">({{ __('general.deposit_risk_threshold') }} {{ $threshold }}%)</span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif($log->is_used)
                <div class="rounded-2xl border px-5 py-4 bg-emerald-50 border-emerald-200 text-emerald-900 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-100">
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200">
                        @lang('general.deposit_credited_badge')
                    </span>
                </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 space-y-6">

                    {{-- Transaction --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">@lang('general.transaction_details')</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">@lang('general.deposit_tx_section_hint')</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2 rounded-xl bg-gray-50 dark:bg-gray-900/40 px-4 py-3 flex flex-wrap items-end justify-between gap-3">
                                <div>
                                    <p class="text-xs text-gray-400 font-semibold mb-1">@lang('general.amount')</p>
                                    <p class="text-2xl font-extrabold text-gray-900 dark:text-white">
                                        {{ toCurrency($log->amount) }}
                                        <span class="text-base font-semibold text-gray-500">{{ $log->symbol }}</span>
                                    </p>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1 sm:text-end">
                                    <p>@lang('general.network'): <span class="font-semibold">{{ $log->network ?: '—' }}</span></p>
                                    <p>@lang('general.supplier'): <span class="font-semibold">{{ $log->supplier ?: '—' }}</span></p>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-400 font-semibold mb-1">@lang('general.is_used')</p>
                                @if($log->is_used)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">@lang('general.yes')</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">@lang('general.no')</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-400 font-semibold mb-1">@lang('general.tag')</p>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 break-all">{{ $log->tag ?: '—' }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-xs uppercase tracking-wide text-gray-400 font-semibold mb-1">@lang('general.txt_id')</p>
                                <p class="text-sm font-mono break-all bg-gray-50 dark:bg-gray-900/40 rounded-xl px-3 py-2.5 text-gray-800 dark:text-gray-200">{{ $log->tx_id ?: '—' }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-xs uppercase tracking-wide text-gray-400 font-semibold mb-1">@lang('general.source_address')</p>
                                <p class="text-sm font-mono break-all bg-gray-50 dark:bg-gray-900/40 rounded-xl px-3 py-2.5 text-gray-800 dark:text-gray-200">{{ $log->from_address ?: '—' }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-xs uppercase tracking-wide text-gray-400 font-semibold mb-1">@lang('general.destiny_address')</p>
                                <p class="text-sm font-mono break-all bg-gray-50 dark:bg-gray-900/40 rounded-xl px-3 py-2.5 text-gray-800 dark:text-gray-200">{{ $log->address ?: '—' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($amlBotActive)
                        {{-- Validation / AML --}}
                        @include('admin.partial.address-validation-panel', [
                            'validationContext' => 'deposit',
                            'validationStatusLabel' => $log->getValidationStatusLabel(),
                            'validationStatusBadgeClass' => $log->getValidationStatusBadgeClass(),
                            'validationStatusBannerClass' => $log->getValidationStatusBannerClass(),
                            'validationStatusHint' => $log->getValidationStatusHint(),
                            'validationTypeLabel' => $log->getValidationTypeLabel(),
                            'validationTypeKey' => $log->validation_type ?: 'none',
                            'validationNote' => $log->validation_note,
                            'validationPayload' => $log->validation_payload,
                            'validatedAt' => $log->validated_at,
                            'amlCheck' => $log->amlCheck,
                            'checkedAddress' => $log->from_address,
                        ])
                    @endif
                </div>

                <div class="space-y-6">
                    {{-- User card --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white">@lang('general.user')</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            @if($log->user)
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-gray-400 font-semibold mb-1">@lang('general.assigned_user')</p>
                                    <a href="{{ route('admin.user.edit', $log->user) }}"
                                       class="text-sm font-semibold text-primary-600 hover:underline">
                                        {{ $log->user->getFullInfo() }}
                                    </a>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">@lang('general.deposit_no_assigned_user')</p>
                            @endif

                            @if($matchedWallet?->user)
                                <div class="pt-3 border-t border-gray-100 dark:border-gray-700">
                                    <p class="text-xs uppercase tracking-wide text-gray-400 font-semibold mb-1">@lang('general.deposit_matched_wallet')</p>
                                    <a href="{{ route('admin.user.edit', $matchedWallet->user) }}"
                                       class="text-sm font-semibold text-primary-600 hover:underline">
                                        {{ $matchedWallet->user->getFullInfo() }}
                                    </a>
                                    <p class="text-xs text-gray-400 mt-1">@lang('general.deposit_matched_wallet_hint')</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <div class="p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">@lang('general.deposit_admin_actions')</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">@lang('general.deposit_admin_actions_hint')</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-5">
                            @if($canCredit)
                                <div class="rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50/50 dark:bg-emerald-900/10 p-4 space-y-4">
                                    <div>
                                        <p class="text-sm font-bold text-emerald-800 dark:text-emerald-200">
                                            @lang('general.deposit_action_credit_title')
                                        </p>
                                        <p class="text-xs text-emerald-700/80 dark:text-emerald-300/80 mt-1 leading-relaxed">
                                            @lang('general.deposit_action_credit_desc')
                                        </p>
                                    </div>

                                    <form action="{{ $creditViaApprove
                                            ? route('admin.reports.currency.deposit.log.approve', $log)
                                            : route('admin.reports.currency.deposit.log.update') }}"
                                          method="POST"
                                          class="space-y-4"
                                          onsubmit="return confirm('@lang('general.deposit_credit_confirm')')">
                                        @csrf
                                        @unless($creditViaApprove)
                                            <input type="hidden" name="log_id" value="{{ $log->id }}">
                                        @endunless

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                @lang('general.user')
                                                @if($creditViaApprove)
                                                    <span class="text-gray-400 font-normal">(@lang('general.optional'))</span>
                                                @else
                                                    <span class="text-red-500">*</span>
                                                @endif
                                            </label>
                                            <select name="user_id"
                                                    @unless($creditViaApprove) required @endunless
                                                    class="select2 w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                <option value="">—</option>
                                                @foreach($users as $id => $name)
                                                    <option value="{{ $id }}" @selected((string) $suggestedUserId === (string) $id)>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p class="text-xs text-gray-500 mt-1.5">@lang('general.deposit_user_select_hint')</p>
                                            @error('user_id')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        @if($creditViaApprove)
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                    @lang('general.deposit_admin_note')
                                                </label>
                                                <textarea name="admin_note" rows="2"
                                                          class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                          placeholder="@lang('general.deposit_admin_note_placeholder')">{{ old('admin_note') }}</textarea>
                                                @error('admin_note')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @endif

                                        <button type="submit"
                                                class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 text-sm font-bold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm transition-all active:scale-[0.99]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            @lang('general.deposit_action_credit_button')
                                        </button>
                                    </form>
                                </div>
                            @endif

                            @if($canReject)
                                <div class="rounded-xl border border-red-200 dark:border-red-800 bg-red-50/40 dark:bg-red-900/10 p-4 space-y-4">
                                    <div>
                                        <p class="text-sm font-bold text-red-800 dark:text-red-200">
                                            @lang('general.deposit_action_reject_title')
                                        </p>
                                        <p class="text-xs text-red-700/80 dark:text-red-300/80 mt-1 leading-relaxed">
                                            @lang('general.deposit_action_reject_desc')
                                        </p>
                                    </div>

                                    <form action="{{ route('admin.reports.currency.deposit.log.reject', $log) }}"
                                          method="POST"
                                          class="space-y-4"
                                          onsubmit="return confirm('@lang('general.deposit_reject_confirm')')">
                                        @csrf
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                @lang('general.deposit_admin_note')
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <textarea name="admin_note" rows="3" required
                                                      class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                      placeholder="@lang('general.deposit_reject_note_placeholder')">{{ old('admin_note') }}</textarea>
                                            @error('admin_note')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <button type="submit"
                                                class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 text-sm font-bold rounded-xl bg-red-600 text-white hover:bg-red-700 shadow-sm transition-all active:scale-[0.99]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            @lang('general.deposit_validation_reject')
                                        </button>
                                    </form>
                                </div>
                            @endif

                            @unless($canCredit || $canReject)
                                <div class="rounded-xl bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700 p-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                        @lang('general.deposit_no_admin_actions')
                                    </p>
                                </div>
                            @endunless
                        </div>
                    </div>

                    {{-- Quick guide --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                        <p class="text-sm font-bold text-gray-800 dark:text-white mb-3">@lang('general.deposit_guide_title')</p>
                        <ul class="space-y-2 text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            @if($amlBotActive)
                                <li class="flex gap-2"><span class="text-emerald-500 font-bold">1.</span> @lang('general.deposit_guide_1')</li>
                                <li class="flex gap-2"><span class="text-emerald-500 font-bold">2.</span> @lang('general.deposit_guide_2')</li>
                                <li class="flex gap-2"><span class="text-red-500 font-bold">3.</span> @lang('general.deposit_guide_3')</li>
                            @else
                                <li class="flex gap-2"><span class="text-emerald-500 font-bold">1.</span> @lang('general.deposit_guide_2')</li>
                                <li class="flex gap-2"><span class="text-red-500 font-bold">2.</span> @lang('general.deposit_guide_3')</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        $('.select2').select2({
            width: '100%',
            dir: document.documentElement.dir || 'rtl'
        });
    </script>
@endsection
