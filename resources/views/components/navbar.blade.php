<aside class="fixed flex flex-col h-full px-5 py-3 overflow-y-auto">

    <nav class="h-full p-3 space-y-3 bg-gray-200 rounded-md">

        <a href="/" class="flex items-center space-x-2 pt-3 pb-4 pl-2 gradient-border-bottom">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-10 h-10">
            <span class="font-semibold text-grad-blue">{{ config('app.brand_name') }}</span>
        </a>

        <div class="space-y-3 pb-3 gradient-border-bottom">
            <label class="px-3 text-xs text-gray-500 uppercase font-semibold">Overview</label>
            <x-navbar-links href="/dashboard" icon="fa-chart-line">Dashboard</x-navbar-links>
            <x-navbar-links href="/map" icon="fa-earth-asia">Interactive Map</x-navbar-links>
            <x-navbar-links href="/weather" icon="fa-cloud">Weather</x-navbar-links>
        </div>
        <div class="space-y-3 pb-3 gradient-border-bottom">
            <label class="px-3 text-xs text-gray-500 uppercase font-semibold">Analytics</label>
            <x-navbar-links href="/plot" icon="fa-vector-square">Plot Management</x-navbar-links>
            <x-navbar-links href="/soil" icon="fa-heart">Soil Health</x-navbar-links>
            <x-navbar-links href="/crop-yield" icon="fa-apple-whole">Crop Yield</x-navbar-links>
        </div>
        <div class="space-y-3 pb-3 gradient-border-bottom">
            <label class="px-3 text-xs text-gray-500 uppercase font-semibold">Guidance</label>
            <x-navbar-links href="/crop-recommendation" icon="fa-seedling">Crop Recommendation</x-navbar-links>
            <x-navbar-links href="/test" icon="fa-book">Knowledge Base</x-navbar-links>
        </div>
        @role('admin')
            <div class="space-y-3 ">
                <label class="px-3 text-xs text-gray-500 uppercase font-semibold">Moderation</label>
                <x-navbar-links href="#" icon="fa-user-gear">Manage Users</x-navbar-links>
                <x-navbar-links href="#" icon="fa-square-plus">Create Post</x-navbar-links>
            </div>
        @endrole
    </nav>
</aside>
