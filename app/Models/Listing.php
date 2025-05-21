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
        'city',
        'district',
        'neighborhood',
        'quarter',
        'postal_code',
        'status',
        'photos',
        'language_id',
    ];
    protected $casts=[
        'photos' => 'array',
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
    public function language()
{
    return $this->belongsTo(Language::class);
}
}
