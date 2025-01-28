<x-layout>
    <x-slot:title>Soil Health Tracking - Create</x-slot:title>
    <x-slot:heading></x-slot:heading>
    <form method="POST" action="/soil/{{ $soil->id }}" class="max-w-6xl mx-auto">
        @csrf
        @method('PATCH')
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <x-breadcrumb-container>
                    <x-breadcrumb-link href="/soil">Soil Health Tracking</x-breadcrumb-link>
                    <x-breadcrumb-link href="/soil/{{ $soil->id }}">Record # {{ $soil->id }}</x-breadcrumb-link>
                    <x-breadcrumb-current>Edit</x-breadcrumb-current>
                </x-breadcrumb-container>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <x-input-plot-dropdown :plots="$plots" :selected="old('plot_id', request()->input('plot_id', $soil->plot_id ?? ''))"/>
                    <x-input-info type="nitrogen" :model="$soil" placeholder="140" comment="(mg/kg)"/>
                    <x-input-info type="phosphorus" :model="$soil" placeholder="145" comment="(mg/kg)"/>
                    <x-input-info type="potassium" :model="$soil" placeholder="205" comment="(mg/kg)"/>
                    <x-input-info type="temperature" :model="$soil" placeholder="44" comment="(°C)"/>
                    <x-input-info type="humidity" :model="$soil" placeholder="100" comment="(%)"/>
                    <x-input-info type="ph" :model="$soil" placeholder="10"/>
                    <x-form-date-picker input_id="record_date" placeholder="" :value="$soil">Record Date</x-form-date-picker>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-end items-center">
            <x-form-cancel-button href="/soil">Cancel</x-form-cancel-button>
            <x-form-submit-button>Confirm</x-form-submit-button>
        </div>
    </form>
</x-layout>
