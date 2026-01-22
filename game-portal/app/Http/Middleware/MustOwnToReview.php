<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustOwnToReview
{
    public function handle(Request $request, Closure $next): Response
    {
       
        if ($request->isMethod('post')) {
            $game = $request->route('game');
            $gameId = is_object($game) ? $game->id : $game;
            
            
            $hasGameInLibrary = auth()->user()->library()
                ->where('game_id', $gameId)
                ->exists();
            
            if (!$hasGameInLibrary) {
                return redirect()
                    ->back()
                    ->with('error', 'You must add this game to your library before writing a review.');
            }
            
           
            $existingReview = auth()->user()->reviews()
                ->where('game_id', $gameId)
                ->first();
            
            if ($existingReview) {
                return redirect()
                    ->back()
                    ->with('error', 'You have already reviewed this game. You can edit your existing review.');
            }
        }
        
       
        if ($request->isMethod('patch') || $request->isMethod('delete')) {
            $review = $request->route('review');
            
            if ($review->user_id !== auth()->id()) {
                return redirect()
                    ->back()
                    ->with('error', 'You can only modify your own reviews.');
            }
        }
        
        return $next($request);
    }
}