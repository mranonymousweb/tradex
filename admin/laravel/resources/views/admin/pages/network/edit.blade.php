@extends('admin.layout.master')

@section('title', trans('general.edit_network'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900 space-y-6" >

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200 dark:border-gray-800">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.edit_network')</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">@lang('general.update_network_settings_description')</p>
            </div>
            <div>
                <a href="{{route('admin.network.index')}}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium rounded-xl bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 transition duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 111.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">@lang('general.network_settings')</h3>
            </div>

            <form action="{{ route('admin.network.update', $network) }}" method="post" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"> @lang('general.symbol')<span class="text-red-500">*</span></label>
                        <input type="text" name="symbol" value="{{old('symbol', $network->symbol)}}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition" placeholder="@lang('general.symbol')">
                        @error('symbol')
                        <span class="text-sm text-red-500 mt-1 block">{{$message}}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.icon')</label>
                        <input type="file" name="icon" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 dark:file:bg-gray-600 dark:file:text-gray-200 transition">
                        @error('icon')
                        <span class="text-sm text-red-500 mt-1 block">{{$message}}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.status')<span class="text-red-500">*</span></label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            <option value="" @selected(old('status') === null)>{{ __('general.select') }} ({{ __('general.status') }})</option>
                            @foreach($statuses as $key => $status)
                                <option value="{{ $key }}" @selected(old('status', $network->status) == $key)>{!! $status !!}</option>
                            @endforeach
                        </select>
                        @error('status')
                        <span class="text-sm text-red-500 mt-1 block">{{$message}}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.is_withdraw_able')<span class="text-red-500">*</span></label>
                        <select name="is_withdraw_able" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            <option value="" @selected(old('is_withdraw_able') === null)>{{ __('general.select') }}</option>
                            @foreach($operations as $key => $withdraw)
                                <option value="{{ $key }}" @selected(old('is_withdraw_able', $network->is_withdraw_able) == $key)>{!! $withdraw !!}</option>
                            @endforeach
                        </select>
                        @error('is_withdraw_able')
                        <span class="text-sm text-red-500 mt-1 block">{{$message}}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.is_deposit_able')<span class="text-red-500">*</span></label>
                        <select name="is_deposit_able" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            <option value="" @selected(old('is_deposit_able') === null)>{{ __('general.select') }}</option>
                            @foreach($operations as $key => $deposit)
                                <option value="{{ $key }}" @selected(old('is_deposit_able', $network->is_deposit_able) == $key)>{!! $deposit !!}</option>
                            @endforeach
                        </select>
                        @error('is_deposit_able')
                        <span class="text-sm text-red-500 mt-1 block">{{$message}}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.has_tag')<span class="text-red-500">*</span></label>
                        <select name="has_tag" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                            <option value="" @selected(old('has_tag') === null)>{{ __('general.select') }}</option>
                            @foreach($operations as $key => $deposit)
                                <option value="{{ $key }}" @selected(old('has_tag', $network->has_tag) == $key)>{!! $deposit !!}</option>
                            @endforeach
                        </select>
                        @error('has_tag')
                        <span class="text-sm text-red-500 mt-1 block">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium rounded-xl bg-primary-600 text-white hover:bg-primary-700 shadow-sm focus:ring-4 focus:ring-primary-200 dark:bg-primary-500 dark:hover:bg-primary-600 dark:focus:ring-primary-800 transition duration-150">
                        @lang('general.save')
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">@lang('general.network_providers')</h3>
                    <button type="button" data-modal-target="change-provider-modal" class="modal-trigger inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium rounded-lg bg-primary-50 text-primary-700 hover:bg-primary-100 dark:bg-primary-900/40 dark:text-primary-400 dark:hover:bg-primary-900/60 transition">
                       @lang('general.change_provider')
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                            <th class="px-6 py-3 text-start">@lang('general.symbol')</th>
                            <th class="px-6 py-3 text-start">@lang('general.type')</th>
                            <th class="px-6 py-3 text-start">@lang('general.supplier')</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-300">
                        @forelse($network->providers as $networkItem)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-6 py-3.5 font-medium text-gray-900 dark:text-white text-start">{{ $networkItem->symbol }}</td>
                                <td class="px-6 py-3.5 text-start"><span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 rounded-md font-mono">{{ $networkItem->getType() }}</span></td>
                                <td class="px-6 py-3.5 text-gray-500 dark:text-gray-400 text-start">{{ $networkItem->supplier->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">@lang('general.not_found')</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">@lang('general.supplier_networks')</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                            <th class="px-6 py-3 text-start">@lang('general.id')</th>
                            <th class="px-6 py-3 text-start">@lang('general.supplier')</th>
                            <th class="px-6 py-3 text-start">@lang('general.symbol')</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-300">
                        @forelse($network->suppliers as $supplierItem)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition text-start">
                                <td class="px-6 py-3.5 font-mono text-gray-500 dark:text-gray-400 text-xs text-start">{{ $supplierItem->id }}</td>
                                <td class="px-6 py-3.5 font-medium text-gray-900 dark:text-white text-start">{{ $supplierItem->supplier->name ?? 'N/A' }}</td>
                                <td class="px-6 py-3.5 text-start"><span class="px-2 py-0.5 text-xs bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-md font-medium">{{ $supplierItem->symbol }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">@lang('general.not_found')</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



    </main>
@endsection
@section('modal')


    <!-- Change Provider Modal  -->
    <div class="modal fixed inset-0 z-50 flex items-center justify-center bg-gray-200/40 dark:bg-gray-900/40 backdrop-blur-md hidden" data-modal-id="change-provider-modal">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">@lang('general.config_provider')</h3>
            <form action="{{route('admin.network.provider.update',$network)}}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">@lang('general.type')<span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">@lang('general.select')</option>
                        @if(isset($types))
                            @foreach($types as $key => $type)
                                <option value="{{ $key }}">{{ $type }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">@lang('general.supplier_engine')<span class="text-red-500">*</span></label>
                    <select name="supplier_id" required class="w-full px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">@lang('general.select')</option>
                        @if(isset($suppliers))
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>


                <div class="flex justify-end space-x-3 gap-2 pt-4 border-t border-gray-100 dark:border-gray-700 mt-6">
                    <button type="button" data-modal-close class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition">
                        @lang('general.cancel')
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600 transition">
                        @lang('general.save')
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
