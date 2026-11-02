{{--
  Shared address-validation panel for admin.
  Expected vars:
    $validationContext: 'withdraw'|'deposit'
    $validationStatusLabel, $validationStatusBadgeClass, $validationStatusBannerClass, $validationStatusHint
    $validationTypeLabel, $validationNote, $validationPayload, $validatedAt
    $amlCheck (optional)
    $checkedAddress (optional string)
--}}
@php
    $amlBotActive = resolve(\App\Services\Plugins\AmlBotPlugin::class)->isActivePlugin();
@endphp
@if($amlBotActive)
@php
    $riskPercent = data_get($validationPayload ?? [], 'risk_score_percent');
    $threshold = data_get(
        $validationPayload ?? [],
        'threshold',
        resolve(\App\Services\Plugins\AmlBotPlugin::class)->getRiskThresholdPercent()
    );
    $listId = data_get($validationPayload ?? [], 'list_id');
    $listDescription = data_get($validationPayload ?? [], 'list_description');
    $hasBlacklistFlag = data_get($validationPayload ?? [], 'has_blacklist_flag');
    $amlStatus = data_get($validationPayload ?? [], 'aml_status');
    $amlAsset = data_get($validationPayload ?? [], 'aml_asset');
    $amlUid = data_get($validationPayload ?? [], 'aml_uid');
    $checkedAddress = $checkedAddress
        ?? data_get($validationPayload ?? [], 'address')
        ?? data_get($validationPayload ?? [], 'from_address');

    $riskMeaning = null;
    if ($riskPercent !== null && $riskPercent !== '') {
        if ((float) $riskPercent < (float) $threshold) {
            $riskMeaning = __('general.validation_risk_meaning_low', [
                'score' => $riskPercent,
                'threshold' => $threshold,
            ]);
        } else {
            $riskMeaning = __('general.validation_risk_meaning_high', [
                'score' => $riskPercent,
                'threshold' => $threshold,
            ]);
        }
    }

    $isWithdraw = ($validationContext ?? '') === 'withdraw';
@endphp

