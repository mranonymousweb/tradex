@extends('admin.layout.master')

@section('title', trans('setting.spot'))

@section('contents')

    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-background-dark">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                    @lang('setting.base_market') <span class="text-primary-600 text-lg">({{$currency->baseCurrency->symbol}})</span>
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{route('admin.market.currency')}}"
                   class="flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-light hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 shadow-sm transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 rtl:rotate-180 transition-transform group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-3 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-base font-bold text-gray-800 dark:text-white">@lang('general.add_new_market')</h3>
                </div>
                <form action="{{ route('admin.market.currency.update', $currency) }}" method="POST" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div class="lg:col-span-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">@lang('general.currencies') *</label>
                            <select name="currencies[]" multiple class="select2 w-full text-sm">
                                @foreach($currencies as $key => $value)
                                    <option value="{{ $key }}" @selected(collect(old('currencies'))->contains($key))>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        @foreach([ 'min_trade_amount_usdt', 'increase_percent', 'increase_value', 'decrease_percent', 'decrease_value'] as $field)
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">@lang("general.$field")</label>
                                <input type="text" name="{{ $field }}" value="{{ old($field) }}"
                                       class="separator w-full px-4 py-2 text-sm border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white font-mono"
                                       dir="ltr" placeholder="0.00">
                            </div>
                        @endforeach

                        <div class="flex gap-6 items-end pb-2">
                            @foreach(['purchasable', 'sellable'] as $switch)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 group-hover:text-primary-500 transition-colors">@lang("general.$switch")</span>
                                    <div class="relative inline-flex items-center">
                                        <input type="hidden" name="{{ $switch }}" value="0">
                                        <input type="checkbox" name="{{ $switch }}" value="1" class="sr-only peer" @checked(old($switch))>
                                        <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-green-500 transition-colors"></div>
                                        <div class="absolute start-0.5 top-0.5 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5 rtl:peer-checked:-translate-x-5"></div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end border-t border-gray-50 dark:border-gray-700 pt-4">
                        <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-lg transition-all shadow-md active:scale-95">@lang('general.save')</button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft border border-gray-100 dark:border-gray-700 p-6">
                <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">@lang('general.filters')</label>
                        <input type="text" name="main_currency_symbol" value="{{ request('main_currency_symbol') }}"
                               placeholder="(e.g. BTC)..."
                               class="w-full px-4 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 outline-none">
                    </div>
                    <div class="w-full md:w-48">
                        <select name="status" class="w-full px-4 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white outline-none">
                            <option value="">@lang('general.all') @lang('general.status')</option>
                            <option value="1" @selected(request('status') == '1')>@lang('general.active')</option>
                            <option value="2" @selected(request('status') === '2')>@lang('general.inactive')</option>
                        </select>
                    </div>
                    <div class="flex gap-2 w-full md:w-auto">
                        <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-lg transition-colors">
                            @lang('general.search')
                        </button>
                        <a href="{{ url()->current() }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 text-sm rounded-lg hover:bg-gray-200 transition-colors">@lang('general.clear_filter')</a>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-start border-collapse">
                        <thead class="bg-gray-50/80 dark:bg-gray-800/80 text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-start">@lang('general.market')</th>
                            <th scope="col" class="px-6 py-3 text-start">@lang('general.min_trade_amount')</th>
                            <th scope="col" class="px-6 py-3 text-center">@lang('general.purchasable')</th>
                            <th scope="col" class="px-6 py-3 text-center">@lang('general.sellable')</th>
                            <th scope="col" class="px-6 py-3 text-center">@lang('general.status')</th>
                            <th scope="col" class="px-6 py-3 text-center">@lang('general.operation')</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm">
                        @forelse( $marketCurrencies as $marketCurrency )
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors group">
                                <td class="px-6 py-3 whitespace-nowrap font-bold text-gray-900 dark:text-white">
                                    {{ $marketCurrency?->getPairs() }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <span class="font-mono text-gray-600 dark:text-gray-400 text-sm" dir="ltr">
                                        {{ toCurrency($marketCurrency->min_trade_amount_usdt) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-block w-2 h-2 rounded-full {{ $marketCurrency->purchasable ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.4)]' : 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.4)]' }}"></span>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-block w-2 h-2 rounded-full {{ $marketCurrency->sellable ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.4)]' : 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.4)]' }}"></span>
                                </td>
                                <td class="px-6 py-3 text-center text-xs">
                                    {!! $marketCurrency?->getStatus() !!}
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.market.currency.details', $marketCurrency) }}"
                                           class="px-2.5 py-1 bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 rounded-md text-xs font-bold hover:bg-blue-100 transition-all">
                                            @lang('general.details')
                                        </a>
                                        @if($marketCurrency->status == 1)
                                            <button onclick="mboxQuestion('{{ route('admin.market.currency.inactive', $marketCurrency) }}')"
                                                    class="px-2.5 py-1 bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 rounded-md text-xs font-bold hover:bg-red-100 transition-all">
                                                @lang('general.inactivated')
                                            </button>
                                        @else
                                            <button onclick="mboxQuestion('{{ route('admin.future.market.currency.active', $marketCurrency) }}')"
                                                    class="px-2.5 py-1 bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 rounded-md text-xs font-bold hover:bg-emerald-100 transition-all">
                                                @lang('general.activated')
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    <span class="text-xs font-medium">@lang('general.not_found')</span>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 text-xs">
                {{ $marketCurrencies->withQueryString()->links('pagination::tailwind') }}
            </div>
        </div>
    </main>
@endsection
