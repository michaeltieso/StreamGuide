<?php

namespace App\Livewire\Admin;

use App\Models\GuideCategory;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GuideCategoryManager extends AdminComponent
{
    public $categories;
    public $editingCategory = false;
    public $name;
    public $description;
    public $order;
    public $currentCategory;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'order' => 'required|integer|min:0',
    ];

    protected function adminMount(...$args): void
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = GuideCategory::orderBy('order')->get();
    }

    public function createCategory()
    {
        $this->resetForm();
        $this->editingCategory = true;
    }

    public function editCategory(GuideCategory $category)
    {
        $this->currentCategory = $category;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->order = $category->order;
        $this->editingCategory = true;
    }

    public function saveCategory()
    {
        $this->validate();

        if ($this->currentCategory) {
            $this->currentCategory->update([
                'name' => $this->name,
                'description' => $this->description,
                'order' => $this->order,
            ]);
        } else {
            GuideCategory::create([
                'name' => $this->name,
                'description' => $this->description,
                'order' => $this->order,
            ]);
        }

        $this->resetForm();
        $this->loadCategories();
        session()->flash('message', 'Category saved successfully.');
    }

    public function deleteCategory(GuideCategory $category)
    {
        // Check if category has guides
        if ($category->guides()->count() > 0) {
            session()->flash('error', 'Cannot delete category that contains guides. Please move or delete the guides first.');
            return;
        }

        $category->delete();
        $this->loadCategories();
        session()->flash('message', 'Category deleted successfully.');
    }

    public function resetForm()
    {
        $this->currentCategory = null;
        $this->name = '';
        $this->description = '';
        $this->order = 0;
        $this->editingCategory = false;
    }

    public function render(): View
    {
        return view('livewire.admin.guide-category-manager');
    }
} 