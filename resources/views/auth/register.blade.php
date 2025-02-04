<x-layout-auth>
    <x-slot:title>Farmore - Register</x-slot:title>

    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="bg-gray-50 p-12 rounded-lg shadow-sm mx-auto sm:w-full sm:max-w-sm border-gray-200 border-2">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <img class="mx-auto h-20 w-auto" src="{{ asset('images/logo.svg') }}" alt="Logo">
                <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Create an account</h2>
            </div>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form class="space-y-6" action="/auth/register" method="POST">
                    @csrf

                    <div>
                        <x-form-label for="username">Username</x-form-label>
                        <div class="mt-2">
                            <x-form-input id="username" name="username" type="text" required />
                            <x-form-error name="username"/>
                        </div>
                    </div>

                    <div>
                        <x-form-label for="email">Email Address</x-form-label>
                        <div class="mt-2">
                            <x-form-input id="email" name="email" type="email" autocomplete="email" required />
                            <x-form-error name="email"/>
                        </div>
                    </div>

                    <div>
                        <x-form-label for="password">Password</x-form-label>
                        <div class="mt-2">
                            <x-form-input id="password" name="password" type="password" required />
                            <x-form-error name="password"/>
                        </div>
                    </div>

                    <div>
                        <x-form-label for="password_confirmation">Confirm Password</x-form-label>
                        <div class="mt-2">
                            <x-form-input id="password_confirmation" name="password_confirmation" type="password" required />
                            <x-form-error name="password_confirmation"/>
                        </div>
                    </div>

                    <div>
                        <x-form-button type="submit">Register</x-form-button>
                    </div>
                </form>
                <p class="text-gray-600 text-sm text-center m-4">Already have an account?
                    <a href="/auth/login" class="font-semibold text-grad-blue">Login</a>
                </p>
            </div>
        </div>
    </div>

</x-layout-auth>
