<div>

    <div class="p-6 lg:p-12">

        <div class="mb-10 flex items-end justify-between border-b border-black/15 pb-6">

            <div>
                <p class="text-[10px] uppercase tracking-[0.25em] text-black/50">
                    Catalogue
                </p>

                <h1 class="mt-3 text-4xl font-light tracking-[-0.04em]">
                    Categories
                </h1>
            </div>

            @if (!$showForm)

                <button
                    type="button"
                    wire:click="create"
                    class="bg-black px-6 py-3 text-[10px] uppercase tracking-[0.2em] text-white transition hover:bg-black/80">
                    Add Category
                </button>

            @endif

        </div>


        @if (session('message'))

            <div class="mb-10 border border-black/15 bg-white px-6 py-5">

                <p class="text-[10px] uppercase tracking-[0.2em] text-black/60">
                    {{ session('message') }}
                </p>

            </div>

        @endif


        @if (session('error'))

            <div class="mb-10 border border-red-200 bg-white px-6 py-5">

                <p class="text-[10px] uppercase tracking-[0.2em] text-red-600">
                    {{ session('error') }}
                </p>

            </div>

        @endif


        @if ($showForm)

            <div class="mb-12 border border-black/15 bg-white">

                <div class="border-b border-black/15 px-6 py-5 lg:px-8">

                    <p class="text-[9px] uppercase tracking-[0.25em] text-black/40">
                        Category Management
                    </p>

                    <h2 class="mt-2 text-2xl font-light tracking-[-0.03em]">
                        {{ $editingId ? 'Edit Category' : 'Add Category' }}
                    </h2>

                </div>


                <form wire:submit="save" class="p-6 lg:p-8">

                    <div class="grid grid-cols-1 gap-6">

                        <div>

                            <label class="text-[9px] uppercase tracking-[0.2em] text-black/50">
                                Category Name
                            </label>

                            <input
                                type="text"
                                wire:model="name"
                                class="mt-3 block w-full border border-black/15 bg-white px-4 py-3 text-sm outline-none focus:border-black"
                                placeholder="e.g. Men's">

                            @error('name')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        <div>

                            <label class="text-[9px] uppercase tracking-[0.2em] text-black/50">
                                Description
                            </label>

                            <textarea
                                wire:model="description"
                                rows="4"
                                class="mt-3 block w-full resize-none border border-black/15 bg-white px-4 py-3 text-sm outline-none focus:border-black"
                                placeholder="Describe this perfume category..."></textarea>

                            @error('description')
                                <p class="mt-2 text-xs text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>


                    <div class="mt-8 flex flex-col gap-3 border-t border-black/10 pt-6 sm:flex-row">

                        <button
                            type="submit"
                            class="bg-black px-7 py-3 text-[10px] uppercase tracking-[0.2em] text-white transition hover:bg-black/80">
                            {{ $editingId ? 'Update Category' : 'Save Category' }}
                        </button>

                        <button
                            type="button"
                            wire:click="cancel"
                            class="border border-black/20 px-7 py-3 text-[10px] uppercase tracking-[0.2em] text-black transition hover:bg-black hover:text-white">
                            Cancel
                        </button>

                    </div>

                </form>

            </div>

        @endif


        <div>

            <div class="mb-6 flex items-end justify-between">

                <div>

                    <p class="text-[9px] uppercase tracking-[0.25em] text-black/40">
                        Catalogue Structure
                    </p>

                    <h2 class="mt-2 text-2xl font-light tracking-[-0.03em]">
                        Existing Categories
                    </h2>

                </div>

                <p class="text-[9px] uppercase tracking-[0.2em] text-black/40">
                    {{ $categories->count() }} Categories
                </p>

            </div>


            <div class="grid grid-cols-1 gap-px bg-black/15 sm:grid-cols-2 lg:grid-cols-3">

                @forelse ($categories as $category)

                    <div class="bg-white p-6">

                        <p class="text-[9px] uppercase tracking-[0.2em] text-black/40">
                            Category
                        </p>

                        <h3 class="mt-3 text-xl font-light tracking-[-0.02em]">
                            {{ $category->name }}
                        </h3>


                        @if ($category->description)

                            <p class="mt-3 text-xs leading-6 text-black/50">
                                {{ $category->description }}
                            </p>

                        @endif


                        <div class="mt-6 flex items-end justify-between border-t border-black/10 pt-5">

                            <div>

                                <p class="text-[9px] uppercase tracking-[0.15em] text-black/40">
                                    Perfumes
                                </p>

                                <p class="mt-1 text-sm">
                                    {{ $category->perfumes_count }}
                                </p>

                            </div>

                        </div>


                        <div class="mt-5 flex gap-2">

                            <button
                                type="button"
                                wire:click="edit({{ $category->id }})"
                                class="flex-1 border border-black/20 px-4 py-3 text-[9px] uppercase tracking-[0.2em] transition hover:bg-black hover:text-white">
                                Edit
                            </button>


                            <button
                                type="button"
                                wire:click="delete({{ $category->id }})"
                                wire:confirm="Are you sure you want to delete this category?"
                                class="flex-1 border border-black/20 px-4 py-3 text-[9px] uppercase tracking-[0.2em] transition hover:bg-black hover:text-white">
                                Delete
                            </button>

                        </div>

                    </div>

                @empty

                    <div class="col-span-full bg-white px-6 py-16 text-center">

                        <p class="text-[10px] uppercase tracking-[0.2em] text-black/40">
                            No categories found
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>