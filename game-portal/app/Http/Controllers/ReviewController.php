<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Game;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Game $game)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $existingReview = Review::where('user_id', auth()->id())
                                ->where('game_id', $game->id)
                                ->whereNull('deleted_at')
                                ->first();
        
        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this game!');
        }

        Review::create([
            'user_id' => auth()->id(),
            'game_id' => $game->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review->update($validated);

        return redirect()->route('games.show', $review->game_id)
                         ->with('success', 'Review updated successfully!');
    }

    public function destroy(Review $review)
    {
        
        if ($review->user_id !== auth()->id() && !auth()->user()->is_admin) {
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            abort(403);
        }

        $gameId = $review->game_id;
        $review->delete();

       
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully!'
            ]);
        }

        
        return redirect()->route('games.show', $gameId)
                         ->with('success', 'Review deleted successfully!');
    }

   
    public function trashed()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $reviews = Review::onlyTrashed()
            ->with(['user', 'game'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);
            
        return view('reviews.trashed', compact('reviews'));
    }

    public function restore(string $id)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $review = Review::onlyTrashed()->findOrFail($id);
        $review->restore();
        
        return back()->with('success', 'Review restored successfully!');
    }

    public function forceDestroy(string $id)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $review = Review::onlyTrashed()->findOrFail($id);
        $review->forceDelete();
        
        return back()->with('success', 'Review permanently deleted!');
    }
}