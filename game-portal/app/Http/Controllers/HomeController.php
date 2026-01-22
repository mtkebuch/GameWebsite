<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        
        $featuredGames = Game::with(['categories', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(6)
            ->get();
        
        
        $categories = Category::withCount('games')
            ->orderBy('name')
            ->get();
        
       
        $topRatedGames = Game::with('reviews')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->has('reviews')
            ->get()
            ->filter(function($game) {
                return $game->reviews_avg_rating >= 4.5;
            })
            ->sortByDesc('reviews_avg_rating')
            ->take(6);
        
        
        $latestReviews = Review::with(['user', 'game'])
            ->whereNotNull('comment')
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('featuredGames', 'categories', 'topRatedGames', 'latestReviews'));
    }
    
   
    public function loadMore(Request $request)
    {
        $page = $request->get('page', 2);
        $perPage = 6;
        
       
        $skip = ($page - 1) * $perPage;
        
        $games = Game::with(['categories', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->latest()
            ->skip($skip)
            ->take($perPage)
            ->get();
        
        
        $totalGames = Game::count();
        $hasMore = ($page * $perPage) < $totalGames;
        
       
        $html = '';
        foreach ($games as $game) {
            $html .= view('partials.game-card', compact('game'))->render();
        }
        
        return response()->json([
            'html' => $html,
            'hasMore' => $hasMore,
            'currentPage' => $page,
            'totalGames' => $totalGames,
            'loadedGames' => $games->count()
        ]);
    }
}