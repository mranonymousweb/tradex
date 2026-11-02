

@props([
    'name',
    'label' => '',
    'options' => [],
    'selected' => null,
    'placeholder' => __('general.select'),
    'required' => false,
    'multiple' => false,
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    <select
        id="{{ $name }}"
        name="{{ $multiple ? $name . '[]' : $name }}"
        class="select2 w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        data-placeholder="{{ $placeholder }}"
        {{ $multiple ? 'multiple' : '' }}
        {{ $required ? 'required' : '' }}
    >
        @unless($multiple)
            <option value="">{{ $placeholder }}</option>
        @endunless

        @foreach($options as $key => $text)
            <option value="{{ $key }}" @selected(collect(old($name, $selected))->contains($key))>
                {{ $text }}
            </option>
        @endforeach
    </select>
    @error($name)
    <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

