@props(['label', 'value', 'icon', 'width' => 'w-1/2'])

<div class="{{ $width }} bg-gray-100 text-gray-500 p-4 rounded-lg flex justify-between items-center shadow-sm">
    <div>
        <label class="block text-sm font-medium">{{ $label }}</label>
        <div class="text-2xl font-bold mt-1 text-gray-800">{{ $value }}</div>
    </div>
    <div class="bg-grad-blue rounded-lg shadow-md py-3 min-w-[53px] text-center">
        <i class="fas {{ $icon }} text-white text-lg"></i>
    </div>
</div>
