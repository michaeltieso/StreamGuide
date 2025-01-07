<?php

namespace App\Livewire\Admin;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\View\View;

class FaqManager extends AdminComponent
{
    public $faqs;
    public $editingFaq = false;
    public $question;
    public $answer;
    public $order;
    public $currentFaq;
    public $faq_category_id;
    public $faqCategories;

    protected $rules = [
        'question' => 'required|string|max:255',
        'answer' => 'required|string',
        'order' => 'required|integer|min:0',
        'faq_category_id' => 'required|exists:faq_categories,id'
    ];

    protected function adminMount(...$args): void
    {
        $this->loadFaqs();
        $this->loadFaqCategories();
    }

    public function loadFaqs()
    {
        $this->faqs = Faq::with('category')->orderBy('order')->get();
    }

    public function loadFaqCategories()
    {
        $this->faqCategories = FaqCategory::orderBy('order')->get();
        
        // If no category is selected and categories exist, select the first one
        if (!$this->faq_category_id && $this->faqCategories->isNotEmpty()) {
            $this->faq_category_id = $this->faqCategories->first()->id;
        }
    }

    public function createFaq()
    {
        $this->resetForm();
        $this->editingFaq = true;
    }

    public function editFaq(Faq $faq)
    {
        $this->currentFaq = $faq;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->order = $faq->order;
        $this->faq_category_id = $faq->faq_category_id;
        $this->editingFaq = true;
    }

    public function saveFaq()
    {
        $this->validate();

        if ($this->currentFaq) {
            $this->currentFaq->update([
                'question' => $this->question,
                'answer' => $this->answer,
                'order' => $this->order,
                'faq_category_id' => $this->faq_category_id
            ]);
        } else {
            Faq::create([
                'question' => $this->question,
                'answer' => $this->answer,
                'order' => $this->order,
                'faq_category_id' => $this->faq_category_id
            ]);
        }

        $this->resetForm();
        $this->loadFaqs();
        session()->flash('message', 'FAQ saved successfully.');
    }

    public function deleteFaq(Faq $faq)
    {
        $faq->delete();
        $this->loadFaqs();
        session()->flash('message', 'FAQ deleted successfully.');
    }

    public function resetForm()
    {
        $this->currentFaq = null;
        $this->question = '';
        $this->answer = '';
        $this->order = 0;
        $this->editingFaq = false;
        
        // Keep the category_id if categories exist
        if ($this->faqCategories->isEmpty()) {
            $this->faq_category_id = null;
        }
    }

    public function render(): View
    {
        return view('livewire.admin.faq-manager');
    }
}
