<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Eğer bu dilin is_native true ise, diğer tüm dillerin is_native'ini false yap
            if ($model->is_native) {
                // Diğer dillerin is_native değerini false yapalım
                static::where('id', '!=', $model->id)->update(['is_native' => false]);
            }
        });
    }
    protected $fillable = [
        'code',
        'name',
        'flag_image',
        'is_native'
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }
}
