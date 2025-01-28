@props(['model', 'field', 'label', 'icon'])

<div class="col-span-2 bg-gray-100 text-gray-500 p-4 rounded-lg flex justify-between items-center shadow-sm">
    <div>
        <label class="block text-sm font-medium">{{ $label }}</label>
        <div class="text-2xl font-bold mt-1">{{ $model->$field ? $model->$field->format('m-d-Y') : 'N/A' }}&nbsp;</div>
    </div>
    <div class="bg-gray-50 p-3 rounded-md shadow-md">
        <i class="fas {{ $icon }} text-grad-blue text-3xl min-w-10 text-center"></i>
    </div>
</div>
