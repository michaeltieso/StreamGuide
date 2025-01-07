<?php

namespace App\Livewire\Admin;

use App\Models\Guide;
use App\Models\GuideCategory;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class GuideManager extends AdminComponent
{
    use WithFileUploads;

    public $guides;
    public $editingGuide = false;
    public $title;
    public $slug;
    public $content;
    public $category_id;
    public $order;
    public $currentGuide;
    public $upload;

    protected $rules = [
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255',
        'content' => 'required|string',
        'category_id' => 'required|exists:guide_categories,id',
        'order' => 'required|integer|min:0',
    ];

    public function handleFileUpload($fileData)
    {
        if (!is_array($fileData)) {
            return null;
        }

        // Create an UploadedFile instance from the raw data
        $tempFile = tempnam(sys_get_temp_dir(), 'upload');
        file_put_contents($tempFile, base64_decode($fileData['data']));
        
        $file = new UploadedFile(
            $tempFile,
            $fileData['name'],
            $fileData['type'],
            null,
            true
        );

        // Generate a unique filename
        $filename = Str::random(10) . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('uploads/guide-images', $filename, 'public');
        
        // Clean up the temp file
        @unlink($tempFile);

        return Storage::url($path);
    }

    protected function adminMount(...$args): void
    {
        $this->loadGuides();
    }

    public function loadGuides()
    {
        $this->guides = Guide::with('category')
            ->orderBy('order')
            ->get();
    }

    public function createGuide()
    {
        $this->resetForm();
        $this->editingGuide = true;
    }

    public function editGuide(Guide $guide)
    {
        $this->currentGuide = $guide;
        $this->title = $guide->title;
        $this->slug = $guide->slug;
        $this->content = $guide->content;
        $this->category_id = $guide->guide_category_id;
        $this->order = $guide->order;
        $this->editingGuide = true;
    }

    public function saveGuide()
    {
        $this->validate();

        if ($this->currentGuide) {
            $this->currentGuide->update([
                'title' => $this->title,
                'slug' => $this->slug,
                'content' => $this->content,
                'guide_category_id' => $this->category_id,
                'order' => $this->order,
            ]);
        } else {
            Guide::create([
                'title' => $this->title,
                'slug' => $this->slug,
                'content' => $this->content,
                'guide_category_id' => $this->category_id,
                'order' => $this->order,
            ]);
        }

        $this->resetForm();
        $this->loadGuides();
        session()->flash('message', 'Guide saved successfully.');
    }

    public function deleteGuide(Guide $guide)
    {
        // Delete associated images from storage
        preg_match_all('/<img[^>]+src="([^">]+)"/', $guide->content, $matches);
        if (isset($matches[1])) {
            foreach ($matches[1] as $url) {
                $path = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $guide->delete();
        $this->loadGuides();
        session()->flash('message', 'Guide deleted successfully.');
    }

    public function resetForm()
    {
        $this->currentGuide = null;
        $this->title = '';
        $this->slug = '';
        $this->content = '';
        $this->category_id = '';
        $this->order = 0;
        $this->editingGuide = false;
    }

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }

    public function render(): View
    {
        return view('livewire.admin.guide-manager');
    }
}
