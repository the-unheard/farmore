@props(['average'])

<div class="w-1/2 bg-gray-50 text-gray-500 p-4 rounded-lg flex justify-between items-center shadow-sm">
    <div>
        <label class="block text-sm font-medium">Average Rating</label>
        <x-map.show-star-rating :avg="$average"/>
    </div>
    <div class="bg-grad-blue rounded-lg shadow-md py-3 min-w-[53px] text-center">
        <i class="fas fa-star text-white text-lg"></i>
    </div>
</div>
