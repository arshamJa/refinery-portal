<div class="p-4 rounded-xl shadow-md space-y-2 text-sm border {{ $statusClass ?? 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600' }}">
    <div><strong>{{ __('نام:') }}</strong> {{ $name }}</div>
    <div><strong>{{ __('واحد:') }}</strong> {{ $unit }}</div>
    <div><strong>{{ __('سمت:') }}</strong> {{ $position }}</div>
    {{-- Optional replacement --}}
    @isset($replacement)
        <div><strong>{{ __('جانشین اینجانب:') }}</strong> {{ $replacement }}</div>
    @endisset
    @isset($reason)
        <div><strong>{{ __('علت رد:') }}</strong> {{ $reason }}</div>
    @endisset

</div>
