@props(['label', 'value' => '-', 'class' => ''])

<div class="flex items-center justify-between">
    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $label }}</dt>
    <dd class="text-base text-gray-900 dark:text-white {{ $class }}">{!! $value !!}</dd>
</div>
