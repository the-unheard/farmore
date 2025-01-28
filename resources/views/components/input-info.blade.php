@props(['type', 'placeholder' => null, 'model' => null, 'comment' => null, 'readonly' => false, 'label' => $type, 'hidden' => false])

<div class="@if($hidden) hidden @endif col-span-1 sm:col-span-2">
    <label class="block text-sm font-medium leading-6 text-gray-600 capitalize" for="{{ $type }}">{{ $label }} {{ $comment }}</label>
    <div class="mt-2">
        <input class="@if($readonly) cursor-default bg-gray-300 @endif block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
               name="{{ $type }}"
               id="{{ $type }}"
               placeholder="{{ $placeholder }}"
               value="{{ old($type, request()->input($type, $model->$type ?? '')) }}"
               @if($readonly) readonly @endif
               @if($hidden) type="hidden" @endif
        >
        <x-form-error name="{{ $type }}"/>
    </div>
</div>
