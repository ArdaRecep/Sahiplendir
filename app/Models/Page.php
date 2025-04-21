<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Language;
use Str;
use Filament\Notifications\Notification;

class Page extends Model
{
    protected $fillable = [
        'group_id',
        'language_id',
        'slug',
        'name',
        'content',
        'published_at',
        'is_home',
    ];

    protected $casts = [
        'is_home' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($page) {
            if (is_null($page->group_id)) {
                $page->group_id = Str::uuid();
            }
        });

        static::saving(function ($page) {
            if ($page->isDirty('is_home') && $page->is_home) {
                // 1. Tüm sayfalarda is_home = false yap
                static::query()->update(['is_home' => false]);

                // 2. Bu kaydın group_id'sine sahip tüm sayfalarda is_home = true yap
                static::where('group_id', $page->group_id)
                    ->update(['is_home' => true]);
            }
        });

        static::deleted(function ($page) {
            static::where('group_id', $page->group_id)
                ->where('id', '!=', $page->id)
                ->get()
                ->each(function ($relatedPage) {
                    $relatedPage->delete();
                });
        });


    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function pageSections()
    {
        return $this->hasMany(PageSection::class);
    }

    // Page.php
public function sections()
{
    return $this
        ->belongsToMany(Section::class, 'page_sections')
        ->withPivot(['order','data','name'])
        ->withTimestamps()
        ->orderBy('page_sections.order');  // tablo_adı.sütun_adı
}




    public function relatedPages()
    {
        return $this->hasMany(Page::class, 'group_id', 'group_id')->where('id', '!=', $this->id);
    }
}
