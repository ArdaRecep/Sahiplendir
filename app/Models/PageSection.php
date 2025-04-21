<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
    protected $fillable = ['name','page_id', 'section_id', 'order', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

    public static function boot()
{
    parent::boot();

    static::creating(function (Model $record) {
        // İlgili sayfanın en yüksek order değerini bulalım
        $maxOrder = \DB::table('page_sections')
            ->where('page_id', $record->page_id)
            ->max('order');

        // Eğer hiç kayıt yoksa null döner, bu yüzden 0 yapıp 1 artırıyoruz
        $record->order = ($maxOrder ?? 0) + 1;
    });
}
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
