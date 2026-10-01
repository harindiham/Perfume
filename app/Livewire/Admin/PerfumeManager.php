<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Perfume;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class PerfumeManager extends Component
{
    use WithFileUploads;

    public $showForm = false;

    public $editingId = null;

    public $name = '';
    public $brand = '';
    public $category_id = '';
    public $price = '';
    public $size = '';
    public $description = '';
    public $top_notes = '';
    public $middle_notes = '';
    public $base_notes = '';

    // Existing image path
    public $image = '';

    // New uploaded image
    public $imageUpload = null;

    public $stock = 0;
    public $is_active = true;


    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'size' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'top_notes' => 'nullable|string',
            'middle_notes' => 'nullable|string',
            'base_notes' => 'nullable|string',

            // Existing database image path
            'image' => 'nullable|string|max:255',

            // New uploaded image
            'imageUpload' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];
    }


    public function create()
    {
        $this->resetForm();

        $this->showForm = true;
    }


    public function edit($id)
    {
        $perfume = Perfume::findOrFail($id);

        $this->editingId = $perfume->id;

        $this->name = $perfume->name;
        $this->brand = $perfume->brand;
        $this->category_id = $perfume->category_id;
        $this->price = $perfume->price;
        $this->size = $perfume->size;
        $this->description = $perfume->description;
        $this->top_notes = $perfume->top_notes;
        $this->middle_notes = $perfume->middle_notes;
        $this->base_notes = $perfume->base_notes;

        // Keep the existing image path
        $this->image = $perfume->image;

        // Reset upload field when editing
        $this->imageUpload = null;

        $this->stock = $perfume->stock;
        $this->is_active = $perfume->is_active;

        $this->showForm = true;

        $this->dispatch('scroll-to-form');
    }


    public function save()
    {
        $validated = $this->validate();

        /*
        |--------------------------------------------------------------------------
        | Upload New Image
        |--------------------------------------------------------------------------
        */

        if ($this->imageUpload) {

            // If editing and an old local image exists, delete it
            if ($this->editingId) {

                $existingPerfume = Perfume::find($this->editingId);

                if (
                    $existingPerfume &&
                    $existingPerfume->image &&
                    !filter_var($existingPerfume->image, FILTER_VALIDATE_URL)
                ) {
                    Storage::disk('public')->delete($existingPerfume->image);
                }
            }

            // Store the new image
            $validated['image'] = $this->imageUpload->store('perfumes', 'public');
        }

        // Remove the temporary upload object before database operation
        unset($validated['imageUpload']);


        /*
        |--------------------------------------------------------------------------
        | Update Existing Perfume
        |--------------------------------------------------------------------------
        */

        if ($this->editingId) {

            $perfume = Perfume::findOrFail($this->editingId);

            $perfume->update($validated);

            session()->flash(
                'message',
                'Perfume updated successfully.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Create New Perfume
        |--------------------------------------------------------------------------
        */

        else {

            Perfume::create($validated);

            session()->flash(
                'message',
                'Perfume added successfully.'
            );
        }


        $this->resetForm();
    }


    public function delete($id)
    {
        $perfume = Perfume::findOrFail($id);

        // Delete local image if one exists
        if (
            $perfume->image &&
            !filter_var($perfume->image, FILTER_VALIDATE_URL)
        ) {
            Storage::disk('public')->delete($perfume->image);
        }

        $perfume->delete();

        session()->flash(
            'message',
            'Perfume deleted successfully.'
        );
    }


    public function cancel()
    {
        $this->resetForm();
    }


    private function resetForm()
    {
        $this->reset([
            'editingId',
            'name',
            'brand',
            'category_id',
            'price',
            'size',
            'description',
            'top_notes',
            'middle_notes',
            'base_notes',
            'image',
            'imageUpload',
            'stock',
        ]);

        $this->is_active = true;

        $this->showForm = false;
    }


    public function render()
    {
        $perfumes = Perfume::with('category')
            ->latest()
            ->get();

        $categories = Category::orderBy('name')->get();

        return view('livewire.admin.perfume-manager', [
            'perfumes' => $perfumes,
            'categories' => $categories,
        ]);
    }
}