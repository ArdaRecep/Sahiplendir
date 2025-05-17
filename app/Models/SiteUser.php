<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteUser extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'site_users';

    protected $fillable = [
        'username',
        'name',
        'surname',
        'email',
        'phone',
        'password',
        'profile_photo',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
