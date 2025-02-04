<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.brand_name') }}</title>
    <script src="https://kit.fontawesome.com/c1ea300973.js" crossorigin="anonymous"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body>

    <!-- Navbar -->
    <nav class="bg-white shadow-md fixed w-full z-10 top-0">
        <div class="container mx-auto px-6 py-2 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <a href="#" class="flex items-center space-x-2 py-3">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-[60px] h-[60px]">
                    <span class="text-2xl font-semibold text-grad-blue">{{ config('app.brand_name') }}</span>
                </a>
            </div>
            <header class="space-x-2">
                <div class="flex space-x-4">
                    @guest
                        <a href="/auth/login" class="px-4 py-2 text-gray-600">Login</a>
                        <a href="/auth/register" class="px-4 py-2 text-gray-600">Register</a>
                    @endguest
                    @auth
                        <a class="px-4 py-2 text-gray-600">{{ auth()->user()->username }}</a>
                        <form method="POST" action="/auth/logout" id="logout-form">
                            @csrf
                            <button form="logout-form" class="px-4 py-2 text-gray-600">Logout</button>
                        </form>
                    @endauth
                </div>
            </header>
        </div>
    </nav>

    <!-- Intro -->
    <section class="bg-[#F6F9FF] pt-80 flex items-center justify-center">
        <div class="w-1/2 text-center">
            <h1 class="text-2xl font-semibold text-gray-800">Start growing smarter with {{ config('app.brand_name') }}</h1>
            <p class="text-md mt-4 text-gray-600">Manage your farms, monitor soil health and crop yields effortlessly.</p>
            <a href="/dashboard" class="inline-block mt-6 w-40 px-4 py-4 bg-grad-blue hover:bg-grad-blue text-white font-semibold rounded-md">Get Started</a>
        </div>
    </section>

    <!-- Qualities -->
    <section class="pt-16 pb-32 bg-[#F6F9FF]">
        <div class="container mx-auto text-center">
{{--            <h2 class="text-2xl font-bold text-gray-800">Why choose Farmore?</h2>--}}
{{--            <p class="text-md mt-4 mb-24 text-gray-600">Here are many reasons you'll love using our platform</p>--}}
            <div class="flex justify-center mt-8 space-x-6 gap-20">
                <!-- Icon -->
                <div class="text-center">
                    <div class="w-12 h-auto mx-auto text-sky-500 flex justify-center items-center rounded-full text-7xl">
                        <i class="fa-solid fa-hourglass-half text-grad-blue"></i>
                    </div>
                    <h3 class="my-5 text-lg font-semibold text-gray-700">Fast</h3>
                    <p class="w-36 text-gray-500 text-sm">Quick and reliable insights</p>
                </div>
                <!-- Icon -->
                <div class="text-center">
                    <div class="w-12 h-auto mx-auto text-sky-500 flex justify-center items-center rounded-full text-7xl">
                        <i class="fa-solid fa-gear text-grad-blue"></i>
                    </div>
                    <h3 class="my-5 text-lg font-semibold text-gray-700">Efficient</h3>
                    <p class="w-36 text-gray-500 text-sm">Maximize your yield with less effort</p>
                </div>
                <!-- Icon -->
                <div class="text-center">
                    <div class="w-12 h-auto mx-auto text-sky-500 flex justify-center items-center rounded-full text-7xl">
                        <i class="fa-solid fa-users text-grad-blue"></i>
                    </div>
                    <h3 class="my-5 text-lg font-semibold text-gray-700">Collaborative</h3>
                    <p class="w-36 text-gray-500 text-sm">Share plots and soil data easily</p>
                </div>
                <!-- Icon -->
                <div class="text-center">
                    <div class="w-12 h-auto mx-auto text-sky-500 flex justify-center items-center rounded-full text-7xl">
                        <i class="fa-solid fa-map-location-dot text-grad-blue"></i>
                    </div>
                    <h3 class="my-5 text-lg font-semibold text-gray-700">Interactive</h3>
                    <p class="w-36 text-gray-500 text-sm">Visualize farms on the interactive map</p>
                </div>
                <!-- Icon -->
                <div class="text-center">
                    <div class="w-12 h-auto mx-auto text-sky-500 flex justify-center items-center rounded-full text-7xl">
                        <i class="fa-solid fa-thumbs-up text-grad-blue"></i>
                    </div>
                    <h3 class="my-5 text-lg font-semibold text-gray-700">Easy</h3>
                    <p class="w-36 text-gray-500 text-sm">Simple and user-friendly interface</p>
                </div>
            </div>
        </div>
    </section>


    <!-- How To Get Started -->
    <section class="py-20 bg-white relative z-0">
        <div class="container mx-auto text-center">
            <h2 class="text-2xl font-bold text-gray-800">How to get started</h2>
            <p class="text-md mt-4 text-gray-600">Follow these easy steps to start managing your farms more efficiently.</p>
        </div>
        <div class="container mx-auto py-8">
            <div class="relative w-[75%] mx-auto top-[50px]">
                <div class="h-1 bg-grad-blue relative my-5 left-2"></div>
            </div>
            <div class="flex justify-between mt-8 z-10 relative">
                <div class="w-1/4 text-center px-4">
                    <div class="bg-grad-blue rounded-full text-gray-100 px-3 py-1 max-w-8 mx-auto">1</div>
                    <p class="text-gray-600 my-5">Create a plot</p>
                    <img src="{{ asset('images/home/plot-create.png') }}" alt="Step 1 Image" class="mx-auto mt-4 w-52">
                </div>
                <div class="w-1/4 text-center px-4">
                    <div class="bg-grad-blue rounded-full text-gray-100 px-3 py-1 max-w-8 mx-auto">2</div>
                    <p class="text-gray-600 my-5">Input soil health records</p>
                    <img src="{{ asset('images/home/soil-create.png') }}" alt="Step 2 Image" class="mx-auto mt-4 w-52">
                </div>
                <div class="w-1/4 text-center px-4">
                    <div class="bg-grad-blue rounded-full text-gray-100 px-3 py-1 max-w-8 mx-auto">3</div>
                    <p class="text-gray-600 my-5">Input crop yield records</p>
                    <img src="{{ asset('images/home/crop-create.png') }}" alt="Step 3 Image" class="mx-auto mt-4 w-52">
                </div>
                <div class="w-1/4 text-center px-4">
                    <div class="bg-grad-blue rounded-full text-gray-100 px-3 py-1 max-w-8 mx-auto">4</div>
                    <p class="text-gray-600 my-5">Monitor your metrics</p>
                    <img src="{{ asset('images/home/crop-index.png') }}" alt="Step 4 Image" class="mx-auto mt-4 w-52">
                </div>
            </div>
        </div>
    </section>

    <!-- Instructions -->
    <section class="py-20 bg-gray-100">
        <div class="container mx-auto text-center">
            <h2 class="text-2xl font-bold text-gray-800">Features</h2>
            <p class="text-md mt-4 text-gray-600">Discover the tools that make {{ config('app.brand_name') }} your go-to platform for smarter farming.</p>
        </div>

        <div class="container max-w-5xl py-14 mx-auto flex flex-col md:flex-row items-center gradient-border-bottom">
            <div class="w-full md:w-1/2">
                <div class="w-7/12 mx-auto">
                    <h3 class="text-2xl font-semibold text-gray-700">Interactive map</h3>
                    <p class="mt-4 text-gray-600">
                        Discover public farm plots with soil health data and crop suggestions
                    </p>
                </div>
            </div>
            <div class="w-full md:w-1/2 text-center">
                <i class="fa-solid fa-earth-asia text-9xl text-grad-blue"></i>
            </div>
        </div>

        <div class="container max-w-5xl py-14 mx-auto flex flex-row-reverse items-center gradient-border-bottom">
            <div class="w-full md:w-1/2">
                <div class="w-7/12 mx-auto">
                    <h3 class="text-2xl font-semibold text-gray-700">Weather forecast</h3>
                    <p class="mt-4 text-gray-600">
                        Get real-time weather updates to plan your farming activities effectively
                    </p>
                </div>
            </div>
            <div class="w-full md:w-1/2 text-center">
                <i class="fa-solid fa-cloud-sun-rain text-9xl text-grad-blue"></i>
            </div>
        </div>

        <div class="container max-w-5xl py-14 mx-auto flex flex-col md:flex-row items-center gradient-border-bottom">
            <div class="w-full md:w-1/2">
                <div class="w-7/12 mx-auto">
                    <h3 class="text-2xl font-semibold text-gray-700">Crop recommendation</h3>
                    <p class="mt-4 text-gray-600">
                        Receive tailored crop suggestions based on soil health using machine learning
                    </p>
                </div>
            </div>
            <div class="w-full md:w-1/2 text-center">
                <i class="fa-solid fa-seedling text-9xl text-grad-blue"></i>
            </div>
        </div>

        <div class="container max-w-5xl py-14 mx-auto flex flex-row-reverse items-center">
            <div class="w-full md:w-1/2">
                <div class="w-7/12 mx-auto">
                    <h3 class="text-2xl font-semibold text-gray-700">Knowledge Base</h3>
                    <p class="mt-4 text-gray-600">
                        Access insights, guides, and best practices for improving farm performance
                    </p>
                </div>
            </div>
            <div class="w-full md:w-1/2 text-center">
                <i class="fa-solid fa-book text-9xl text-grad-blue"></i>
            </div>
        </div>

    </section>

{{--    <section class="py-20 bg-white">--}}
{{--        <div class="container mx-auto text-center">--}}
{{--            <h2 class="text-2xl font-bold text-gray-800">Who is Farmore for?</h2>--}}
{{--            <div class="flex flex-wrap justify-center mt-10">--}}
{{--                <!-- Card 1 -->--}}
{{--                <div class="w-[280px] p-4">--}}
{{--                    <div class="bg-gray-200 rounded-lg shadow-lg overflow-hidden">--}}
{{--                        <img src="{{ asset('images/home/farmer.png') }}" alt="Feature Image" class="mx-auto mt-7 w-48">--}}
{{--                        <div class="p-4">--}}
{{--                            <h3 class="font-semibold text-md text-gray-700">Farmers</h3>--}}
{{--                            <p class="text-gray-600 mt-2 text-sm">Easily manage plots, soil health, and crop yields in one place</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <!-- Card 1 -->--}}
{{--                <div class="w-[280px] p-4">--}}
{{--                    <div class="bg-gray-200 rounded-lg shadow-lg overflow-hidden">--}}
{{--                        <img src="{{ asset('images/home/consultant.png') }}" alt="Feature Image" class="mx-auto mt-7 w-48">--}}
{{--                        <div class="p-4">--}}
{{--                            <h3 class="font-semibold text-md text-gray-700">Agricultural Consultants</h3>--}}
{{--                            <p class="text-gray-600 mt-2 text-sm">Provide data-driven advice to help clients optimize their farming practices</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <!-- Card 1 -->--}}
{{--                <div class="w-[280px] p-4">--}}
{{--                    <div class="bg-gray-200 rounded-lg shadow-lg overflow-hidden">--}}
{{--                        <img src="{{ asset('images/home/researcher.png') }}" alt="Feature Image" class="mx-auto mt-7 w-48">--}}
{{--                        <div class="p-4">--}}
{{--                            <h3 class="font-semibold text-md text-gray-700">Environmental Researchers</h3>--}}
{{--                            <p class="text-gray-600 mt-2 text-sm">Track soil and crop data to study the environmental impact of farming</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <!-- Card 1 -->--}}
{{--                <div class="w-[280px] p-4">--}}
{{--                    <div class="bg-gray-200 rounded-lg shadow-lg overflow-hidden">--}}
{{--                        <img src="{{ asset('images/home/agribusiness.png') }}" alt="Feature Image" class="mx-auto mt-7 w-48">--}}
{{--                        <div class="p-4">--}}
{{--                            <h3 class="font-semibold text-md text-gray-700">Agribusiness Companies</h3>--}}
{{--                            <p class="text-gray-600 mt-2 text-sm">Analyze large-scale farm operations to maximize profitability and efficiency</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

    <!-- Footer -->
    <footer class="bg-gray-800 py-6">
        <div class="text-sm container mx-auto text-center text-white">
            <p>&copy; 2024 {{ config('app.brand_name') }}. All rights reserved.</p>
        </div>
    </footer>

    <!-- Floating Button to Scroll to Top -->
    <button onclick="scrollToTop()" class="fixed bottom-5 right-5 bg-grad-blue hover:bg-grad-blue text-white p-4 rounded-full shadow-lg hover:bg-sky-600">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V6m-7 7l7-7 7 7" />
        </svg>
    </button>

    <script>
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>
