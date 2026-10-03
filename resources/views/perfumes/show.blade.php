<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $perfume->name }} | Perfume</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8f7f3] text-[#1d1d1b]">

    <header class="border-b border-black/10 bg-[#f8f7f3]">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 lg:px-10">

            <a href="{{ route('perfumes.index') }}"
               class="text-xl font-bold tracking-[0.18em]">
                PERFUME
            </a>

            <nav class="flex items-center gap-6 text-sm">
                <a href="{{ route('perfumes.index') }}"
                   class="transition hover:opacity-60">
                    Collection
                </a>

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="transition hover:opacity-60">
                        Account
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="transition hover:opacity-60">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="transition hover:opacity-60">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                       class="transition hover:opacity-60">
                        Register
                    </a>
                @endauth
            </nav>

        </div>
    </header>


    <main class="mx-auto max-w-7xl px-6 py-12 lg:px-10 lg:py-20">

        {{-- Back to collection --}}
        <div class="mb-10">
            <a href="{{ route('perfumes.index') }}"
               class="text-sm uppercase tracking-[0.18em] text-black/60 transition hover:text-black">
                ← Back to Collection
            </a>
        </div>


       <section class="grid w-full grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-16">

    {{-- Product image --}}
    <div class="relative z-0 w-full min-w-0">

        <div class="w-full overflow-hidden bg-[#ebe9e2]">
            @if($perfume->image)

                <img
                    src="{{ asset('storage/' . $perfume->image) }}"
                    alt="{{ $perfume->name }}"
                    class="block h-auto w-full max-w-full object-cover"
                >

            @else

                <div class="flex aspect-[4/5] w-full items-center justify-center text-sm uppercase tracking-[0.2em] text-black/40">
                    No Image Available
                </div>

            @endif
        </div>

    </div>


    {{-- Product information --}}
    <div class="relative z-10 min-w-0 w-full">

        {{-- Category --}}
        @if($perfume->category)
            <p class="mb-5 text-xs uppercase tracking-[0.25em] text-black/50">
                {{ $perfume->category->name }}
            </p>
        @endif


        {{-- Brand --}}
        <p class="mb-3 text-sm uppercase tracking-[0.2em] text-black/50">
            {{ $perfume->brand }}
        </p>


        {{-- Name --}}
        <h1 class="break-words text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl">
            {{ $perfume->name }}
        </h1>


        {{-- Price --}}
        <p class="mt-6 text-2xl font-medium">
            LKR {{ number_format($perfume->price, 2) }}
        </p>


        {{-- Size --}}
        @if($perfume->size)
            <p class="mt-2 text-sm text-black/50">
                {{ $perfume->size }}
            </p>
        @endif


        {{-- Description --}}
        @if($perfume->description)
            <div class="mt-10 border-t border-black/10 pt-8">

                <h2 class="text-xs font-bold uppercase tracking-[0.2em]">
                    Description
                </h2>

                <p class="mt-4 text-base leading-8 text-black/70">
                    {{ $perfume->description }}
                </p>

            </div>
        @endif


        {{-- Fragrance notes --}}
        <div class="mt-10 border-t border-black/10 pt-8">

            <h2 class="text-xs font-bold uppercase tracking-[0.2em]">
                Fragrance Notes
            </h2>

            <div class="mt-6 space-y-6">

                @if($perfume->top_notes)
                    <div>
                        <h3 class="text-sm font-semibold">
                            Top Notes
                        </h3>

                        <p class="mt-2 leading-7 text-black/60">
                            {{ $perfume->top_notes }}
                        </p>
                    </div>
                @endif


                @if($perfume->middle_notes)
                    <div>
                        <h3 class="text-sm font-semibold">
                            Middle Notes
                        </h3>

                        <p class="mt-2 leading-7 text-black/60">
                            {{ $perfume->middle_notes }}
                        </p>
                    </div>
                @endif


                @if($perfume->base_notes)
                    <div>
                        <h3 class="text-sm font-semibold">
                            Base Notes
                        </h3>

                        <p class="mt-2 leading-7 text-black/60">
                            {{ $perfume->base_notes }}
                        </p>
                    </div>
                @endif

            </div>

        </div>


        {{-- Stock --}}
        <div class="mt-10 border-t border-black/10 pt-8">

            @if($perfume->stock > 0)

                <p class="text-sm text-black/60">
                    <span class="font-semibold text-black">
                        In stock
                    </span>
                    — {{ $perfume->stock }} available
                </p>

            @else

                <p class="text-sm font-semibold text-red-700">
                    Currently out of stock
                </p>

            @endif

        </div>


        {{-- Order via WhatsApp --}}
        <div class="mt-8">

            @php
                $whatsappMessage = "Hello! I would like to order:\n\n"
                    . "Product: " . $perfume->name . "\n"
                    . "Brand: " . $perfume->brand . "\n"
                    . "Size: " . $perfume->size . "\n"
                    . "Price: LKR " . number_format($perfume->price, 2) . "\n\n"
                    . "Please let me know how I can proceed with the order.";
            @endphp

            @if($perfume->stock > 0)

                <a
                    href="https://wa.me/94760720078?text={{ urlencode($whatsappMessage) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    style="display:flex; width:100%; align-items:center; justify-content:center; background-color:#25D366; color:white; padding:16px 24px; font-size:14px; font-weight:600; text-transform:uppercase; letter-spacing:0.15em; text-decoration:none;"
                >
                    Order via WhatsApp
                </a>

            @else

                <button
                    disabled
                    class="w-full cursor-not-allowed bg-black/10 px-6 py-4 text-sm font-semibold uppercase tracking-[0.15em] text-black/40"
                >
                    Out of Stock
                </button>

            @endif

        </div>


        {{-- Status --}}
        <div class="mt-8">

            <span class="inline-block border border-black/10 px-4 py-2 text-xs uppercase tracking-[0.15em]">
                {{ $perfume->is_active ? 'Available' : 'Unavailable' }}
            </span>

        </div>

    </div>

</section>




    </main>


    <footer class="mt-20 border-t border-black/10">
        <div class="mx-auto max-w-7xl px-6 py-8 lg:px-10">
            <p class="text-xs uppercase tracking-[0.18em] text-black/40">
                © {{ date('Y') }} Perfume. All rights reserved.
            </p>
        </div>
    </footer>

</body>
</html>