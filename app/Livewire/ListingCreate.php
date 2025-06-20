<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Language;
use App\Models\SubCategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\City;
use App\Models\District;
use App\Models\Neigborhood;
use App\Models\Quarter;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class ListingCreate extends Component
{
    use WithFileUploads;
    
    public $cities;
    public $categories;
    public $subCategories;
    public $language_id;
    public $language;
    public $districts;
    public $neigborhoods;
    public $quarters;
    public $photos = [];
    public $existingPhotos = [];
    public $selectedCity;
    public $selectedDistrict;
    public $selectedNeigborhood;
    public $selectedQuarter;
    public $selectedCategory;
    public $selectedSubCategory;
    public $title;
    public $description;
    public $status = 'inactive';

    //data
    public $selectedUnit;
    public $age;
    public $selectedNeutered;
    public $neutered;
    public $selectedGender;
    public $gender;
    public $selectedOtherAnimals;
    public $otherAnimals;
    public $selectedApartment;
    public $apartment;
    public $selectedSize;
    public $size;
    public $ageWithUnit;

    public function mount($language_id)
    {
        $this->language_id = $language_id;
        $this->categories = Category::all();
        $this->cities = City::all();
        $this->districts = collect();
        $this->subCategories = collect();
        $this->neigborhoods = collect();
        $this->quarters = collect();
        $this->language = Language::findOrFail($language_id);
    }

    // Seçim anında tetiklenecek modifiers ile çalışır
    public function updatedSelectedCity($cityId)
    {
        $this->districts = District::where('city_id', $cityId)->get();
        $this->neigborhoods = collect();
        $this->quarters = collect();
    }
    public function updatedSelectedCategory($categoryId)
    {
        $this->subCategories = SubCategory::where('category_id', $categoryId)->get();
        ;
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
    //data
    public function updatedSelectedGender($gender)
    {
        $this->gender = $gender;
    }
    public function updatedSelectedUnit($unit)
    {
        if($unit=="month")
        {
            if($this->language->code=="tr")
                $this->ageWithUnit = $this->age." Aylık";
            else
                $this->ageWithUnit = $this->age." Months old";
        }
        else
        {
            if($this->language->code=="tr")
                $this->ageWithUnit = $this->age." Yaşında";
            else
                $this->ageWithUnit = $this->age." Years old";
        }
    }
    public function updatedSelectedApartment($apartment)
    {
        $this->apartment = $apartment;
    }
    public function updatedSelectedOtherAnimals($otherAnimals)
    {
        $this->$otherAnimals = $otherAnimals;
    }
    public function updatedSelectedNeutered($neutered)
    {
        $this->neutered = $neutered;
    }
    public function updatedSelectedSize($size)
    {
        $this->size = $size;
    }

    public function submit()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'selectedCategory' => 'required|exists:categories,id',
            'selectedCity' => 'required|exists:cities,id',
            'selectedDistrict' => 'required|exists:districts,id',
            'selectedNeigborhood' => 'required|exists:neigborhoods,id',
            'selectedQuarter' => 'required|exists:quarters,id',
            'selectedSubCategory' => 'required|exists:sub_categories,id',
            'photos.*' => 'required|image',
            'age' => 'required|int|max:255',
            'selectedUnit' => 'required|string|max:255',
            'selectedGender' => 'required|string|max:255',
            'selectedSize' => 'required|string|max:255',
            'selectedApartment' => 'required|string|max:255',
            'selectedOtherAnimals' => 'required|string|max:255',
            'selectedNeutered' => 'required|string|max:255',
        ]);
        try{
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
            'category_id' => $this->selectedCategory,
            'language_id' => $this->language_id,
            'sub_category_id' => $this->selectedSubCategory,
            'city_id' => $this->selectedCity,
            'district_id' => $this->selectedDistrict,
            'neigborhood_id' => $this->selectedNeigborhood,
            'quarter_id' => $this->selectedQuarter,
            'postal_code' => Quarter::find($this->selectedQuarter)->postal_code,
            'status' => $this->status,
            'data' => [                     // ← tüm alt alanlar bu dizi içinde
                'age' => $this->ageWithUnit,
                'gender' => $this->selectedGender,
                'size' => $this->selectedSize,
                'apartment' => $this->selectedApartment,
                'neutered' => $this->selectedNeutered,
                'otherAnimals' => $this->selectedOtherAnimals,
            ],
        ]);

        session()->flash('success', trans("theme/front.success_listing",[],$this->language->code));
        $this->dispatch('swal', [
                'title' => trans("theme/front.success_listing",[],$this->language->code),
                'text' => trans("theme/front.success_listing_text",[],$this->language->code),
                'confirmButtonText' => trans("theme/front.ok",[],$this->language->code),
                'icon' => 'success',
                'iconColor' => 'green',
            ]);
        $this->reset([
            'photos',
            'selectedCity',
            'selectedDistrict',
            'selectedNeigborhood',
            'selectedQuarter',
            'selectedCategory',
            'selectedSubCategory',
            'title',
            'description',
            'age',
            'selectedNeutered',
            'selectedGender',
            'selectedUnit',
            'selectedOtherAnimals',
            'selectedApartment',
            'selectedSize'
        ]);
    }catch(\Exception $e)
    {
        \Log::error(trans("theme/front.send_listing_error",[],$this->language->code) . $e->getMessage());
        $this->dispatch('swal', [
                'title' => trans("theme/front.error",[],$this->language->code),
                'text' => trans("theme/front.error_listing",[],$this->language->code),
                'confirmButtonText' => trans("theme/front.ok",[],$this->language->code),
                'icon' => 'error',
                'iconColor' => 'red',
            ]);
    }
    }

    public function render()
    {
        $language = Language::findOrFail($this->language_id);
        App::setLocale($language->code);
        return view('livewire.listing-create', [$this->language_id,'language'=>$language]);
    }
}
