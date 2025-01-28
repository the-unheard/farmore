@props(['type' => 'success', 'message' => ''])

@php
    $iconClasses = [
        'success' => 'fa-solid fa-check-circle text-green-500',
        'error' => 'fa-solid fa-exclamation-circle text-red-500',
        'warning' => 'fa-solid fa-exclamation-triangle text-orange-500',
    ];
    $bgClasses = [
        'success' => 'bg-green-100 text-green-600',
        'error' => 'bg-red-100 text-red-600',
        'warning' => 'bg-orange-100 text-orange-600',
    ];
@endphp

<div class="relative">
    <div id="toast-{{ $type }}" class="fixed top-0 left-1/2 transform -translate-x-1/2 mt-4 z-50 flex items-center w-full max-w-xs p-4 bg-gray-50 rounded-lg shadow" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 {{ $bgClasses[$type] }} rounded-md">
            <i class="{{ $iconClasses[$type] }} w-5 h-5 text-center"></i>
        </div>
        <div class="ml-3 text-sm font-normal text-gray-600">{{ $message }}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-gray-50 text-gray-400 hover:text-gray-900 rounded-lg p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#toast-{{ $type }}" aria-label="Close">
            <svg class="w-3 h-3" aria-hidden="true" fill="none" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
            </svg>
        </button>
    </div>
</div>
