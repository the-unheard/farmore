@props(['type', 'placeholder' => null, 'model' => null])

<div class="col-span-4">
    <label class="block text-sm font-medium leading-6 text-gray-600 capitalize" for="{{ $type }}">{{ $type }}</label>
    <div class="mt-2">
        <input type="text"
               class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
               name="{{ $type }}"
               id="{{ $type }}"
               placeholder="{{ $placeholder }}"
               value="{{ old($type, request()->input($type, $model->$type ?? '')) }}">
        <x-form-error name="{{ $type }}"/>
    </div>
</div>
