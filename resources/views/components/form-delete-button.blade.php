<button {{ $attributes->merge(['class' => 'rounded-md bg-red-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500', 'type' => 'submit']) }}>
    {{ $slot }}
</button>
