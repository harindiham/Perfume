<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Perfume — Collection</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="bg-white text-black antialiased">


    {{-- Navigation --}}
    <header class="border-b border-black/10">

        <div class="mx-auto max-w-[1600px] px-6 lg:px-12">

            <div class="flex min-h-[80px] items-center justify-between gap-8">


                {{-- Logo --}}
                <a
                    href="{{ route('perfumes.index') }}"
                    class="shrink-0 text-lg font-medium tracking-[0.25em]">
                    PERFUME
                </a>


                {{-- Navigation --}}
                <nav class="hidden items-center gap-7 lg:flex">

                    <a
                        href="{{ route('perfumes.index') }}"
                        class="text-[10px] uppercase tracking-[0.2em] transition hover:opacity-50">
                        Collection
                    </a>


                    @foreach ($categories as $category)

                        <a
                            href="{{ route('perfumes.category', ['id' => $category->id]) }}"
                            class="text-[10px] uppercase tracking-[0.2em] transition hover:opacity-50">
                            {{ $category->name }}
                        </a>

                    @endforeach

                </nav>


                {{-- Right Side --}}
                <div class="flex items-center gap-5">

                    <a
                        href="{{ route('login') }}"
                        class="text-[10px] uppercase tracking-[0.2em] transition hover:opacity-50">
                        Login
                    </a>


                    @auth

                        @if (auth()->user()->is_admin)

                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="hidden text-[10px] uppercase tracking-[0.2em] transition hover:opacity-50 sm:block">
                                Admin
                            </a>

                        @endif

                    @endauth

                </div>

            </div>

        </div>

    </header>



    {{-- Hero --}}
    <main>

        <section class="mx-auto max-w-[1600px] px-6 lg:px-12">

            <div class="grid min-h-[650px] grid-cols-1 lg:grid-cols-2">


                {{-- Hero Text --}}
                <div class="flex items-center py-20 lg:py-32">

                    <div class="max-w-xl">

                        <p class="text-[10px] uppercase tracking-[0.3em] text-black/45">
                            The Collection
                        </p>


                        <h1 class="mt-6 text-6xl font-light leading-[0.95] tracking-[-0.06em] sm:text-7xl lg:text-8xl">

                            A study in
                            <br>
                            fragrance.

                        </h1>


                        <p class="mt-8 max-w-md text-sm leading-7 text-black/55">

                            Explore a curated collection of fragrances,
                            from contemporary compositions to timeless
                            perfume classics.

                        </p>


                        <a
                            href="#catalogue"
                            class="mt-10 inline-block bg-black px-8 py-4 text-[10px] uppercase tracking-[0.25em] text-white transition hover:bg-black/80">

                            Explore Collection

                        </a>

                    </div>

                </div>



                {{-- Hero Image --}}
                <div class="flex min-h-[500px] items-center justify-center bg-neutral-100 lg:min-h-[650px]">

                    <div class="text-center">

                        <p class="text-[10px] uppercase tracking-[0.3em] text-black/25">
                            Perfume
                        </p>


                        <p class="mt-4 text-7xl font-light tracking-[-0.07em] text-black/10 lg:text-9xl">
                            SCENT
                        </p>

                    </div>

                </div>

            </div>

        </section>



        {{-- Catalogue Introduction --}}
        <section
            id="catalogue"
            class="border-y border-black/10">

            <div class="mx-auto max-w-[1600px] px-6 py-16 lg:px-12 lg:py-24">

                <div class="flex flex-col justify-between gap-8 md:flex-row md:items-end">


                    <div>

                        <p class="text-[10px] uppercase tracking-[0.3em] text-black/45">
                            Catalogue
                        </p>


                        <h2 class="mt-4 text-4xl font-light tracking-[-0.05em] lg:text-6xl">
                            The collection
                        </h2>

                    </div>


                    <p class="max-w-sm text-sm leading-6 text-black/50">

                        Discover fragrances organised across our
                        collection of men's, women's, designer,
                        Arabic and unisex perfumes.

                    </p>

                </div>

            </div>

        </section>



        {{-- Products --}}
        <section class="mx-auto max-w-[1600px] px-6 py-12 lg:px-12 lg:py-20">

            @if ($perfumes->count())

                <div class="grid grid-cols-1 gap-px bg-black/10 sm:grid-cols-2 lg:grid-cols-3">


                    @foreach ($perfumes as $perfume)

                        <article class="group bg-white">


                            {{-- Image --}}
                            <div class="flex aspect-square items-center justify-center overflow-hidden bg-neutral-100">

                                @if ($perfume->image)

                                    <img
                                        src="{{ filter_var($perfume->image, FILTER_VALIDATE_URL)
                                            ? $perfume->image
                                            : asset('storage/' . $perfume->image) }}"
                                        alt="{{ $perfume->name }}"
                                        class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]">

                                @else

                                    <div class="text-center">

                                        <p class="text-[9px] uppercase tracking-[0.3em] text-black/25">
                                            {{ $perfume->brand }}
                                        </p>


                                        <p class="mt-3 text-4xl font-light tracking-[-0.05em] text-black/10">
                                            {{ $perfume->name }}
                                        </p>

                                    </div>

                                @endif

                            </div>



                            {{-- Product Information --}}
                            <div class="p-7 lg:p-8">

                                <p class="text-[9px] uppercase tracking-[0.25em] text-black/40">
                                    {{ $perfume->category->name }}
                                </p>


                                <h3 class="mt-4 text-2xl font-light tracking-[-0.04em]">
                                    {{ $perfume->name }}
                                </h3>


                                <p class="mt-1 text-xs text-black/45">
                                    {{ $perfume->brand }}
                                </p>



                                <div class="mt-7 flex items-end justify-between">

                                    <div>

                                        <p class="text-sm">
                                            {{ number_format($perfume->price, 2) }}
                                        </p>


                                        @if ($perfume->size)

                                            <p class="mt-1 text-[9px] uppercase tracking-[0.2em] text-black/35">
                                                {{ $perfume->size }}
                                            </p>

                                        @endif

                                    </div>



                                    @if ($perfume->stock > 0)

                                        <p class="text-[9px] uppercase tracking-[0.2em] text-black/40">
                                            In Stock
                                        </p>

                                    @else

                                        <p class="text-[9px] uppercase tracking-[0.2em] text-black/30">
                                            Out of Stock
                                        </p>

                                    @endif

                                </div>



                                {{-- View Product --}}
                                <a
                                    href="{{ route('perfumes.show', ['id' => $perfume->id]) }}"
                                    class="mt-7 block border-t border-black/10 pt-5 text-[9px] uppercase tracking-[0.25em] transition hover:opacity-50">

                                    View Product

                                </a>

                            </div>

                        </article>

                    @endforeach

                </div>

            @else

                <div class="border border-black/10 py-32 text-center">

                    <p class="text-[10px] uppercase tracking-[0.3em] text-black/35">
                        Collection Empty
                    </p>


                    <h2 class="mt-5 text-3xl font-light tracking-[-0.04em]">
                        No perfumes available
                    </h2>


                    <p class="mx-auto mt-4 max-w-md text-sm text-black/45">

                        The perfume collection is currently being prepared.

                    </p>

                </div>

            @endif

        </section>



        {{-- Categories --}}
        <section class="border-t border-black/10">

            <div class="mx-auto max-w-[1600px] px-6 py-16 lg:px-12 lg:py-24">


                <div class="mb-12">

                    <p class="text-[10px] uppercase tracking-[0.3em] text-black/40">
                        Explore
                    </p>


                    <h2 class="mt-4 text-4xl font-light tracking-[-0.05em]">
                        Categories
                    </h2>

                </div>



                <div class="grid grid-cols-1 border-l border-t border-black/10 sm:grid-cols-2 lg:grid-cols-5">


                    @foreach ($categories as $category)

                        <a
                            href="{{ route('perfumes.category', ['id' => $category->id]) }}"
                            class="group border-b border-r border-black/10 p-7 transition hover:bg-black hover:text-white lg:p-8">


                            <p class="text-[9px] uppercase tracking-[0.25em] opacity-40">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </p>


                            <h3 class="mt-12 text-lg font-light">
                                {{ $category->name }}
                            </h3>


                            <p class="mt-3 text-[10px] uppercase tracking-[0.15em] opacity-40">

                                {{ $category->perfumes_count }}

                                {{ $category->perfumes_count === 1 ? 'Perfume' : 'Perfumes' }}

                            </p>

                        </a>

                    @endforeach

                </div>

            </div>

        </section>

    </main>



    {{-- Footer --}}
    <footer class="border-t border-black/10">

        <div class="mx-auto flex max-w-[1600px] flex-col justify-between gap-8 px-6 py-10 sm:flex-row sm:items-center lg:px-12">


            <p class="text-[10px] uppercase tracking-[0.25em]">
                PERFUME
            </p>


            <p class="text-[9px] uppercase tracking-[0.2em] text-black/35">
                A curated fragrance catalogue
            </p>

        </div>

    </footer>


</body>

</html>