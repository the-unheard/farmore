<x-layout>
    <x-slot:title>Manage Plots - Plot # {{ $plot->id }}</x-slot:title>
    <div class="max-w-6xl mx-auto">
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <x-breadcrumb-container>
                    <x-breadcrumb-link href="/manage-plots">Manage Plots</x-breadcrumb-link>
                    <x-breadcrumb-current>Plot # {{ $plot->id }}</x-breadcrumb-current>
                </x-breadcrumb-container>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <x-show-info label="Plot ID" icon="fa-id-badge">{{ $plot->id }}</x-show-info>
                    <x-show-info label="Plot Name" icon="fa-user" colspan="col-span-4">{{ $plot->name }}</x-show-info>

                    <x-show-info label="Owner" icon="fa-envelope">{{ $plot->username }}</x-show-info>
                    <x-show-info label="City" icon="fa-envelope">{{ $plot->city }}</x-show-info>
                    <x-show-info label="Hectare" icon="fa-envelope">{{ $plot->hectare }}</x-show-info>

                    <x-show-info label="Public" icon="fa-envelope">{{ $plot->is_public }}</x-show-info>
                    <x-show-info label="Average Rating" icon="fa-envelope">{{ $plot->average_rating }}</x-show-info>
                    <x-show-info label="Rating Count" icon="fa-envelope">{{ $plot->rating_count }}</x-show-info>
                </div>
            </div>
        </div>

        <div class="w-full flex justify-end mt-6">
            <div class="flex items-center space-x-2">
                <form method="POST" action="/manage-plots/{{ $plot->id }}"  onsubmit="return confirm('Are you sure you want to delete this plot?');">
                    @csrf
                    @method('DELETE')
                    <x-form-delete-button>Delete Plot</x-form-delete-button>
                </form>
                <x-common-anchor href="/manage-plots/{{ $plot->id }}/edit">Change Plot Name</x-common-anchor>
            </div>
        </div>
    </div>
</x-layout>
