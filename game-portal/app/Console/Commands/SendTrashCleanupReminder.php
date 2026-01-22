<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Game;
use App\Models\Review;
use App\Models\Category;
use Illuminate\Support\Str;

class SendTrashCleanupReminder extends Command
{
    protected $signature = 'notifications:trash-cleanup-reminder';
    protected $description = 'Remind admin to cleanup old trashed items';

    public function handle()
    {
        $admin = User::where('role_id', 1)->first();

        if (!$admin) {
            $this->error("Admin not found!");
            return 1;
        }

        $oneHourAgo = now()->subHour();

        
        $trashedGames = Game::onlyTrashed()
            ->where('deleted_at', '<=', $oneHourAgo)
            ->count();

        $trashedUsers = User::onlyTrashed()
            ->where('deleted_at', '<=', $oneHourAgo)
            ->count();

        $trashedReviews = Review::onlyTrashed()
            ->where('deleted_at', '<=', $oneHourAgo)
            ->count();

        $trashedCategories = Category::onlyTrashed()
            ->where('deleted_at', '<=', $oneHourAgo)
            ->count();

        $totalTrashed = $trashedGames + $trashedUsers + $trashedReviews + $trashedCategories;

        if ($totalTrashed === 0) {
            $this->info("No old trashed items to cleanup!");
            return 0;
        }

        $admin->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => 'trash_cleanup_reminder',
            'data' => [
                'title' => 'Trash Cleanup Reminder',
                'message' => "{$totalTrashed} items in trash for 1+ hours (Games: {$trashedGames}, Users: {$trashedUsers}, Reviews: {$trashedReviews}, Categories: {$trashedCategories})",
                'total_trashed' => $totalTrashed,
                'trashed_games' => $trashedGames,
                'trashed_users' => $trashedUsers,
                'trashed_reviews' => $trashedReviews,
                'trashed_categories' => $trashedCategories,
                'date' => now()->toFormattedDateString(),
            ]
        ]);

        $this->info("Trash Cleanup Reminder Sent!");
        $this->info("Total Items: {$totalTrashed}");
        $this->info("Games: {$trashedGames}");
        $this->info("Users: {$trashedUsers}");
        $this->info("Reviews: {$trashedReviews}");
        $this->info("Categories: {$trashedCategories}");

        return 0;
    }
}