@props(['icon' => '', 'href' => '#'])

<a href="{{ $href }}" class="{{ 'flex items-center px-3 py-2 rounded-lg hover:bg-gray-300'
    . (request()->is(trim($href,'/') . '*') ? ' bg-white ' : ' bg-transparent ') }}">
    <i class="{{ 'w-8 h-8 text-sm fa-solid rounded-lg flex items-center justify-center shadow-gray-300 shadow-md '
    . (request()->is(trim($href,'/') . '*') ? ' bg-grad-blue text-white ' : ' bg-gray-100 text-gray-600 ') . $icon }}"></i>
    <span class="mx-2 text-sm font-medium text-gray-600">{{ $slot }}</span>
</a>
