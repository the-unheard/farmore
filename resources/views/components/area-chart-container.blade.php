@props(['div_id'])

<div class="w-52 max-w-sm w-full bg-white rounded-lg shadow mb-4 p-4 md:p-2">
    <div class="flex justify-between">
        <div>
            <h5 class="leading-none text-lg font-bold text-gray-900 pb-2">{{ $slot }}</h5>
        </div>
    </div>
    <div id="{{ $div_id }}"></div>
</div>
