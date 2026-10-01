<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>{{ $category->name }} — Perfume</title>

    <meta
        name="description"
        content="Explore our {{ strtolower($category->name) }} perfume collection."
    >

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


                    @foreach ($categories as $navCategory)

                        <a
                            href="{{ route('perfumes.category', ['id' => $navCategory->id]) }}"
                            class="text-[10px] uppercase tracking-[0.2em] transition hover:opacity-50
                            {{ $navCategory->id === $category->id
                                ? 'border-b border-black pb-1'
                                : '' }}">

                            {{ $navCategory->name }}

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



    {{-- Category Header --}}
    <main>


        <section class="border-b border-black/10">

            <div class="mx-auto max-w-[1600px] px-6 py-20 lg:px-12 lg:py-32">


                <p class="text-[10px] uppercase tracking-[0.3em] text-black/40">
                    Perfume Collection
                </p>


                <h1 class="mt-6 text-6xl font-light leading-none tracking-[-0.06em] sm:text-7xl lg:text-8xl">
                    {{ $category->name }}
                </h1>


                @if ($category->description)

                    <p class="mt-8 max-w-xl text-sm leading-7 text-black/50">
                        {{ $category->description }}
                    </p>

                @else

                    <p class="mt-8 max-w-xl text-sm leading-7 text-black/50">
                        Explore our curated collection of
                        {{ strtolower($category->name) }} fragrances.
                    </p>

                @endif

            </div>

        </section>



        {{-- Products --}}
        <section class="mx-auto max-w-[1600px] px-6 py-12 lg:px-12 lg:py-20">


            <div class="mb-10 flex items-end justify-between">

                <div>

                    <p class="text-[10px] uppercase tracking-[0.3em] text-black/40">
                        Collection
                    </p>

                    <h2 class="mt-3 text-3xl font-light tracking-[-0.04em]">
                        {{ $category->name }}
                    </h2>

                </div>


                <p class="text-[9px] uppercase tracking-[0.2em] text-black/35">

                    {{ $perfumes->count() }}

                    {{ $perfumes->count() === 1 ? 'Perfume' : 'Perfumes' }}

                </p>

            </div>



            @if ($perfumes->count())

                <div class="grid grid-cols-1 gap-px bg-black/10 sm:grid-cols-2 lg:grid-cols-4">


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
                                    {{ $perfume->brand }}
                                </p>


                                <h3 class="mt-4 text-2xl font-light tracking-[-0.04em]">
                                    {{ $perfume->name }}
                                </h3>


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
                        There are currently no active perfumes in this category.
                    </p>


                    <a
                        href="{{ route('perfumes.index') }}"
                        class="mt-8 inline-block border border-black/20 px-7 py-3 text-[9px] uppercase tracking-[0.2em] transition hover:bg-black hover:text-white">

                        View Collection

                    </a>

                </div>

            @endif

        </section>



        {{-- Back to Collection --}}
        <section class="border-t border-black/10">

            <div class="mx-auto max-w-[1600px] px-6 py-12 lg:px-12">

                <a
                    href="{{ route('perfumes.index') }}"
                    class="text-[9px] uppercase tracking-[0.25em] transition hover:opacity-50">

                    ← Back to Collection

                </a>

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