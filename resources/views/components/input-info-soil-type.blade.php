@props(['plot'])

<div class="col-span-1 sm:col-span-2">
    <label class="block text-sm font-medium leading-6 text-gray-600 capitalize" for="soil_type">Soil Type</label>
    <div class="mt-2">
        <select name="soil_type" id="soil_type" class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            <option value="clay" {{(old('soil_type') ?? $plot->soil_type ?? '') == 'clay loam' ? 'selected' : '' }}>Clay</option>
            <option value="clay loam" {{ (old('soil_type') ?? $plot->soil_type ?? '') == 'clay loam' ? 'selected' : '' }}>Clay loam</option>
            <option value="loam" {{ (old('soil_type') ?? $plot->soil_type ?? '') == 'loam' ? 'selected' : '' }}>Loam</option>
            <option value="loamy sand" {{ (old('soil_type') ?? $plot->soil_type ?? '') == 'loamy sand' ? 'selected' : '' }}>Loamy sand</option>
            <option value="sand" {{ (old('soil_type') ?? $plot->soil_type ?? '') == 'sand' ? 'selected' : '' }}>Sand</option>
            <option value="sandy clay" {{ (old('soil_type') ?? $plot->soil_type ?? '') == 'sandy clay' ? 'selected' : '' }}>Sandy clay</option>
            <option value="sandy clay loam" {{ (old('soil_type') ?? $plot->soil_type ?? '') == 'sandy clay loam' ? 'selected' : '' }}>Sandy clay loam</option>
            <option value="sandy loam" {{ (old('soil_type') ?? $plot->soil_type ?? '') == 'sandy loam' ? 'selected' : '' }}>Sandy loam</option>
            <option value="silt" {{ (old('soil_type') ?? $plot->soil_type ?? '') == 'silt' ? 'selected' : '' }}>Silt</option>
            <option value="silty clay" {{ (old('soil_type') ?? $plot->soil_type ?? '') == 'silty clay' ? 'selected' : '' }}>Silty clay</option>
            <option value="silt loam" {{ (old('soil_type') ?? $plot->soil_type ?? '') == 'silt loam' ? 'selected' : '' }}>Silt loam</option>
            <option value="silty clay loam" {{ (old('soil_type') ?? $plot->soil_type ?? '') == 'silty clay loam' ? 'selected' : '' }}>Silty clay loam</option>

        </select>
    </div>
</div>
