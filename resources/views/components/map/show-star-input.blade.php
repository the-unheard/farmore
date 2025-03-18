@props(['action', 'plot'])

<div class="w-1/2 bg-gray-50 text-gray-500 p-4 rounded-lg flex justify-between items-center shadow-sm">
    <div>
        <label class="block text-sm font-medium">Give Rating</label>
        @authNotId($plot->user_id)
        <form method="POST" id="rating-form" action="/map/{{ $action }}/rate">
            @csrf
            <div class="rating-stars flex items-center mt-2 text-gray-800 text-2xl">
                <input type="hidden" name="rating" id="rating" value="0">
                <i class="fa-solid fa-star star text-gray-400 cursor-pointer" data-value="1"></i>
                <i class="fa-solid fa-star star text-gray-400 cursor-pointer" data-value="2"></i>
                <i class="fa-solid fa-star star text-gray-400 cursor-pointer" data-value="3"></i>
                <i class="fa-solid fa-star star text-gray-400 cursor-pointer" data-value="4"></i>
                <i class="fa-solid fa-star star text-gray-400 cursor-pointer" data-value="5"></i>
                <span id="rating-text" class="ml-4 text-sm text-gray-700"></span>
            </div>
        </form>
        @else
            <div class="text-2xl font-bold mt-1">Not available</div>
        @endauthNotId

    </div>
    <div class="bg-grad-blue rounded-lg shadow-md py-3 min-w-[53px] text-center">
        <i class="fas fa-star-half-stroke text-white text-lg"></i>
    </div>
</div>
