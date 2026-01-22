<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
       
        $stats = [
            'total_reviews' => $user->reviews()->count(),
            'average_rating' => $user->reviews()->avg('rating') ?? 0,
            'recent_reviews' => $user->reviews()
                                     ->with('game')
                                     ->latest()
                                     ->take(5)
                                     ->get(),
        ];
        
       
        $libraryCount = $user->library()->count();
        $wishlistCount = $user->wishlist()->count();
        
       
        $reviewedCategories = $user->reviews()
                                   ->with('game.categories')
                                   ->get()
                                   ->pluck('game.categories')
                                   ->flatten()
                                   ->pluck('id')
                                   ->unique();
        
        $recommendedGames = Game::whereHas('categories', function($query) use ($reviewedCategories) {
                                if ($reviewedCategories->count() > 0) {
                                    $query->whereIn('categories.id', $reviewedCategories);
                                }
                            })
                            ->whereNotIn('id', $user->reviews()->pluck('game_id'))
                            ->withAvg('reviews', 'rating')
                            ->inRandomOrder()
                            ->take(6)
                            ->get();
        
        return view('dashboard.index', compact('stats', 'libraryCount', 'wishlistCount', 'recommendedGames'));
    }

    public function library()
    {
        $user = Auth::user();
        
       
        $myGames = $user->library()
                        ->withAvg('reviews', 'rating')
                        ->paginate(12);
        
        return view('dashboard.library', compact('myGames'));
    }

    public function reviews()
    {
        $user = Auth::user();
        
        $myReviews = $user->reviews()
                          ->with('game')
                          ->latest()
                          ->paginate(10);
        
        return view('dashboard.reviews', compact('myReviews'));
    }

    public function wishlist()
    {
        $user = Auth::user();
        
        $wishlistGames = $user->wishlist()
                              ->withAvg('reviews', 'rating')
                              ->paginate(12);
        
        return view('dashboard.wishlist', compact('wishlistGames'));
    }

    public function achievements()
    {
        return view('dashboard.achievements');
    }

    public function friends()
    {
        return view('dashboard.friends');
    }

 
    public function addToLibrary(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
        ]);

        $user = Auth::user();
        
        
        if (!$user->library()->where('game_id', $validated['game_id'])->exists()) {
            $user->library()->attach($validated['game_id'], [
                'added_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Game added to library'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Game already in library'
        ], 400);
    }

   
    public function removeFromLibrary(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
        ]);

        $user = Auth::user();
        $user->library()->detach($validated['game_id']);
        
        return response()->json([
            'success' => true,
            'message' => 'Game removed from library'
        ]);
    }

    
    public function addToWishlist(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
        ]);

        $user = Auth::user();
        
      
        if (!$user->wishlist()->where('game_id', $validated['game_id'])->exists()) {
            $user->wishlist()->attach($validated['game_id'], [
                'added_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Game added to wishlist'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Game already in wishlist'
        ], 400);
    }

    
    public function removeFromWishlist(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
        ]);

        $user = Auth::user();
        $user->wishlist()->detach($validated['game_id']);
        
        return response()->json([
            'success' => true,
            'message' => 'Game removed from wishlist'
        ]);
    }
}