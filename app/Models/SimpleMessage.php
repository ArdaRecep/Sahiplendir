<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimpleMessage extends Model
{
    protected $table = 'simple_messages';
    protected $fillable = ['sender_id','recipient_id','body'];

    public function sender()
    {
        return $this->belongsTo(SiteUser::class, 'sender_id');
    }
    public function recipient()
    {
        return $this->belongsTo(SiteUser::class, 'recipient_id');
    }
}
