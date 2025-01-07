<?php

namespace App\Livewire;

use App\Models\FaqCategory;
use Livewire\Component;
use Livewire\Attributes\Url;

class Faq extends Component
{
    #[Url]
    public $search = '';

    #[Url]
    public $selectedCategory = null;

    public function getQueriedFaqsProperty()
    {
        $query = FaqCategory::with(['faqs' => function ($query) {
            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('question', 'like', '%' . $this->search . '%')
                      ->orWhere('answer', 'like', '%' . $this->search . '%');
                });
            }
        }])->orderBy('order');

        if ($this->selectedCategory) {
            $query->where('id', $this->selectedCategory);
        }

        return $query->get();
    }

    public function render()
    {
        return view('livewire.faq', [
            'faqCategories' => $this->queriedFaqs,
        ])->layout('layouts.app', [
            'header' => 'Frequently Asked Questions'
        ]);
    }
}
