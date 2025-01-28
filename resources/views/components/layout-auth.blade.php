@props(['heading' => null])

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <script src="https://kit.fontawesome.com/c1ea300973.js" crossorigin="anonymous"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/js/toast-notification.js')
</head>
<body class="bg-gray-200">

    <div class="flex flex-col h-screen">
        <div class="flex min-h-screen flex-grow">
            <!-- Main Content Area -->
            <div class="flex flex-col flex-grow">
                <!-- Top Bar -->
                <header class="flex items-end justify-end w-full px-5 pt-5">
                    <!-- Login/Signup Buttons on the Right -->
                    <div class="flex space-x-4">
                        @guest
                            <a href="/auth/login" class="px-4 py-2 text-sm text-gray-600 font-semibold">Login</a>
                            <a href="/auth/register" class="px-4 py-2 text-sm text-gray-600 font-semibold">Register</a>
                        @endguest
                        @auth
                            <a class="px-4 py-2 text-sm text-gray-600 font-semibold">{{ auth()->user()->username }}</a>
                            <form method="POST" action="/auth/logout" id="logout-form">
                                @csrf
                                <button form="logout-form" class="px-4 py-2 text-sm text-gray-600 font-semibold">Logout</button>
                            </form>
                        @endauth
                    </div>
                </header>

                <main class="p-6 h-auto flex-grow">

                    @if (session('success'))
                        <x-show-notification type="success" :message="session('success')" />
                    @endif

                    @if (session('error'))
                        <x-show-notification type="error" :message="session('error')" />
                    @endif

                    @if (session('warning'))
                        <x-show-notification type="warning" :message="session('warning')" />
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

</body>
</html>
