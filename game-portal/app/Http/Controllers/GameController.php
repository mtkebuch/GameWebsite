<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::with('categories', 'reviews')
                     ->withAvg('reviews', 'rating')
                     ->withCount('reviews')
                     ->paginate(12);
        return view('games.browse', compact('games'));
    }

    public function show(Game $game)
    {
        
        $game->load(['categories', 'reviews.user']);
        
        
        $userReview = null;
        if (auth()->check()) {
            $userReview = $game->reviews()
                               ->where('user_id', auth()->id())
                               ->first();
        }
        
       
        $userOwnsGame = false;
        if (auth()->check()) {
            $userOwnsGame = auth()->user()->library()
                                         ->where('game_id', $game->id)
                                         ->exists();
        }
        
        
        $inWishlist = false;
        if (auth()->check()) {
            $inWishlist = auth()->user()->wishlist()
                                       ->where('game_id', $game->id)
                                       ->exists();
        }
        
      
        $relatedGames = Game::whereHas('categories', function($query) use ($game) {
                $query->whereIn('categories.id', $game->categories->pluck('id'));
            })
            ->where('id', '!=', $game->id)
            ->with('categories')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->inRandomOrder()
            ->take(4)
            ->get();
        
        return view('games.show', compact(
            'game', 
            'relatedGames', 
            'userReview',
            'userOwnsGame',
            'inWishlist'
        ));
    }

    public function create()
    {
        $categories = Category::all();
        return view('games.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048', 
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('games', 'public');
        }

        $game = Auth::user()->games()->create($data);

        if (isset($data['categories'])) {
            $game->categories()->sync($data['categories']);
        }

        return redirect()->route('games.show', $game)->with('success', 'Game created successfully!');
    }

    public function edit(Game $game)
    {
       
        if ($game->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        $categories = Category::all();
        return view('games.edit', compact('game', 'categories'));
    }

    public function update(Request $request, Game $game)
    {
        
        if ($game->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            
            if ($game->image) {
                Storage::disk('public')->delete($game->image);
            }
            $data['image'] = $request->file('image')->store('games', 'public');
        }

        $game->update($data);

        if (isset($data['categories'])) {
            $game->categories()->sync($data['categories']);
        }

        return redirect()->route('games.show', $game)->with('success', 'Game updated successfully!');
    }

    public function destroy(Game $game)
    {
        
        if ($game->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
      
        if ($game->image) {
            Storage::disk('public')->delete($game->image);
        }
        
        $game->delete();
        
        return redirect()->route('games.index')->with('success', 'Game deleted successfully!');
    }
}