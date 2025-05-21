<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\City;
use App\Models\District;
use App\Models\Neigborhood;
use App\Models\Quarter;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;

class ListingCreate extends Component
{
    use WithFileUploads;
    public $cities;
    public $category_id;
    public $districts;
    public $neigborhoods;
    public $quarters;
    public $photos = [];
    public $existingPhotos = [];
    public $selectedCity;
    public $selectedDistrict;
    public $selectedNeigborhood;
    public $selectedQuarter;

    public $title;
    public $description;
    public $status = 'active';

    public function mount()
    {
        $this->cities = City::all();
        $this->districts = collect();
        $this->neigborhoods = collect();
        $this->quarters = collect();
    }

    // Seçim anında tetiklenecek modifiers ile çalışır
    public function updatedSelectedCity($cityId)
    {
        $this->districts = District::where('city_id', $cityId)->get();
        $this->neigborhoods = collect();
        $this->quarters = collect();
    }

    public function updatedSelectedDistrict($districtId)
    {
        $this->neigborhoods = Neigborhood::where('district_id', $districtId)->get();
        $this->quarters = collect();
    }

    public function updatedSelectedNeigborhood($neigborhoodId)
    {
        $this->quarters = Quarter::where('neigborhood_id', $neigborhoodId)->get();
    }

    public function submit()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'selectedCity' => 'required|exists:cities,id',
            'selectedDistrict' => 'required|exists:districts,id',
            'selectedNeigborhood' => 'required|exists:neigborhoods,id',
            'selectedQuarter' => 'required|exists:quarters,id',
            'photos.*' => 'required|image|max:2048',
        ]);
        $paths = [];
        foreach ($this->photos as $file) {
            $paths[] = $file->store('listings', 'public');
        }

        $allPhotos = array_merge($this->existingPhotos, $paths);
        Listing::create([
            'user_id' => Auth::guard('siteuser')->user()->id,
            'photos' => $allPhotos,
            'title' => $this->title,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'city' => City::find($this->selectedCity)->name,
            'district' => District::find($this->selectedDistrict)->name,
            'neighborhood' => Neigborhood::find($this->selectedNeigborhood)->name,
            'quarter' => Quarter::find($this->selectedQuarter)->name,
            'postal_code' => Quarter::find($this->selectedQuarter)->postal_code,
            'status' => $this->status,
        ]);

        session()->flash('success', 'İlanınız başarıyla oluşturuldu.');
        return view('livewire.profile');
    }

    public function render()
    {
        return view('livewire.listing-create');
    }
}