<div class="rounded-2xl border overflow-hidden {{ $validationStatusBannerClass }}">
    <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <h3 class="text-base font-extrabold">
                {{ $isWithdraw ? __('general.withdraw_validation_section') : __('general.deposit_validation_section') }}
            </h3>
            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $validationStatusBadgeClass }}">
                {{ $validationStatusLabel }}
            </span>
        </div>
        <p class="text-sm leading-relaxed font-medium">
            {{ $validationStatusHint }}
        </p>
        <p class="mt-2 text-xs leading-relaxed opacity-80">
            {{ $isWithdraw ? __('general.withdraw_validation_help') : __('general.deposit_validation_help') }}
        </p>
    </div>

    <div class="px-5 py-4 bg-white/50 dark:bg-black/10 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <div class="rounded-xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-gray-900/30 p-3">
                <p class="text-[11px] uppercase tracking-wide opacity-60 font-bold mb-1">@lang('general.deposit_validation_type')</p>
                <p class="text-sm font-bold">{{ $validationTypeLabel }}</p>
                <p class="text-xs opacity-70 mt-1">{{ __('general.validation_type_help_' . ($validationTypeKey ?? 'none')) }}</p>
            </div>

            <div class="rounded-xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-gray-900/30 p-3">
                <p class="text-[11px] uppercase tracking-wide opacity-60 font-bold mb-1">@lang('general.deposit_risk_score')</p>
                @if($riskPercent !== null && $riskPercent !== '')
                    <p class="text-sm font-bold">{{ $riskPercent }}%</p>
                    <p class="text-xs opacity-70 mt-1">@lang('general.deposit_risk_threshold'): {{ $threshold }}%</p>
                @else
                    <p class="text-sm font-bold">—</p>
                    <p class="text-xs opacity-70 mt-1">@lang('general.validation_risk_not_available')</p>
                @endif
            </div>

            <div class="rounded-xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-gray-900/30 p-3">
                <p class="text-[11px] uppercase tracking-wide opacity-60 font-bold mb-1">@lang('general.deposit_validated_at')</p>
                <p class="text-sm font-bold">
                    {{ $validatedAt ? \Illuminate\Support\Carbon::parse($validatedAt)->format('Y/m/d - H:i:s') : '—' }}
                </p>
                @if($amlUid)
                    <p class="text-xs opacity-70 mt-1 font-mono break-all">UID: {{ $amlUid }}</p>
                @endif
            </div>
        </div>

        @if($riskMeaning)
            <div class="rounded-xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-gray-900/30 px-4 py-3">
                <p class="text-xs font-bold opacity-70 mb-1">@lang('general.validation_risk_meaning_title')</p>
                <p class="text-sm leading-relaxed">{{ $riskMeaning }}</p>
            </div>
        @endif

        @if($checkedAddress)
            <div class="rounded-xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-gray-900/30 px-4 py-3">
                <p class="text-xs font-bold opacity-70 mb-1">
                    {{ $isWithdraw ? __('general.validation_checked_destination') : __('general.validation_checked_source') }}
                </p>
                <p class="text-sm font-mono break-all">{{ $checkedAddress }}</p>
            </div>
        @endif

        @if($listId || $listDescription)
            <div class="rounded-xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-gray-900/30 px-4 py-3">
                <p class="text-xs font-bold opacity-70 mb-1">@lang('general.validation_list_match_title')</p>
                <p class="text-sm leading-relaxed">
                    @lang('general.validation_list_match_id'): <span class="font-semibold">#{{ $listId ?: '—' }}</span>
                </p>
                @if($listDescription)
                    <p class="text-sm mt-1 leading-relaxed">{{ $listDescription }}</p>
                @endif
            </div>
        @endif

        @if($validationNote)
            <div class="rounded-xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-gray-900/30 px-4 py-3">
                <p class="text-xs font-bold opacity-70 mb-1">@lang('general.deposit_validation_note')</p>
                <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $validationNote }}</p>
            </div>
        @endif

        @if($amlCheck || $amlStatus || $amlAsset || $hasBlacklistFlag !== null)
            <div class="rounded-xl border border-sky-100 dark:border-sky-900/40 bg-sky-50/70 dark:bg-sky-900/10 px-4 py-3 space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <p class="text-sm font-bold">@lang('general.deposit_aml_info')</p>
                    @if($amlCheck?->investigation_link)
                        <a href="{{ $amlCheck->investigation_link }}" target="_blank" rel="noopener"
                           class="text-xs font-semibold text-primary-600 hover:underline">
                            @lang('general.deposit_aml_investigation_link')
                        </a>
                    @endif
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-sm">
                    <div>
                        <p class="text-xs opacity-60 mb-1">@lang('general.status')</p>
                        <p class="font-semibold">{{ $amlCheck?->status ?: ($amlStatus ?: '—') }}</p>
                    </div>
                    <div>
                        <p class="text-xs opacity-60 mb-1">@lang('general.deposit_risk_score')</p>
                        <p class="font-semibold">
                            {{ $amlCheck?->risk_score !== null ? $amlCheck->risk_score : '—' }}
                            @if($amlCheck?->risk_score_level)
                                <span class="opacity-60 font-normal">({{ $amlCheck->risk_score_level }})</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs opacity-60 mb-1">@lang('general.asset')</p>
                        <p class="font-semibold">{{ $amlCheck?->asset ?: ($amlAsset ?: '—') }}</p>
                    </div>
                    <div>
                        <p class="text-xs opacity-60 mb-1">@lang('general.validation_blacklist_flag')</p>
                        <p class="font-semibold">
                            @if(($amlCheck?->has_blacklist_flag ?? $hasBlacklistFlag) === true)
                                @lang('general.yes')
                            @elseif(($amlCheck?->has_blacklist_flag ?? $hasBlacklistFlag) === false)
                                @lang('general.no')
                            @else
                                —
                            @endif
                        </p>
                    </div>
                </div>

                @if(!empty($amlCheck?->signal_tags))
                    <div>
                        <p class="text-xs opacity-60 mb-2">@lang('general.deposit_aml_signals')</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach((array) $amlCheck->signal_tags as $tag)
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-white/80 text-amber-800 border border-amber-100 dark:bg-amber-900/30 dark:text-amber-200 dark:border-amber-800">
                                    {{ is_array($tag) ? json_encode($tag) : $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="rounded-xl border border-black/5 dark:border-white/10 bg-white/70 dark:bg-gray-900/30 px-4 py-3">
            <p class="text-xs font-bold opacity-70 mb-2">@lang('general.validation_admin_next_steps_title')</p>
            <ul class="space-y-1.5 text-sm leading-relaxed list-disc list-inside opacity-90">
                @if($isWithdraw)
                    <li>@lang('general.withdraw_validation_step_1')</li>
                    <li>@lang('general.withdraw_validation_step_2')</li>
                    <li>@lang('general.withdraw_validation_step_3')</li>
                @else
                    <li>@lang('general.deposit_validation_step_1')</li>
                    <li>@lang('general.deposit_validation_step_2')</li>
                    <li>@lang('general.deposit_validation_step_3')</li>
                @endif
            </ul>
        </div>

        @if(!empty($validationPayload))
            <details class="group rounded-xl border border-black/5 dark:border-white/10 overflow-hidden bg-white/40 dark:bg-black/10">
                <summary class="cursor-pointer list-none px-4 py-3 text-sm font-semibold flex items-center justify-between">
                    <span>@lang('general.deposit_validation_payload')</span>
                    <span class="text-xs opacity-60 group-open:hidden">@lang('general.see')</span>
                </summary>
                <pre class="text-xs leading-relaxed overflow-x-auto max-h-64 bg-slate-900 text-slate-100 p-4 m-0">{{ json_encode($validationPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
            </details>
        @endif
    </div>
</div>
@endif
