@props(['value', 'analysis', 'label', 'icon'])

<div class="bg-gray-50 px-5 rounded-lg shadow flex justify-between items-center relative">
    <div>
        <p class="text-sm font-semibold text-gray-500">{{ $label }}</p>
        <span class="text-2xl font-bold text-gray-800">{{ $value }}</span>
        <span class="text-sm font-semibold @if($analysis === 'Good') text-green-500 @else text-red-500 @endif">{{ $analysis }}</span>
    </div>
    <div class="bg-grad-blue rounded-lg shadow-md py-3 min-w-[53px] text-center">
        <i class="fas {{ $icon }} text-white text-lg"></i>
    </div>
</div>
