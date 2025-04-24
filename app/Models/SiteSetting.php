<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiteSetting extends Model
{
    protected $fillable = [
        'group_id',
        'language_id',
        'data',
        'logo',
        'footer_logo',
        'fav_icon',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
