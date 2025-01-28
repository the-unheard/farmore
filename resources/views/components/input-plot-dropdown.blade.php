<div class="col-span-1 sm:col-span-2">
    <label class="block text-sm font-medium leading-6 text-gray-600 capitalize" for="plot_id">Select Plot</label>
    <div class="mt-2">
        <select name="plot_id" id="plot_id" class="block w-full rounded-md border-0 px-3 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            @foreach($plots as $plot)
                <option value="{{ $plot->id }}" {{ $selected == $plot->id ? 'selected' : '' }}>
                    {{ $plot->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
