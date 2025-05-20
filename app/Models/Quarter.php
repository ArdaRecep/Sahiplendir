<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quarter extends Model
{
    protected $fillable = [
        'name',
        'postal_code',
        'neigborhood_id',
    ];

    /**
     * Quarter bir mahalleye aittir.
     */
    public function neigborhood(): BelongsTo
    {
        return $this->belongsTo(Neigborhood::class);
    }
}
