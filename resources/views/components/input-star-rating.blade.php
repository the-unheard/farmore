<div class="col-span-1 sm:col-span-2">
    <x-form-label>Rate</x-form-label>
    <form {{$attributes->merge(['method' => 'POST', 'id' => 'rating-form']) }}>
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
</div>
