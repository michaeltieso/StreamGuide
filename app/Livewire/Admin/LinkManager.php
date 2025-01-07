<?php

namespace App\Livewire\Admin;

use App\Models\Link;
use Illuminate\View\View;

class LinkManager extends AdminComponent
{
    public $links;
    public $editingLink = false;
    public $title;
    public $url;
    public $order;
    public $currentLink;

    protected $rules = [
        'title' => 'required|string|max:255',
        'url' => 'required|url',
        'order' => 'required|integer|min:0',
    ];

    protected function adminMount(...$args): void
    {
        $this->loadLinks();
    }

    public function loadLinks()
    {
        $this->links = Link::orderBy('order')->get();
    }

    public function createLink()
    {
        $this->resetForm();
        $this->editingLink = true;
    }

    public function editLink(Link $link)
    {
        $this->currentLink = $link;
        $this->title = $link->title;
        $this->url = $link->url;
        $this->order = $link->order;
        $this->editingLink = true;
    }

    public function saveLink()
    {
        $this->validate();

        if ($this->currentLink) {
            $this->currentLink->update([
                'title' => $this->title,
                'url' => $this->url,
                'order' => $this->order,
            ]);
        } else {
            Link::create([
                'title' => $this->title,
                'url' => $this->url,
                'order' => $this->order,
            ]);
        }

        $this->resetForm();
        $this->loadLinks();
        session()->flash('message', 'Link saved successfully.');
    }

    public function deleteLink(Link $link)
    {
        $link->delete();
        $this->loadLinks();
        session()->flash('message', 'Link deleted successfully.');
    }

    public function resetForm()
    {
        $this->currentLink = null;
        $this->title = '';
        $this->url = '';
        $this->order = 0;
        $this->editingLink = false;
    }

    public function render(): View
    {
        return view('livewire.admin.link-manager');
    }
}
