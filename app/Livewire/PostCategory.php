<?php

namespace App\Livewire;

use App\Models\Language;
use App\Models\PostCategory as ModelsPostCategory;
use Livewire\Component;

class PostCategory extends Component
{
    public $language_id;
    public $category_id;
    public $page;
    public $search;

    public function mount($language_id, $category_id, $page)
    {
        $this->language_id = $language_id;
        $this->category_id = $category_id;
        $this->page = $page;
    }

    public function render()
    {
        $language = Language::findOrFail($this->language_id);
        \Carbon\Carbon::setLocale($language->code);
        $category = ModelsPostCategory::findOrFail($this->category_id);

        $categories = ModelsPostCategory::where('language_id', $this->language_id)
            ->whereNotNull('published_at')
            ->withCount(['posts' => function ($query) {
                $query->whereNotNull('published_at');
            }])
            ->latest()
            ->get();

        $posts = $category->posts()
            ->when(!is_null($this->search), function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->get();
        return view('livewire.post-category', [
            'categories' => $categories,
            'posts' => $posts,
            'language' => $language,
            'postCount' => $posts->count(),
            'category_id' => $this->category_id,
            'page' => $this->page,
        ]);
    }
}
