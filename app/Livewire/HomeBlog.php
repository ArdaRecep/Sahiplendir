<?php

namespace App\Livewire;

use App\Models\Language;
use App\Models\Post;
use Livewire\Component;

class HomeBlog extends Component
{

    protected $language_id;

    public function mount($language_id)
    {
        $this->language_id = $language_id;
    }

    public function render()
    {

        $posts = Post::with('postCategories')
        ->where('language_id', $this->language_id)
        ->whereNotNull('published_at')
        ->latest()
        ->take(3)
        ->get();

        $language = Language::findOrFail($this->language_id);
        \Carbon\Carbon::setLocale($language->code);

        return view('livewire.home-blog',[
            'language' => $language,
            'posts' => $posts
        ]);
    }
}
