<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Category;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    
    public function index()
    {
        $stats = [
            'total_games' => Game::count(),
            'total_users' => User::count(),
            'total_reviews' => Review::count(),
            'total_categories' => Category::count(),
            'recent_games' => Game::latest()->take(5)->get(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_reviews' => Review::with(['user', 'game'])->latest()->take(5)->get(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
    
    
    public function games()
    {
        $games = Game::with('categories')->latest()->paginate(15);
        return view('admin.games.index', compact('games'));
    }
    
    
    public function createGame()
    {
        $categories = Category::all();
        return view('admin.games.create', compact('categories'));
    }
    
    
public function storeGame(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'nullable|numeric|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        'categories' => 'required|array',
        'categories.*' => 'exists:categories,id'
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('games', 'public');
    }

    $game = Game::create([
        'title' => $validated['title'],
        'description' => $validated['description'],
        'price' => $validated['price'] ?? 0,
        'image' => $imagePath,
        'created_by' => auth()->id(),
    ]);

    $game->categories()->attach($validated['categories']);

    return redirect()->route('admin.games.index')
        ->with('success', 'Game created successfully!');
}


public function editGame(Game $game)
{
    $categories = Category::all();
    return view('admin.games.edit', compact('game', 'categories'));
}


    public function updateGame(Request $request, Game $game)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240', 
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        if ($request->hasFile('image')) {
            if ($game->image) {
                Storage::disk('public')->delete($game->image);
            }
            $validated['image'] = $request->file('image')->store('games', 'public');
        }

        $game->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'] ?? 0,
            'image' => $validated['image'] ?? $game->image,
        ]);

        $game->categories()->sync($validated['categories']);

        return redirect()->route('admin.games.index')
            ->with('success', 'Game updated successfully!');
    }

   
    public function destroyGame(Game $game)
    {
        
        $game->delete();
        
        return redirect()->route('admin.games.index')
            ->with('success', 'Game deleted successfully!');
    }
    
    
    public function categories()
    {
        $categories = Category::withCount('games')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }
    
    
    public function createCategory()
    {
        return view('admin.categories.create');
    }
    
   
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);
        
        Category::create($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }
    
   
    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }
    
   
    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);
        
        $category->update($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }
    
    
    public function destroyCategory(Category $category)
    {
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
    
   
    public function users()
    {
        $users = User::with('role')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }
    
    
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
   
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }
    
   
    public function destroyUser(User $user)
    {
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself!');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
    
   
    public function toggleAdmin(User $user)
    {
       
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own role!');
        }
        
       
        $user->update([
            'role_id' => $user->role_id === 1 ? 2 : 1
        ]);
        
        return back()->with('success', 'User role updated successfully!');
    }
    
  
    public function reviews()
    {
        $reviews = Review::with(['user', 'game'])->latest()->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }
    
   
    public function destroyReview(Review $review)
    {
        $review->delete();
        
        return back()->with('success', 'Review deleted successfully!');
    }
    
  
    public function statistics()
    {
        $stats = [
            'games_by_category' => Category::withCount('games')->get(),
            'top_rated_games' => Game::withAvg('reviews', 'rating')
                ->orderByDesc('reviews_avg_rating')
                ->take(10)
                ->get(),
            'most_active_users' => User::withCount('reviews')
                ->orderByDesc('reviews_count')
                ->take(10)
                ->get(),
        ];
        
        return view('admin.statistics', compact('stats'));
    }

    // ============ GAMES TRASHED ============
    public function trashedGames()
    {
        $games = Game::onlyTrashed()
            ->with('categories')
            ->latest('deleted_at')
            ->paginate(15);
        return view('admin.games.trashed', compact('games'));
    }

    public function restoreGame($id)
    {
        $game = Game::onlyTrashed()->findOrFail($id);
        $game->restore();
        
        return redirect()->route('admin.games.trashed')
            ->with('success', 'Game restored successfully!');
    }

    public function forceDestroyGame($id)
    {
        $game = Game::onlyTrashed()->findOrFail($id);
        
        if ($game->image) {
            Storage::disk('public')->delete($game->image);
        }
        
        $game->forceDelete();
        
        return redirect()->route('admin.games.trashed')
            ->with('success', 'Game permanently deleted!');
    }

 
    public function trashedCategories()
    {
        $categories = Category::onlyTrashed()
            ->withCount('games')
            ->latest('deleted_at')
            ->paginate(15);
        return view('admin.categories.trashed', compact('categories'));
    }

    public function restoreCategory($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        
        return redirect()->route('admin.categories.trashed')
            ->with('success', 'Category restored successfully!');
    }

    public function forceDestroyCategory($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();
        
        return redirect()->route('admin.categories.trashed')
            ->with('success', 'Category permanently deleted!');
    }

    
    public function trashedUsers()
    {
        $users = User::onlyTrashed()
            ->with('role')
            ->latest('deleted_at')
            ->paginate(15);
        return view('admin.users.trashed', compact('users'));
    }

    public function restoreUser($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        
        return redirect()->route('admin.users.trashed')
            ->with('success', 'User restored successfully!');
    }

    public function forceDestroyUser($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
        
        return redirect()->route('admin.users.trashed')
            ->with('success', 'User permanently deleted!');
    }

   
    public function trashedReviews()
    {
        $reviews = Review::onlyTrashed()
            ->with(['user', 'game'])
            ->latest('deleted_at')
            ->paginate(15);
        return view('admin.reviews.trashed', compact('reviews'));
    }

    public function restoreReview($id)
    {
        $review = Review::onlyTrashed()->findOrFail($id);
        $review->restore();
        
        return redirect()->route('admin.reviews.trashed')
            ->with('success', 'Review restored successfully!');
    }

    public function forceDestroyReview($id)
    {
        $review = Review::onlyTrashed()->findOrFail($id);
        $review->forceDelete();
        
        return redirect()->route('admin.reviews.trashed')
            ->with('success', 'Review permanently deleted!');
    }
}