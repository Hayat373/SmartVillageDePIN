<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VillageStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'village_name', 'resource_type', 'total_contributed',
        'total_allocated', 'contributor_count', 'stat_date'
    ];

    protected $casts = [
        'total_contributed' => 'decimal:6',
        'total_allocated' => 'decimal:6',
        'stat_date' => 'date',
    ];

    public function scopeForVillage($query, $villageName)
    {
        return $query->where('village_name', $villageName);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('stat_date', '>=', now()->subDays($days));
    }
}