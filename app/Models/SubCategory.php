<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SubCategory extends Model
{
    protected $fillable = ['category_id', 'name', 'language_id', 'group_id'];
    protected static function booted()
    {
        static::creating(function ($sub) {
            if (is_null($sub->group_id)) {
                $sub->group_id = Str::uuid();
            }
        });
    }
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }
}
