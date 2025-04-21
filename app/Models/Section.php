<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image'];

    // Eğer sayfalarla ilişkili olacaksa
    // Section.php
    public function pages()
    {
        return $this->belongsToMany(Page::class, 'page_sections')
            ->withPivot(['order', 'data', 'name']) // name alanını pivot ile almak
            ->withTimestamps();
    }


    public function getAdminViewPath(): string
    {
        return "sections.admin.{$this->slug}";
    }

    public function getFrontViewPath(): string
    {
        return "sections.front.{$this->slug}";
    }
    public function pageSections()
    {
        return $this->hasMany(PageSection::class);
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'page_sections')
            ->withPivot('order', 'data')
            ->withTimestamps();
    }
}
