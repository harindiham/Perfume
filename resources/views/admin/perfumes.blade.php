<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Perfumes — Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="bg-white text-black">

    <header class="border-b border-black/15">

    

</div>
        <div class="flex items-center justify-between px-6 py-5 lg:px-12">

            <a href="{{ route('admin.dashboard') }}"
               class="text-xl font-semibold tracking-[-0.04em]">
                PERFUME
            </a>

            <nav class="hidden items-center gap-8 text-[10px] uppercase tracking-[0.18em] md:flex">

                <a href="{{ route('admin.dashboard') }}"
                   class="text-black/50 hover:text-black">
                    Dashboard
                </a>

                <a href="{{ route('admin.perfumes') }}"
                   class="border-b border-black pb-1">
                    Perfumes
                </a>

                <a href="#"
                   class="text-black/50 hover:text-black">
                    Categories
                </a>

            </nav>

            <div class="flex items-center gap-5 text-[10px] uppercase tracking-[0.15em]">

                <span class="hidden sm:block">
                    {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="hover:underline">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </header>

    <main>
        <livewire:admin.perfume-manager />
    </main>

    @livewireScripts

</body>
</html>