<?php

namespace App\Livewire;

use App\Models\Language;
use App\Models\Post;
use App\Models\PostCategory;
use Livewire\Component;

class BlogDetail extends Component
{
    // language_id
    public $language_id;

    // post_id
    public $post_id;

    // search
    public $search;

    public function mount($language_id, $post_id)
    {
        $this->language_id = $language_id;
        $this->post_id = $post_id;
    }

    public function render()
    {
        $language = Language::findOrFail($this->language_id);
        \Carbon\Carbon::setLocale($language->code);

        $categories = PostCategory::where('language_id', $this->language_id)
            ->whereNotNull('published_at')
            ->withCount([
                'posts' => function ($query) {
                    $query->whereNotNull('published_at');
                }
            ])
            ->latest()
            ->get();
        $query = Post::where('language_id', $this->language_id)
            ->where('id', '!=', $this->post_id)
            ->whereNotNull('published_at')
            ->orderBy('order', 'asc');


        if ($this->search) {
            $query->where(function ($subQuery) {
                $subQuery->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.blog-detail', [
            'posts' => $query->get(),
            'categories' => $categories,
            'language' => $language,

        ]);
    }
}
