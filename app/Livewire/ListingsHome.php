<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Language;
use App\Models\Listing;

class ListingsHome extends Component
{
    public $listings;
    public $language_id;
    public function mount($language_id)
    {
        $this->language_id = $language_id;
        $this->listings = Listing::where('language_id', $this->language_id)->whereNot('status','inactive')->get();
    }
    public function render()
    {
        $language = Language::findOrFail($this->language_id);
        return view('livewire.listings-home',['language' => $language]);
    }
}
