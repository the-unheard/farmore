@props(['avg' => 0])

@php
    // Round down to the nearest 2 decimal places and always show 2 decimals
    $roundedAvg = number_format(floor($avg * 100) / 100, 2);
@endphp

@for ($i = 1; $i <= 5; $i++)
    @if ($avg >= $i)
        <i class="fa-solid fa-star text-yellow-400"></i>
    @else
        <i class="fa-solid fa-star text-gray-400"></i>
    @endif
@endfor
<span class="ml-2 text-sm">({{ $avg > 0 ? $roundedAvg : 'No Rating'  }})</span>
