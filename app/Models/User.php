<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'hedera_account_id', 'village_name', 'country', 'reputation_score', 'is_verified',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'reputation_score' => 'decimal:2',
        'is_verified' => 'boolean',
    ];


    public function contributions()
    {
        return $this->hasMany(ResourceContribution::class);
    }

    public function getTotalContributionsAttribute()
    {
        return $this->contributions()->where('status', 'confirmed')->count();
    }

    public function getTotalContributedAmountAttribute()
    {
        return $this->contributions()->where('status', 'confirmed')->sum('amount');
    }
    
}