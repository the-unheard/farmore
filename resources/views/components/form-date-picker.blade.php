@props(['input_id', 'placeholder', 'value' => null, 'comment' => null])

<div class="col-span-1 sm:col-span-2">
    <x-form-label for="{{ $input_id }}">{{ $slot }} {{ $comment }}</x-form-label>
    <div class="mt-2 relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
            </svg>
        </div>
        <input
            datepicker
            name="{{ $input_id }}"
            id="{{ $input_id }}"
            type="text"
            datepicker-format="mm-dd-yyyy"
            {{ $attributes->merge([
                'class' => 'block w-full rounded-md border-0 pl-10 pr-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                'placeholder' => 'Select date'
            ]) }}
            value="{{ old($input_id, request()->input($input_id, $value && $value->$input_id ? \Carbon\Carbon::parse($value->$input_id)->format('m-d-Y') : '')) }}"
        >
        <x-form-error name="{{ $input_id }}"/>
    </div>
</div>
