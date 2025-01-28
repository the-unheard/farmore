@props(['label', 'minValue' => null, 'minDate' => null, 'avgValue' => null, 'maxValue' => null, 'maxDate' => null, 'prediction' => 'N/A'])

<div class="bg-white shadow rounded-lg p-6">
    <h5 class="text-lg font-semibold text-gray-900 mb-6">{{ $label }}</h5>

    @php
        if($maxValue !== 'N/A') {
            $maxPercentage = 80;
            $minPercentage = ($minValue / $maxValue) * $maxPercentage;
            $avgPercentage = ($avgValue / $maxValue) * $maxPercentage;
        } else {
            $maxPercentage = 0;
            $minPercentage = 0;
            $avgPercentage = 0;
        }
    @endphp

        <!-- Lowest Value -->
    <div class="mb-4">
        <div class="flex items-center justify-between mb-1">
            <span class="text-sm font-medium text-gray-500">Lowest</span>
            <span data-tooltip-target="tooltip-min-{{ $label }}" class="text-sm text-red-500">{{ $minValue }}</span>
            <div id="tooltip-min-{{ $label }}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                {{ $minDate }}
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
        <div class="relative w-full bg-gray-200 rounded-full h-1">
            <div class="absolute top-[-1px] bg-grad-blue h-1.5 rounded-full" style="width: {{ round($minPercentage, 2) }}%"></div>
        </div>
    </div>

    <!-- Average Value -->
    <div class="mb-4">
        <div class="flex items-center justify-between mb-1">
            <span class="text-sm font-medium text-gray-500">Average</span>
            <span class="text-sm text-gray-500">{{ $avgValue }}</span>
        </div>
        <div class="relative w-full bg-gray-200 rounded-full h-1">
            <div class="absolute top-[-1px] bg-grad-blue h-1.5 rounded-full" style="width: {{ round($avgPercentage, 2) }}%"></div>
        </div>
    </div>

    <!-- Highest Value -->
    <div class="mb-4">
        <div class="flex items-center justify-between mb-1">
            <span class="text-sm font-medium text-gray-500">Highest</span>
            <span data-tooltip-target="tooltip-max-{{ $label }}" class="text-sm text-green-500">{{ $maxValue }}</span>
            <div id="tooltip-max-{{ $label }}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                {{ $maxDate }}
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
        <div class="relative w-full bg-gray-200 rounded-full h-1">
            <div class="absolute top-[-1px] bg-grad-blue h-1.5 rounded-full" style="width: {{ round($maxPercentage, 2) }}%"></div>
        </div>
    </div>

    <!-- Predicted Value -->
    <div>
        <div class="flex items-center justify-between mb-1">
            @if($prediction != 'N/A')
                <span class="text-sm font-medium text-gray-500">Prediction (30 days)</span>
                <span class="text-lg font-semibold text-grad-blue">{{ $prediction }}</span>
            @else
                <span class="text-sm font-medium text-gray-500">Prediction requires at least 5 records</span>
            @endif
        </div>
    </div>
</div>

