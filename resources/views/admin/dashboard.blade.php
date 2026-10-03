<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin — Perfume Store</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-black">

    <div class="min-h-screen">

        {{-- Navigation --}}
        <header class="border-b border-black/15">
            <div class="flex items-center justify-between px-6 py-5 lg:px-12">

                {{-- Logo --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="text-xl font-semibold tracking-[-0.04em] transition-opacity hover:opacity-60">
                    PERFUME
                </a>


                {{-- Main navigation --}}
                <nav class="hidden items-center gap-8 text-[10px] uppercase tracking-[0.18em] md:flex">

                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}"
                       class="border-b border-black pb-1 transition-opacity hover:opacity-60">
                        Dashboard
                    </a>

                    {{-- Perfumes --}}
                    <a href="{{ route('admin.perfumes') }}"
                       class="text-black/50 transition hover:text-black">
                        Perfumes
                    </a>

                    {{-- Categories --}}
                    <a href="{{ route('admin.categories') }}"
                       class="text-black/50 transition hover:text-black">
                        Categories
                    </a>

                </nav>


                {{-- User section --}}
                <div class="flex items-center gap-5 text-[10px] uppercase tracking-[0.15em]">

                    <span class="hidden sm:block">
                        {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="transition-opacity hover:opacity-60">
                            Logout
                        </button>
                    </form>

                </div>

            </div>
        </header>


        {{-- Main content --}}
        <main>

            {{-- Intro --}}
            <section class="grid min-h-[420px] grid-cols-1 border-b border-black/15 lg:grid-cols-2">

                {{-- Left introduction --}}
                <div class="flex items-center border-b border-black/15 px-6 py-16 lg:border-b-0 lg:border-r lg:px-16">

                    <div>

                        <p class="mb-6 text-[10px] uppercase tracking-[0.25em] text-black/50">
                            Administration
                        </p>

                        <h1 class="max-w-xl text-5xl font-light leading-[0.95] tracking-[-0.05em] md:text-7xl">
                            Perfume
                            <br>
                            Store
                        </h1>

                        <p class="mt-8 max-w-md text-sm leading-7 text-black/60">
                            Manage the perfume catalogue, product information,
                            categories and stock from one place.
                        </p>

                    </div>

                </div>


                {{-- Right welcome panel --}}
                <div class="flex items-end bg-black p-6 text-white lg:p-16">

                    <div class="w-full">

                        <p class="mb-12 text-[10px] uppercase tracking-[0.25em] text-white/50">
                            Welcome
                        </p>

                        <p class="text-2xl font-light tracking-[-0.03em]">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="mt-2 text-xs uppercase tracking-[0.18em] text-white/50">
                            Store Administrator
                        </p>

                    </div>

                </div>

            </section>


            {{-- Statistics --}}
            <section>

                {{-- Overview heading --}}
                <div class="border-b border-black/15 px-6 py-5 lg:px-12">
                    <p class="text-[10px] uppercase tracking-[0.25em] text-black/50">
                        Overview
                    </p>
                </div>


                {{-- Statistics cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">


                    {{-- Total perfumes --}}
                    <a href="{{ route('admin.perfumes') }}"
                       class="group border-b border-black/15 p-8 transition-colors duration-300 hover:bg-black hover:text-white lg:border-r">

                        <p class="text-[10px] uppercase tracking-[0.2em] text-black/50 transition-colors group-hover:text-white/50">
                            Total Perfumes
                        </p>

                        <p class="mt-16 text-5xl font-light tracking-[-0.05em]">
                            {{ $totalPerfumes }}
                        </p>

                        <p class="mt-4 text-xs text-black/50 transition-colors group-hover:text-white/50">
                            Products in catalogue
                        </p>

                        <p class="mt-8 text-[10px] uppercase tracking-[0.2em] opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            View Perfumes →
                        </p>

                    </a>


                    {{-- Categories --}}
                    <a href="{{ route('admin.categories') }}"
                       class="group border-b border-black/15 p-8 transition-colors duration-300 hover:bg-black hover:text-white lg:border-r">

                        <p class="text-[10px] uppercase tracking-[0.2em] text-black/50 transition-colors group-hover:text-white/50">
                            Categories
                        </p>

                        <p class="mt-16 text-5xl font-light tracking-[-0.05em]">
                            {{ $totalCategories }}
                        </p>

                        <p class="mt-4 text-xs text-black/50 transition-colors group-hover:text-white/50">
                            Available categories
                        </p>

                        <p class="mt-8 text-[10px] uppercase tracking-[0.2em] opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            View Categories →
                        </p>

                    </a>


                    {{-- Active perfumes --}}
                    <a href="{{ route('admin.perfumes') }}"
                       class="group border-b border-black/15 p-8 transition-colors duration-300 hover:bg-black hover:text-white lg:border-r">

                        <p class="text-[10px] uppercase tracking-[0.2em] text-black/50 transition-colors group-hover:text-white/50">
                            Active
                        </p>

                        <p class="mt-16 text-5xl font-light tracking-[-0.05em]">
                            {{ $activePerfumes }}
                        </p>

                        <p class="mt-4 text-xs text-black/50 transition-colors group-hover:text-white/50">
                            Published perfumes
                        </p>

                        <p class="mt-8 text-[10px] uppercase tracking-[0.2em] opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            View Perfumes →
                        </p>

                    </a>


                    {{-- Low stock --}}
                    <a href="{{ route('admin.perfumes') }}"
                       class="group border-b border-black/15 p-8 transition-colors duration-300 hover:bg-black hover:text-white">

                        <p class="text-[10px] uppercase tracking-[0.2em] text-black/50 transition-colors group-hover:text-white/50">
                            Low Stock
                        </p>

                        <p class="mt-16 text-5xl font-light tracking-[-0.05em]">
                            {{ $lowStockPerfumes }}
                        </p>

                        <p class="mt-4 text-xs text-black/50 transition-colors group-hover:text-white/50">
                            Products requiring attention
                        </p>

                        <p class="mt-8 text-[10px] uppercase tracking-[0.2em] opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            Review Stock →
                        </p>

                    </a>

                </div>

            </section>


            {{-- Quick actions --}}
            <section class="grid grid-cols-1 lg:grid-cols-2">


                {{-- Manage perfumes --}}
                <a href="{{ route('admin.perfumes') }}"
                   class="group flex min-h-[220px] items-end border-b border-black/15 p-8 transition-colors duration-300 hover:bg-black hover:text-white lg:border-r lg:p-12">

                    <div>

                        <p class="mb-5 text-[10px] uppercase tracking-[0.25em] opacity-50">
                            Catalogue
                        </p>

                        <h2 class="text-3xl font-light tracking-[-0.04em]">
                            Manage Perfumes
                        </h2>

                        <p class="mt-4 text-xs opacity-50">
                            Add, edit and remove products
                        </p>

                        <span class="mt-8 inline-block text-[10px] uppercase tracking-[0.2em]">
                            View Catalogue →
                        </span>

                    </div>

                </a>


                {{-- Manage categories --}}
                <a href="{{ route('admin.categories') }}"
                   class="group flex min-h-[220px] items-end border-b border-black/15 p-8 transition-colors duration-300 hover:bg-black hover:text-white lg:p-12">

                    <div>

                        <p class="mb-5 text-[10px] uppercase tracking-[0.25em] opacity-50">
                            Organisation
                        </p>

                        <h2 class="text-3xl font-light tracking-[-0.04em]">
                            Manage Categories
                        </h2>

                        <p class="mt-4 text-xs opacity-50">
                            Organise your perfume catalogue
                        </p>

                        <span class="mt-8 inline-block text-[10px] uppercase tracking-[0.2em]">
                            View Categories →
                        </span>

                    </div>

                </a>

            </section>


            {{-- Footer --}}
            <footer class="flex flex-col justify-between gap-4 px-6 py-8 text-[9px] uppercase tracking-[0.2em] text-black/40 sm:flex-row lg:px-12">

                <span>
                    Perfume Store
                </span>

                <span>
                    Administration Panel
                </span>

            </footer>

        </main>

    </div>

</body>
</html>