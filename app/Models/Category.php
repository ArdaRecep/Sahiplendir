<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Category extends Model
{
    protected $fillable = ['name', 'language_id', 'group_id'];
    protected static function booted()
    {
        static::creating(function ($cat) {
            if (is_null($cat->group_id)) {
                $cat->group_id = Str::uuid();
            }
        });
    }
    public function language()
    {
        return $this->belongsTo(Language::class);
    }
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
