<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    protected $fillable = [
        'name',
        'city_id',
    ];

    /**
     * İlçe bir şehre aittir.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Bir ilçenin birden çok mahallesi olabilir.
     */
    public function neigborhoods(): HasMany
    {
        return $this->hasMany(Neigborhood::class);
    }
}
