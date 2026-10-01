<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;

class CategoryManager extends Component
{
    public $showForm = false;

    public $editingId = null;

    public $name = '';

    public $description = '';


    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $this->editingId,
            'description' => 'nullable|string',
        ];
    }


    public function create()
    {
        $this->resetForm();

        $this->showForm = true;
    }


    public function edit($id)
    {
        $category = Category::findOrFail($id);

        $this->editingId = $category->id;

        $this->name = $category->name;
        $this->description = $category->description;

        $this->showForm = true;
    }


    public function save()
    {
        $validated = $this->validate();

        if ($this->editingId) {

            $category = Category::findOrFail($this->editingId);

            $category->update($validated);

            session()->flash(
                'message',
                'Category updated successfully.'
            );
        }

        else {

            Category::create($validated);

            session()->flash(
                'message',
                'Category added successfully.'
            );
        }

        $this->resetForm();
    }


    public function delete($id)
    {
        $category = Category::findOrFail($id);

        if ($category->perfumes()->exists()) {

            session()->flash(
                'error',
                'This category cannot be deleted because it contains perfumes.'
            );

            return;
        }

        $category->delete();

        session()->flash(
            'message',
            'Category deleted successfully.'
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
            'description',
        ]);

        $this->showForm = false;
    }


    public function render()
    {
        $categories = Category::withCount('perfumes')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.category-manager', [
            'categories' => $categories,
        ]);
    }
}