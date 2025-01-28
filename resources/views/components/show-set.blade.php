@props(['model', 'field', 'label'])

<div class="col-span-1 sm:col-span-2">
    <x-form-label for="{{ $field }}">{{ $label }}</x-form-label>
    <x-show-value>{{ $model->$field }}</x-show-value>
</div>
