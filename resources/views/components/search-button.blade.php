<button
    {{$attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-between gap-1 items-center px-4 py-2 text-[#F5F0F1] bg-[#4332BD] hover:ring-2 hover:ring-[#4332BD] hover:ring-offset-2 border border-transparent rounded-md font-semibold text-xs uppercase transition ease-in-out duration-300'])}}>
    {{$slot}}
</button>
