<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
   
    public function index()
    {
        $categories = Category::withCount('games')
            ->orderBy('name')
            ->get();
            
        return view('categories.index', compact('categories'));
    }
   
    public function create()
    {
        
    }
   
    public function store(Request $request)
    {
        
    }
    
    public function show(Category $category)
    {
        $games = $category->games()
            ->with(['categories' => function($query) {
                $query->withTrashed();
            }])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->paginate(12);

        return view('categories.show', compact('category', 'games'));
    }
    
    public function edit(string $id)
    {
        
    }
   
    public function update(Request $request, string $id)
    {
        
    }
   
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        
        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully');
    }
    
   
    public function trashed()
    {
        $categories = Category::onlyTrashed()
            ->withCount('games')
            ->orderBy('deleted_at', 'desc')
            ->get();
            
        return view('categories.trashed', compact('categories'));
    }

    public function restore(string $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        
        return redirect()
            ->route('categories.index')
            ->with('success', 'Category restored successfully');
    }

    public function forceDestroy(string $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();
        
        return redirect()
            ->route('categories.trashed')
            ->with('success', 'Category permanently deleted');
    }
}