<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Categories | Perfume Store Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="bg-white text-black">

    <header class="border-b border-black/10">

        <div class="flex h-[88px] items-center justify-between px-8 lg:px-16">

            <a href="{{ route('admin.dashboard') }}"
               class="text-xl font-semibold tracking-[-0.04em]">
                PERFUME
            </a>

            <nav class="hidden items-center gap-10 md:flex">

                <a href="{{ route('admin.dashboard') }}"
                   class="text-[10px] uppercase tracking-[0.22em] text-black/50 transition hover:text-black">
                    Dashboard
                </a>

                <a href="{{ route('admin.perfumes') }}"
                   class="text-[10px] uppercase tracking-[0.22em] text-black/50 transition hover:text-black">
                    Perfumes
                </a>

                <a href="{{ route('admin.categories') }}"
                   class="border-b border-black pb-2 text-[10px] uppercase tracking-[0.22em] text-black">
                    Categories
                </a>

            </nav>

            <div class="flex items-center gap-6">

                <span class="hidden text-[10px] uppercase tracking-[0.2em] md:block">
                    {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="text-[10px] uppercase tracking-[0.2em] transition hover:text-black/50">
                        Logout
                    </button>
                </form>

            </div>

        </div>

    </header>

    <main>

        <livewire:admin.category-manager />

    </main>

    @livewireScripts

</body>

</html>