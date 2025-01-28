@props(['label', 'value'])

<li class="border-b border-dashed border-gray-200 last:border-b-0 last:mb-0">
    <a class="flex justify-between px-1 py-1 rounded-sm hover:bg-gray-200">
        <span class="text-gray-600 font-medium text-sm">{{ $label }}</span>
        <span class="text-gray-500 text-sm">{{ $value }}</span>
    </a>
</li>
