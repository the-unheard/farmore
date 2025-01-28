@props(['nutrient', 'rateOfChange', 'unit' => null])

<div id="nutrient-{{ $nutrient }}" class="nutrient-analysis">
    <h4 class="text-lg font-semibold text-gray-900 mb-4 capitalize">{{ $nutrient }} Trend Analysis</h4>

    @foreach($rateOfChange as $data)
        <div class="mb-4">
            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($data['date'])->format('M d, Y') }}</p>
            <p class="text-sm text-gray-700">{{ ucfirst($nutrient) }}: {{ $data['value'] }} {{ $unit }}</p>

            @if($data['change'] > 0)
                <p class="text-green-600 text-sm font-bold">
                    <i class="fas fa-arrow-up"></i> {{ round($data['change'], 2) }}% over {{ floor($data['daysDifference']) }} days
                </p>
            @elseif($data['change'] < 0)
                <p class="text-red-600 text-sm font-bold">
                    <i class="fas fa-arrow-down"></i> {{ round($data['change'], 2) }}% over {{ floor($data['daysDifference']) }} days
                </p>
            @else
                <p class="text-gray-600 text-sm font-bold">
                    <i class="fas fa-arrows-alt-h"></i> No change
                </p>
            @endif
        </div>
        @if(!$loop->last)
            <hr class="my-4">
        @endif
    @endforeach
</div>
