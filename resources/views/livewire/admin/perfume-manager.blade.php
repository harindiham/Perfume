<div class="p-6 lg:p-12">

    {{-- Header --}}
    <div class="mb-10 flex items-end justify-between border-b border-black/15 pb-6">

        <div>
            <p class="text-[10px] uppercase tracking-[0.25em] text-black/50">
                Catalogue
            </p>

            <h1 class="mt-3 text-4xl font-light tracking-[-0.04em]">
                Perfumes
            </h1>
        </div>

        @if (!$showForm)
            <button
                type="button"
                wire:click="create"
                class="bg-black px-6 py-3 text-[10px] uppercase tracking-[0.2em] text-white transition hover:bg-black/80">
                Add Perfume
            </button>
        @endif

    </div>


    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="mb-8 border border-black/15 bg-neutral-50 px-5 py-4">

            <p class="text-[10px] uppercase tracking-[0.2em] text-black/70">
                {{ session('message') }}
            </p>

        </div>
    @endif


    {{-- Add / Edit Form --}}
    @if ($showForm)

        <div class="mb-12 border border-black/15 bg-white">

            <div class="border-b border-black/15 px-6 py-5 lg:px-8">

                <p class="text-[9px] uppercase tracking-[0.25em] text-black/40">
                    Catalogue Management
                </p>

                <h2 class="mt-2 text-2xl font-light tracking-[-0.03em]">
                    {{ $editingId ? 'Edit Perfume' : 'Add Perfume' }}
                </h2>

            </div>


            <form wire:submit="save" class="p-6 lg:p-8">

                {{-- Validation Errors --}}
                @if ($errors->any())

                    <div class="mb-8 border border-red-200 bg-red-50 px-5 py-4">

                        <p class="mb-2 text-[10px] uppercase tracking-[0.2em] text-red-700">
                            Please correct the following
                        </p>

                        <ul class="space-y-1 text-xs text-red-600">

                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>

                @endif


                {{-- Basic Information --}}
                <div class="mb-10">

                    <div class="mb-6 border-b border-black/10 pb-3">

                        <p class="text-[9px] uppercase tracking-[0.25em] text-black/40">
                            Basic Information
                        </p>

                    </div>


                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        {{-- Name --}}
                        <div>

                            <label class="text-[9px] uppercase tracking-[0.2em] text-black/50">
                                Perfume Name
                            </label>

                            <input
                                type="text"
                                wire:model="name"
                                placeholder="e.g. Blanche"
                                class="mt-2 w-full border-0 border-b border-black/20 bg-transparent px-0 py-3 text-sm outline-none focus:border-black focus:ring-0">

                            @error('name')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Brand --}}
                        <div>

                            <label class="text-[9px] uppercase tracking-[0.2em] text-black/50">
                                Brand
                            </label>

                            <input
                                type="text"
                                wire:model="brand"
                                placeholder="e.g. BYREDO"
                                class="mt-2 w-full border-0 border-b border-black/20 bg-transparent px-0 py-3 text-sm outline-none focus:border-black focus:ring-0">

                            @error('brand')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Category --}}
                        <div>

                            <label class="text-[9px] uppercase tracking-[0.2em] text-black/50">
                                Category
                            </label>

                            <select
                                wire:model="category_id"
                                class="mt-2 w-full border-0 border-b border-black/20 bg-transparent px-0 py-3 text-sm outline-none focus:border-black focus:ring-0">

                                <option value="">Select category</option>

                                @foreach ($categories as $category)

                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('category_id')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Price --}}
                        <div>

                            <label class="text-[9px] uppercase tracking-[0.2em] text-black/50">
                                Price
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                wire:model="price"
                                placeholder="0.00"
                                class="mt-2 w-full border-0 border-b border-black/20 bg-transparent px-0 py-3 text-sm outline-none focus:border-black focus:ring-0">

                            @error('price')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Size --}}
                        <div>

                            <label class="text-[9px] uppercase tracking-[0.2em] text-black/50">
                                Size
                            </label>

                            <input
                                type="text"
                                wire:model="size"
                                placeholder="e.g. 50ml"
                                class="mt-2 w-full border-0 border-b border-black/20 bg-transparent px-0 py-3 text-sm outline-none focus:border-black focus:ring-0">

                            @error('size')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Stock --}}
                        <div>

                            <label class="text-[9px] uppercase tracking-[0.2em] text-black/50">
                                Stock
                            </label>

                            <input
                                type="number"
                                min="0"
                                wire:model="stock"
                                placeholder="0"
                                class="mt-2 w-full border-0 border-b border-black/20 bg-transparent px-0 py-3 text-sm outline-none focus:border-black focus:ring-0">

                            @error('stock')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- Product Image --}}
                <div class="mb-10">

                    <div class="mb-6 border-b border-black/10 pb-3">

                        <p class="text-[9px] uppercase tracking-[0.25em] text-black/40">
                            Product Image
                        </p>

                    </div>


                    <div>

                        <label class="text-[9px] uppercase tracking-[0.2em] text-black/50">
                            Upload Image
                        </label>

                        <div class="mt-3">

                            <input
                                type="file"
                                wire:model="imageUpload"
                                accept="image/jpeg,image/png,image/webp"
                                class="block w-full cursor-pointer border border-black/15 bg-white p-3 text-xs">

                        </div>


                        {{-- Uploading Message --}}
                        <div
                            wire:loading
                            wire:target="imageUpload"
                            class="mt-3 text-[10px] uppercase tracking-[0.15em] text-black/50">

                            Uploading image...

                        </div>


                        {{-- New Image Preview --}}
                        @if ($imageUpload)

                            <div class="mt-5">

                                <p class="mb-3 text-[9px] uppercase tracking-[0.2em] text-black/40">
                                    New Image Preview
                                </p>

                                <div class="h-64 w-64 overflow-hidden bg-neutral-100">

                                    <img
                                        src="{{ $imageUpload->temporaryUrl() }}"
                                        alt="New perfume image"
                                        class="h-full w-full object-cover">

                                </div>

                            </div>

                        {{-- Existing Image Preview --}}
                        @elseif ($image)

                            <div class="mt-5">

                                <p class="mb-3 text-[9px] uppercase tracking-[0.2em] text-black/40">
                                    Current Image
                                </p>

                                <div class="h-64 w-64 overflow-hidden bg-neutral-100">

                                    @if (filter_var($image, FILTER_VALIDATE_URL))

                                        <img
                                            src="{{ $image }}"
                                            alt="Current perfume image"
                                            class="h-full w-full object-cover">

                                    @else

                                        <img
                                            src="{{ asset('storage/' . $image) }}"
                                            alt="Current perfume image"
                                            class="h-full w-full object-cover">

                                    @endif

                                </div>

                            </div>

                        @endif


                        <p class="mt-3 text-[10px] text-black/40">
                            JPG, JPEG, PNG or WEBP. Maximum file size: 2MB.
                        </p>


                        @error('imageUpload')

                            <p class="mt-2 text-xs text-red-600">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>


                {{-- Description --}}
                <div class="mb-10">

                    <div class="mb-6 border-b border-black/10 pb-3">

                        <p class="text-[9px] uppercase tracking-[0.25em] text-black/40">
                            Product Description
                        </p>

                    </div>


                    <textarea
                        wire:model="description"
                        rows="5"
                        placeholder="Describe the perfume..."
                        class="w-full resize-none border border-black/15 bg-white p-4 text-sm outline-none focus:border-black focus:ring-0"></textarea>

                    @error('description')
                        <p class="mt-2 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Fragrance Notes --}}
                <div class="mb-10">

                    <div class="mb-6 border-b border-black/10 pb-3">

                        <p class="text-[9px] uppercase tracking-[0.25em] text-black/40">
                            Fragrance Notes
                        </p>

                    </div>


                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

                        {{-- Top Notes --}}
                        <div>

                            <label class="text-[9px] uppercase tracking-[0.2em] text-black/50">
                                Top Notes
                            </label>

                            <textarea
                                wire:model="top_notes"
                                rows="5"
                                placeholder="Bergamot, Lemon, Pink Pepper..."
                                class="mt-2 w-full resize-none border border-black/15 bg-white p-4 text-sm outline-none focus:border-black focus:ring-0"></textarea>

                            @error('top_notes')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Middle Notes --}}
                        <div>

                            <label class="text-[9px] uppercase tracking-[0.2em] text-black/50">
                                Middle Notes
                            </label>

                            <textarea
                                wire:model="middle_notes"
                                rows="5"
                                placeholder="Rose, Jasmine, Violet..."
                                class="mt-2 w-full resize-none border border-black/15 bg-white p-4 text-sm outline-none focus:border-black focus:ring-0"></textarea>

                            @error('middle_notes')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Base Notes --}}
                        <div>

                            <label class="text-[9px] uppercase tracking-[0.2em] text-black/50">
                                Base Notes
                            </label>

                            <textarea
                                wire:model="base_notes"
                                rows="5"
                                placeholder="Musk, Amber, Sandalwood..."
                                class="mt-2 w-full resize-none border border-black/15 bg-white p-4 text-sm outline-none focus:border-black focus:ring-0"></textarea>

                            @error('base_notes')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- Status --}}
                <div class="mb-10">

                    <div class="border-t border-black/10 pt-6">

                        <label class="flex items-center gap-3">

                            <input
                                type="checkbox"
                                wire:model="is_active"
                                class="h-4 w-4 border-black/30 text-black focus:ring-black">

                            <span class="text-[10px] uppercase tracking-[0.2em] text-black/60">
                                Product Active
                            </span>

                        </label>

                        <p class="mt-2 text-[10px] text-black/40">
                            Active perfumes can be displayed on the customer catalogue.
                        </p>

                    </div>

                </div>


                {{-- Form Actions --}}
                <div
                    style="display: flex; flex-direction: row; gap: 12px; border-top: 1px solid #ddd; padding-top: 24px; margin-top: 24px;">

                    <button
                        type="submit"
                        style="display: inline-block; background: #000; color: #fff; padding: 14px 28px; border: none; cursor: pointer; font-size: 10px; letter-spacing: 0.2em; text-transform: uppercase;">

                        {{ $editingId ? 'Update Perfume' : 'Save Perfume' }}

                    </button>


                    <button
                        type="button"
                        wire:click="cancel"
                        style="display: inline-block; background: #fff; color: #000; padding: 14px 28px; border: 1px solid #000; cursor: pointer; font-size: 10px; letter-spacing: 0.2em; text-transform: uppercase;">

                        Cancel

                    </button>

                </div>

            </form>

        </div>

    @endif


    {{-- Existing Perfumes --}}
    @if ($perfumes->count())

        <div class="mb-6 flex items-end justify-between">

            <div>

                <p class="text-[9px] uppercase tracking-[0.25em] text-black/40">
                    Catalogue Inventory
                </p>

                <h2 class="mt-2 text-2xl font-light tracking-[-0.03em]">
                    Existing Perfumes
                </h2>

            </div>

            <p class="text-[10px] uppercase tracking-[0.15em] text-black/40">
                {{ $perfumes->count() }}
                {{ $perfumes->count() === 1 ? 'Item' : 'Items' }}
            </p>

        </div>


