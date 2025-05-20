<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Neigborhood extends Model
{
    protected $fillable = [
        'name',
        'district_id',
    ];

    /**
     * Mahalle bir ilçeye aittir.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Bir mahallenin birden çok quarter’ı olabilir.
     */
    public function quarters(): HasMany
    {
        return $this->hasMany(Quarter::class);
    }
}
