<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Language;
use App\Models\Listing;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\City;

class Listings extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $language_id;
    public $language;
    public $search;
    public $category_id;
    public $sub_category_id;
    public $city_id;

    // Yeni yaş filtresi
    public $age_value;
    public $age_unit; // 'Aylık' veya 'Yıllık'

    public $size;
    public $gender;
    public $neutered;
    public $apartment;
    public $otherAnimals;

    public function mount($language_id)
    {
        $this->language_id = $language_id;
        $this->language = Language::findOrFail($language_id);
    }

    public function updating($field)
    {
        if (
            in_array($field, [
                'search',
                'category_id',
                'sub_category_id',
                'city_id',
                'age_value',
                'age_unit',
                'size',
                'gender',
                'neutered',
                'apartment',
                'otherAnimals',
            ])
        ) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->search = null;
        $this->category_id = null;
        $this->sub_category_id = null;
        $this->city_id = null;
        $this->age_value = null;
        $this->age_unit = null;
        $this->size = null;
        $this->gender = null;
        $this->neutered = null;
        $this->apartment = null;
        $this->otherAnimals = null;
        $this->resetPage();
    }

    public function render()
    {
        \Carbon\Carbon::setLocale(
            Language::findOrFail($this->language_id)->code
        );

        $query = Listing::where('language_id', $this->language_id)
            ->where('status', '!=', 'inactive')
            ->when(
                $this->search,
                fn($q) =>
                $q->where('title', 'like', "%{$this->search}%")
            )
            ->when(
                $this->category_id,
                fn($q) =>
                $q->where('category_id', $this->category_id)
            )
            ->when(
                $this->sub_category_id,
                fn($q) =>
                $q->where('sub_category_id', $this->sub_category_id)
            )
            ->when(
                $this->city_id,
                fn($q) =>
                $q->where('city_id', $this->city_id)
            )
            // Yaş filtresi: önce hem değer hem birim doluysa uygula
            ->when($this->age_value && $this->age_unit, function ($q) {
                $ageString = $this->age_value . ' ' . $this->age_unit;
                return $q->where('data->age', $ageString);
            })
            ->when(
                $this->size,
                fn($q) =>
                $q->where('data->size', $this->size)
            )
            ->when(
                $this->gender,
                fn($q) =>
                $q->where('data->gender', $this->gender)
            )
            ->when(
                $this->neutered,
                fn($q) =>
                $q->where('data->neutered', $this->neutered)
            )
            // apartment: true ise data->apartment = 'Evet'
            ->when(
                $this->apartment === true,
                fn($q) =>
                $q->where('data->apartment', trans('theme/front.yes', [], $this->language->code))
            )
            // otherAnimals: true ise data->otherAnimals = 'Evet'
            ->when(
                $this->otherAnimals === true,
                fn($q) =>
                $q->where('data->otherAnimals', trans('theme/front.yes', [], $this->language->code))
            )
            ->latest();

        $listings = $query->paginate(6);

        $categories = Category::where('language_id', $this->language_id)->get();
        $subCategories = SubCategory::where('language_id', $this->language_id)
            ->when($this->category_id, fn($q) => $q->where('category_id', $this->category_id))
            ->get();
        $cities = City::all();
        $language = Language::findOrFail($this->language_id);

        return view('livewire.listings', compact(
            'listings',
            'categories',
            'subCategories',
            'cities',
            'language'
        ));
    }
}