<div class="grid grid-cols-1 gap-px bg-black/15 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($perfumes as $perfume)

                <div class="bg-white">

                    {{-- Image --}}
                    <div class="flex aspect-square items-center justify-center bg-neutral-100">

                        @if ($perfume->image)

                            <img
                                src="{{ filter_var($perfume->image, FILTER_VALIDATE_URL)
                                    ? $perfume->image
                                    : asset('storage/' . $perfume->image) }}"
                                alt="{{ $perfume->name }}"
                                class="h-full w-full object-cover">

                        @else

                            <span class="text-[10px] uppercase tracking-[0.2em] text-black/30">
                                No Image
                            </span>

                        @endif

                    </div>


                    {{-- Product Information --}}
                    <div class="p-6">

                        <p class="text-[9px] uppercase tracking-[0.2em] text-black/50">
                            {{ $perfume->category->name }}
                        </p>


                        <h2 class="mt-3 text-xl font-light tracking-[-0.02em]">
                            {{ $perfume->name }}
                        </h2>


                        <p class="mt-1 text-xs text-black/50">
                            {{ $perfume->brand }}
                        </p>


                        <div class="mt-6 flex items-end justify-between border-t border-black/10 pt-5">

                            {{-- Price --}}
                            <div>

                                <p class="text-sm">
                                    {{ number_format($perfume->price, 2) }}
                                </p>

                                @if ($perfume->size)

                                    <p class="mt-1 text-[10px] uppercase tracking-[0.15em] text-black/40">
                                        {{ $perfume->size }}
                                    </p>

                                @endif

                            </div>


                            {{-- Stock --}}
                            <div class="text-right">

                                <p class="text-[9px] uppercase tracking-[0.15em] text-black/40">
                                    Stock
                                </p>

                                <p class="mt-1 text-sm">
                                    {{ $perfume->stock }}
                                </p>

                            </div>

                        </div>


                        {{-- Edit / Delete --}}
                        <div class="mt-5 flex gap-2">

                            <button
                                type="button"
                                wire:click="edit({{ $perfume->id }})"
                                class="flex-1 border border-black/20 px-4 py-3 text-[9px] uppercase tracking-[0.2em] transition hover:bg-black hover:text-white">

                                Edit

                            </button>


                            <button
                                type="button"
                                wire:click="delete({{ $perfume->id }})"
                                wire:confirm="Are you sure you want to delete this perfume?"
                                class="flex-1 border border-black/20 px-4 py-3 text-[9px] uppercase tracking-[0.2em] transition hover:bg-black hover:text-white">

                                Delete

                            </button>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        {{-- Empty Catalogue --}}
        <div class="border border-black/15 py-24 text-center">

            <p class="text-[10px] uppercase tracking-[0.25em] text-black/40">
                Catalogue Empty
            </p>

            <h2 class="mt-4 text-2xl font-light tracking-[-0.03em]">
                No perfumes yet
            </h2>

            <p class="mt-3 text-sm text-black/50">
                Add your first perfume to begin building the catalogue.
            </p>

            <button
                type="button"
                wire:click="create"
                class="mt-8 bg-black px-6 py-3 text-[10px] uppercase tracking-[0.2em] text-white transition hover:bg-black/80">

                Add First Perfume

            </button>

        </div>

    @endif

</div>


@script

<script>

    $wire.on('scroll-to-form', () => {

        setTimeout(() => {

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

        }, 100);

    });

</script>

@endscript