@props(['icon', 'label', 'value'])

<div class="w-1/2 flex items-center justify-between my-4 pr-6">
    <div>
        <i class="py-2 bg-grad-blue w-8 text-center rounded fas {{ $icon }} text-white"></i>
        <span class="ml-3">{{ $label }}</span>
    </div>
    <span>{{ $value }}</span>
</div>
