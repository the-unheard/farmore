@props(['icon', 'label', 'id', 'color'])

<div class="relative flex items-start space-x-4 mt-5">
    <div class="relative">
        <div class="bg-gray-50 z-10 relative">
            <i class="fas {{ $icon }} {{ $color }} text-xl w-9 py-2 text-center"></i>
        </div>
    </div>
    <div>
        <p class="text-sm text-gray-500">{{ $label }}</p>
        <p id="{{ $id }}" class="text-gray-800 font-semibold text-sm"></p>
    </div>
</div>
