<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class City extends Model
{
    // Tablo adı Laravel’in varsayılanı ile uyuyor, bu yüzden $table belirtmeye gerek yok.
    protected $fillable = [
        'name',
    ];
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    /**
     * Bir şehrin birden çok ilçesi olabilir.
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}
