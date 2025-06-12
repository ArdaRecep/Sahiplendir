<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionAnimalScore extends Model
{
    protected $fillable = ['test_option_id','sub_category_id','score'];
    public function option()   { return $this->belongsTo(TestOption::class,'test_option_id'); }
    public function sub_category() { return $this->belongsTo(SubCategory::class); }
}
