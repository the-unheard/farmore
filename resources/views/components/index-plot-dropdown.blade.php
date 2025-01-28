@props(['action', 'model', 'selected' => null])

<form method="GET" action="{{ $action }}" class="mb-4">
    <div class="flex w-1/3">
        <label for="plot_id" class="flex items-center px-3 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-700 h-10 w-32">
            Select Plot
        </label>
        <select name="plot_id" id="plot_id" onchange="this.form.submit()" class="block w-96 p-2 rounded-r-md border border-gray-300 bg-white shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm h-10">
            @foreach($model as $item)
                <option value="{{ $item->id }}" {{ request()->input('plot_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
</form>
