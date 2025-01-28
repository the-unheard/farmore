@props(['crops', 'type', 'model' => null, 'selected' => ''])

<script>
    const selectedCrop = @json($selected);
</script>

<div class="col-span-1 sm:col-span-2">
    <label class="block text-sm font-medium leading-6 text-gray-600 capitalize" for="{{ $type }}">Crop Type</label>
    <div class="mt-2">
        <div class="relative">
            <input type="text" placeholder="Search crop..."
                   class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                   id="{{ $type }}"
                   name="{{ $type }}"
                   value="{{ $selected }}"
            >

            <div class="absolute z-10 bg-white border border-gray-300 rounded shadow-lg mt-1 w-full max-h-48 overflow-y-auto hidden"
                 id="crop-dropdown">
                <ul id="crop-list">
                    @foreach ($crops as $crop)
                        <li class="text-sm p-2 hover:bg-sky-200 cursor-pointer"
                            data-crop="{{ strtolower($crop->crop_name) }} {{ strtolower($crop->other_name) }}">
                            <span class="font-semibold">{{ $crop->crop_name }}</span>
                            <span class="text-gray-500">({{ $crop->other_name }})</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <x-form-error name="{{ $type }}"/>
    </div>
</div>
