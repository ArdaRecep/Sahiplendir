<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestQuestion extends Model
{
    protected $fillable = ['question_text','language_id'];
    public function options() { return $this->hasMany(TestOption::class); }
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
