<?php

namespace App\Console\Commands;

use App\Models\VillageStat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateVillageStats extends Command
{
    protected $signature = 'stats:update';
    protected $description = 'Update village statistics based on recent contributions';

    public function handle()
    {
        $this->info('Updating village statistics...');
        
        // Get yesterday's date
        $yesterday = now()->subDay()->format('Y-m-d');
        
        // Update or create stats for each village and resource type
        $stats = DB::table('resource_contributions')
            ->join('users', 'resource_contributions.user_id', '=', 'users.id')
            ->where('resource_contributions.status', 'confirmed')
            ->whereDate('resource_contributions.created_at', $yesterday)
            ->select(
                'users.village_name',
                'resource_contributions.resource_type',
                DB::raw('SUM(resource_contributions.amount) as total_contributed'),
                DB::raw('COUNT(DISTINCT resource_contributions.user_id) as contributor_count')
            )
            ->groupBy('users.village_name', 'resource_contributions.resource_type')
            ->get();
        
        foreach ($stats as $stat) {
            VillageStat::updateOrCreate(
                [
                    'village_name' => $stat->village_name,
                    'resource_type' => $stat->resource_type,
                    'stat_date' => $yesterday
                ],
                [
                    'total_contributed' => $stat->total_contributed,
                    'contributor_count' => $stat->contributor_count
                ]
            );
        }
        
        $this->info('Village statistics updated successfully!');
        return 0;
    }
}