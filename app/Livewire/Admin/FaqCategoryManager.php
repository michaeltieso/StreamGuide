<?php

namespace App\Livewire\Admin;

use App\Models\FaqCategory;
use App\Models\GuideCategory;
use Illuminate\View\View;

class FaqCategoryManager extends AdminComponent
{
    public $faqCategories;
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
        $this->loadFaqCategories();
    }

    protected function loadFaqCategories()
    {
        $this->faqCategories = FaqCategory::with(['faqs' => function($query) {
            $query->orderBy('order');
        }])
        ->orderBy('order')
        ->get();
    }

    public function createCategory()
    {
        $this->resetForm();
        $this->editingCategory = true;
    }

    public function editCategory(FaqCategory $category)
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
            FaqCategory::create([
                'name' => $this->name,
                'description' => $this->description,
                'order' => $this->order,
            ]);
        }

        $this->resetForm();
        $this->loadFaqCategories();
        session()->flash('message', 'Category saved successfully.');
    }

    public function deleteCategory(FaqCategory $category)
    {
        // Check if category has FAQs
        if ($category->faqs()->count() > 0) {
            session()->flash('error', 'Cannot delete category that contains FAQs. Please move or delete the FAQs first.');
            return;
        }

        $category->delete();
        $this->loadFaqCategories();
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
        return view('livewire.admin.faq-category-manager', [
            'categories' => $this->faqCategories
        ]);
    }
} 