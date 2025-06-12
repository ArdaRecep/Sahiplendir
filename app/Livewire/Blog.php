<?php

namespace App\Livewire;

use App\Models\Language;
use App\Models\Post;
use Livewire\WithPagination;
use App\Models\PostCategory;
use Livewire\Component;

class Blog extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $language_id;

    public $search;

    public function mount($language_id)
    {
        $this->language_id = $language_id;
    }

    public function render()
    {
        $query = Post::where('language_id', $this->language_id)->latest();
        $language = Language::findOrFail($this->language_id);
        \Carbon\Carbon::setLocale($language->code);

        if ($this->search) {
            $query
                ->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $categories = PostCategory::where('language_id', $this->language_id)
            ->whereNotNull('published_at')
            ->withCount([
                    'posts' => function ($query) {
                        $query->whereNotNull('published_at');
                    }
                ])
            ->latest()
            ->get();

        return view('livewire.blog', [
            'posts' => $query->whereNotNull('published_at')->paginate(6),
            'postCount' => $query->count(),
            'categories' => $categories,
            'language' => $language,
            'search' => $this->search,
        ]);
    }
}
