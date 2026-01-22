<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Str;

class SendActiveUsersReport extends Command
{
    protected $signature = 'notifications:most-active-user {period=month}';
    protected $description = 'Find and notify admin about the most active user';

    public function handle()
    {
        $period = $this->argument('period'); 
        
        $admin = User::where('role_id', 1)->first();

        if (!$admin) {
            $this->error("Admin not found!");
            return 1;
        }

        $dateFilter = match($period) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            default => null,
        };

        
        $users = User::where('role_id', 2) 
            ->whereNull('deleted_at')
            ->withCount([
                'library as library_count',
                'reviews as reviews_count' => function($q) use ($dateFilter) {
                    if ($dateFilter) $q->where('created_at', '>=', $dateFilter);
                },
            ])
            ->get()
            ->map(function($user) {
               
                $score = 0;
                $score += $user->reviews_count * 10; 
                $score += $user->library_count * 3;   
                
                return [
                    'user' => $user,
                    'score' => $score,
                    'reviews' => $user->reviews_count,
                    'library' => $user->library_count,
                ];
            })
            ->filter(fn($data) => $data['score'] > 0)
            ->sortByDesc('score')
            ->values();

        if ($users->isEmpty()) {
            $this->info("No active users found for this period.");
            return 0;
        }

        $winner = $users->first();
        $topUser = $winner['user'];

        $periodLabel = match($period) {
            'week' => 'This Week',
            'month' => 'This Month',
            default => 'All Time',
        };

        
        $admin->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'most_active_user',
            'data' => [
                'title' => "Community Champion - {$periodLabel}",
                'message' => "Most active member: {$topUser->name}",
                'winner_name' => $topUser->name,
                'period' => $periodLabel,
                'date' => now()->toDateString(),
            ]
        ]);

        $this->info("Most Active User Report Sent!");
        $this->info("Winner: {$topUser->name}");
        $this->info("Reviews: {$winner['reviews']}");
        $this->info("Library Games: {$winner['library']}");
        $this->info("Score: {$winner['score']}");

        return 0;
    }
}