<x-layout>
    <x-slot:title>Manage Users - User # {{ $user->id }}</x-slot:title>
    <div class="max-w-6xl mx-auto">
        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <x-breadcrumb-container>
                    <x-breadcrumb-link href="/manage-users">Manage Users</x-breadcrumb-link>
                    <x-breadcrumb-current>User # {{ $user->id }}</x-breadcrumb-current>
                </x-breadcrumb-container>
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <x-show-info label="User ID" icon="fa-id-badge">{{ $user->id }}</x-show-info>
                    <x-show-info label="Username" icon="fa-user" colspan="col-span-4">{{ $user->username }}</x-show-info>
                    <x-show-info label="Email" icon="fa-envelope" colspan="col-span-6">{{ $user->email }}</x-show-info>

                    <x-show-info label="Role" icon="fa-user-gear">{{ $user->roles->pluck('name')->join(', ') ?: 'member' }}</x-show-info>
                    <x-show-info label="Created At" icon="fa-calendar">{{ $user->created_at }}</x-show-info>
                    <x-show-info label="Updated At" icon="fa-calendar">{{ $user->updated_at }}</x-show-info>
                </div>
            </div>
        </div>

        @if ($user->id !== 1)
        <div class="w-full flex justify-end mt-6">
            <div class="flex items-center space-x-2">
                <form method="POST" action="/manage-users/{{ $user->id }}"  onsubmit="return confirm('Are you sure you want to delete this user?');">
                    @csrf
                    @method('DELETE')
                    <x-form-delete-button>Delete User</x-form-delete-button>
                </form>
                <x-common-anchor href="/manage-users/{{ $user->id }}/edit">Change Username</x-common-anchor>
            </div>
        </div>
        @endif
    </div>
</x-layout>
