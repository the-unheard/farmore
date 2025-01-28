@props(['header' => null, 'subheader' => null])

<div class="flex justify-between pb-4 mb-2">
    <div class="flex items-center">
        <div>
            <h5 class="leading-none text-2xl font-bold text-gray-900 pb-2">{{ $header }}</h5>
            <p class="text-base font-normal text-gray-500">{{ $subheader }}</p>
        </div>
    </div>
</div>
