<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Listing extends Model
{
    protected $fillable = [
        'listing_no',
        'user_id',
        'title',
        'description',
        'category_id',
        'sub_category_id',
        'city_id',
        'district_id',
        'neigborhood_id',
        'quarter_id',
        'postal_code',
        'status',
        'photos',
        'data',
        'language_id',
    ];
    protected $casts=[
        'photos' => 'array',
        'data' => 'array',
    ];

    protected static function booted()
    {
        static::created(function ($listing) {
            // ID’ye göre 8 haneli sıfır-pad’li numara
            $listing->listing_no = str_pad($listing->id, 8, '0', STR_PAD_LEFT);
            // sessizce kaydet (observers tetiklenmesin diye)
            $listing->saveQuietly();
        });
    }

    /**
     * İlan sahibi kullanıcı
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(SiteUser::class, 'user_id');
    }

    /**
     * İlanın kategorisi
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    /**
     * İlanın alt kategorisi
     */
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
    public function neigborhood(): BelongsTo
    {
        return $this->belongsTo(Neigborhood::class);
    }
    public function quarter(): BelongsTo
    {
        return $this->belongsTo(Quarter::class);
    }
    public function language()
{
    return $this->belongsTo(Language::class);
}
}
