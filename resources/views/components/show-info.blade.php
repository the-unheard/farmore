@props(['label', 'icon', 'colspan' => 'col-span-2', 'id' => null])

<div class="{{ $colspan }} bg-gray-100 text-gray-500 p-4 rounded-lg flex justify-between items-center shadow-sm">
    <div>
        <label class="block text-sm font-medium">{{ $label }}</label>
        <div id="{{ $id }}" class="text-2xl font-bold mt-1">{{ $slot }}&nbsp;</div>
    </div>
    <div class="bg-gray-50 p-3 rounded-md shadow-md">
        <i class="fas {{ $icon }} text-grad-blue text-3xl min-w-10 text-center"></i>
    </div>
</div>
