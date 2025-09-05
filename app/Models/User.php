<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'hedera_account_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function contributions()
    {
        return $this->hasMany(ResourceContribution::class);
    }
}