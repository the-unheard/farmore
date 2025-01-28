@props(['selected' => null, 'input_id' => null])

<div class="col-span-1 sm:col-span-2">
    <x-form-label for="crop">Crop Type</x-form-label>
    <div class="mt-2">
        <select name="crop" id="crop" class="block w-full p-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            @php
                $crops = [
                    'Rice', 'Maize', 'Chickpea', 'Kidney Beans', 'Pigeon Peas',
                    'Moth Beans', 'Mungbean', 'Black Gram', 'Lentil', 'Pomegranate',
                    'Banana', 'Mango', 'Grapes', 'Watermelon', 'Muskmelon',
                    'Apple', 'Orange', 'Papaya', 'Coconut', 'Cotton', 'Jute', 'Coffee',
                ];
                $selectedCrop = old('crop', $selected);
            @endphp

            @foreach($crops as $crop)
                <option value="{{ $crop }}" {{ $selectedCrop == $crop ? 'selected' : '' }}>
                    {{ $crop }}
                </option>
            @endforeach
        </select>
        <x-form-error name="{{ $input_id }}"/>
    </div>
</div>
