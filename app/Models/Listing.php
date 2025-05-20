<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Listing extends Model
{
    protected $fillable = [
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
    ];

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
}
