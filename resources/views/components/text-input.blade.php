@props(['disabled' => false])

<input autofocus @disabled($disabled) {{ $attributes->merge(['class' => 'w-full text-sm bg-white border rounded-md border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50']) }}>
