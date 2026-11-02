@extends('admin.layout.master')

@section('title', trans('general.currencies_management'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900 space-y-6">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between pb-2">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.currencies_management')</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">@lang('general.manage_currencies')</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{route('admin.setting.currency.management')}}"
                   class="inline-flex items-center px-4 py-2.5 text-sm font-medium rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    @lang('master.back')
                </a>

                <button onclick="mboxQuestion('{{ route('admin.currency.inactive') }}')"
                        type="button"
                        class="inline-flex items-center px-4 py-2.5 text-sm font-medium rounded-xl border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 bg-red-50/50 dark:bg-red-950/20 hover:bg-red-100 dark:hover:bg-red-950/50 transition"
                        title="@lang('general.inactive_all_currency')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    @lang('general.inactive_all_currency')
                </button>

                <a href="{{route('admin.currency.create')}}"
                   class="inline-flex items-center px-4 py-2.5 text-sm font-medium rounded-xl bg-primary-600 text-white hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 shadow-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    @lang('general.create_currency')
                </a>
            </div>
        </div>

        @include('admin.pages.filter',['filters' => $filters,'back'=>route('admin.currency.index')])

        <form action="{{route('admin.currency.change.price')}}" method="POST" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700/70 p-6 space-y-4">
            @csrf
            <div class="flex flex-col gap-2 max-w-xl">
                <label for="price_supplier" class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                    @lang('general.price_receive')
                </label>
                <select
                    name="price_supplier"
                    id="price_supplier"
                    required
                    oninvalid="this.setCustomValidity('@lang('general.supplier_wallet_required')')"
                    oninput="setCustomValidity('')"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    @foreach($suppliers as $key=>$value)
                        <option @selected($supplier?->price_supplier == $key) value="{{$key}}">
                            {{$value}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end border-t border-gray-100 dark:border-gray-700/50 pt-4">
                <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 bg-primary-600 dark:bg-primary-500 text-white text-sm font-medium rounded-xl shadow-sm hover:bg-primary-700 dark:hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 transition">
                    @lang('general.save')
                </button>
            </div>
        </form>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700/70 overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 text-xs uppercase tracking-wider h-12">
                        <th class="px-6 py-3 font-semibold text-center">@lang('general.id')</th>
                        <th class="px-6 py-3 font-semibold text-center">@lang('general.rank')</th>
                        <th class="px-6 py-3 font-semibold text-center">@lang('general.icon')</th>
                        <th class="px-6 py-3 font-semibold">@lang('general.symbol')</th>
                        <th class="px-6 py-3 font-semibold">@lang('general.name')</th>
                        <th class="px-6 py-3 font-semibold">@lang('general.type')</th>
                        <th class="px-6 py-3 font-semibold text-center">@lang('general.status')</th>
                        <th class="px-6 py-3 font-semibold text-right">@lang('general.operation')</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-600 dark:text-gray-300">
                    @forelse($currencies as $currency)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-mono font-bold text-gray-400">
                                {{$currency?->id ?? '—'}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md">
                                        {{$currency?->rank ?? '—'}}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center">
                                    <img
                                        src="{{asset($currency->icon)}}"
                                        alt="{{$currency->symbol}}"
                                        loading="lazy"
                                        width="32"
                                        height="32"
                                        class="w-8 h-8 rounded-full object-contain bg-gray-50 dark:bg-gray-900 p-1 border border-gray-100 dark:border-gray-700 shadow-sm"
                                    />
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                {{$currency?->symbol ?? '—'}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{$currency?->name ?? '—'}}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 font-medium">
                                        {{ \App\Models\Currency::getType($currency->type) ?? '—'}}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                {!! $currency->getStatus() !!}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{route('admin.currency.edit',$currency)}}"
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    @lang('general.setting')
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-12 whitespace-nowrap text-center text-gray-400 dark:text-gray-500" colspan="8">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                                    </svg>
                                    <span class="text-sm font-medium">@lang('general.not_found')</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($currencies->hasPages())
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                    {{ $currencies->withQueryString()->links('pagination::tailwind') }}
                </div>
            @endif
        </div>

    </main>
@endsection
