
<div class="container_row grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
    <!-- Currency Select -->
    <div class="md:col-span-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang('general.currency')</label>
        <select name="currencies[{{ $index }}][currency_id]"
                required
                oninvalid="this.setCustomValidity('@lang('general.currency_id_required')')"
                oninput="setCustomValidity('')"
                class="select2 w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                {{ $errors->has("currencies.$index.currency_id") ? 'is-invalid' : '' }}>
            <option value="">{{ __('general.select') }}</option>
            @foreach($currencies as $id => $name)
                <option value="{{ $id }}"
                        {{ (old("currencies.$index.currency_id", $bonus?->currency_id  ?? null)) == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
        @error("currencies.$index.currency_id")
        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- Amount -->
    <div class="md:col-span-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang('general.amount')</label>
        <input type="text" step="0.01" name="currencies[{{ $index }}][amount]" required
               oninvalid="this.setCustomValidity('@lang('general.max_days_required')')"
               oninput="setCustomValidity('')"
               value="{{ old("currencies.$index.amount",toCurrency($bonus?->amount) ?? '') }}"
               class="separator w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white @error('currencies.'.$index.'.amount') is-invalid @enderror"
               placeholder="0.00">
        @error("currencies.$index.amount")
        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- Inventory -->
    <div class="md:col-span-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang('general.inventory')</label>
        <input type="text" name="currencies[{{ $index }}][inventory]" required
               oninvalid="this.setCustomValidity('@lang('general.inventory_required')')"
               oninput="setCustomValidity('')"
               value="{{ old("currencies.$index.inventory", toCurrency($bonus?->inventory) ?? '') }}"
               class="separator w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white @error('currencies.'.$index.'.inventory') is-invalid @enderror"
               placeholder="100">
        @error("currencies.$index.inventory")
        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- Actions -->
    <div class="md:col-span-2 flex gap-2 delete_btn">
        <button type="button"
                class="flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded-lg transition-colors">
            @lang('actions.delete')
        </button>
    </div>
</div>
