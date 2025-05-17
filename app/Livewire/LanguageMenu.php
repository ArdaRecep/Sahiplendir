<?php

namespace App\Livewire;

use App\Models\Language;
use Livewire\Component;

class LanguageMenu extends Component
{
    public $page; // Mevcut sayfa bilgisi
    public $type;
    public $languagesWithSlugs = []; // Dillerin slug'larıyla birlikte listesi

    public function mount($page, $type = "desktop")
    {
        // Dilleri slug ile birlikte hazırlama
        $this->languagesWithSlugs = Language::with(['pages' => function ($query)
        {
            $query->where('group_id', $this->page->group_id);
        }
        ])->get()->map(function ($language)
        {
            $slug = optional($language->pages->first())->slug; // İlk sayfanın slug değeri
            $published_at = optional($language->pages->first())->published_at;

            return [
                'name' => $language->code,
                'flag_image' => $language->flag_image ?? null,
                'title' => $language->name,
                'slug' => $slug,
                'published_at' => $published_at
            ];
        });
        if($this->languagesWithSlugs[0]["slug"]==null)
        {
            $this->languagesWithSlugs = Language::with(['posts' => function ($query)
        {
            $query->where('group_id', $this->page->group_id);
        }
        ])->get()->map(function ($language)
        {
            $slug = optional($language->posts->first())->slug; // İlk sayfanın slug değeri
            $published_at = optional($language->posts->first())->published_at;

            return [
                'name' => $language->name,
                'flag_image' => $language->flag_image ?? null,
                'title' => $language->title,
                'slug' => $slug,
                'published_at' => $published_at
            ];
        });
        }

        if($this->languagesWithSlugs[0]["slug"]==null)
        {
            $this->languagesWithSlugs = Language::with(['post_categories' => function ($query)
        {
            $query->where('group_id', $this->page->group_id);
        }
        ])->get()->map(function ($language)
        {
            $slug = optional($language->post_categories->first())->slug; // İlk sayfanın slug değeri
            $published_at = optional($language->post_categories->first())->published_at;

            return [
                'name' => $language->name,
                'flag_image' => $language->flag_image ?? null,
                'title' => $language->title,
                'slug' => $slug,
                'published_at' => $published_at
            ];
        });
        }

        if($this->languagesWithSlugs[0]["slug"]==null)
        {
            $this->languagesWithSlugs = Language::with(['Service' => function ($query)
        {
            $query->where('group_id', $this->page->group_id);
        }
        ])->get()->map(function ($language)
        {
            $slug = optional($language->Service->first())->slug; // İlk sayfanın slug değeri
            $published_at = optional($language->Service->first())->published_at;

            return [
                'name' => $language->name,
                'flag_image' => $language->flag_image ?? null,
                'title' => $language->title,
                'slug' => $slug,
                'published_at' => $published_at
            ];
        });
        }

    }

    public function render()
    {
        return view('livewire.language-menu', [
            'languagesWithSlugs' => $this->languagesWithSlugs,
            'type' => $this->type,
        ]);
    }
}