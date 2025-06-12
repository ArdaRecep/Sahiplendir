<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestOption extends Model
{
    protected $fillable = ['test_question_id', 'option_text'];
    public function question()
    {
        return $this->belongsTo(TestQuestion::class, 'test_question_id');
    }
    public function scores()
    {
        return $this->hasMany(OptionAnimalScore::class, 'test_option_id');
    }
}
