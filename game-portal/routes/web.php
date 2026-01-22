<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\GameExportController;

use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/load-more-games', [HomeController::class, 'loadMore'])->name('games.loadMore');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-library', [DashboardController::class, 'library'])->name('user.library');
    Route::get('/my-reviews', [DashboardController::class, 'reviews'])->name('user.reviews');
    Route::get('/wishlist', [DashboardController::class, 'wishlist'])->name('user.wishlist');
    Route::get('/achievements', [DashboardController::class, 'achievements'])->name('user.achievements');
    Route::get('/friends', [DashboardController::class, 'friends'])->name('user.friends');
});


Route::middleware('auth')->group(function () {
    Route::post('/library/add', [DashboardController::class, 'addToLibrary'])->name('library.add');
    Route::delete('/library/remove', [DashboardController::class, 'removeFromLibrary'])->name('library.remove');

    Route::post('/wishlist/add', [DashboardController::class, 'addToWishlist'])->name('wishlist.add');
    Route::delete('/wishlist/remove', [DashboardController::class, 'removeFromWishlist'])->name('wishlist.remove');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/games/{game}', [GameController::class, 'show'])->name('games.show');


Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');


Route::middleware('auth')->group(function () {
    Route::post('/games/{game}/reviews', [ReviewController::class, 'store'])
        ->middleware('must.own')
        ->name('reviews.store');
        
    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});



Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    
    Route::get('/notifications', function () {
        $admin = auth()->user();
        $notifications = $admin->notifications()->latest()->get();
        return view('admin.notifications', compact('notifications'));
    })->name('notifications');

   
    Route::delete('/notifications/{notification}', function($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return redirect()->back()->with('success', 'Notification deleted successfully.');
    })->name('notifications.delete');

   
    Route::get('/games', [AdminController::class, 'games'])->name('games.index');
    Route::get('/games/trashed', [AdminController::class, 'trashedGames'])->name('games.trashed');
    Route::get('/games/create', [AdminController::class, 'createGame'])->name('games.create');
    Route::post('/games', [AdminController::class, 'storeGame'])->name('games.store');
    Route::get('/games/{game}/edit', [AdminController::class, 'editGame'])->name('games.edit');
    Route::patch('/games/{game}', [AdminController::class, 'updateGame'])->name('games.update');
    Route::delete('/games/{game}', [AdminController::class, 'destroyGame'])->name('games.destroy');
    Route::post('/games/{id}/restore', [AdminController::class, 'restoreGame'])->name('games.restore');
    Route::delete('/games/{id}/force', [AdminController::class, 'forceDestroyGame'])->name('games.force-destroy');
    
    Route::get('/games/export', [GameExportController::class, 'export'])->name('games.export');

   
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
    Route::get('/categories/trashed', [AdminController::class, 'trashedCategories'])->name('categories.trashed');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
    Route::patch('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');
    Route::post('/categories/{id}/restore', [AdminController::class, 'restoreCategory'])->name('categories.restore');
    Route::delete('/categories/{id}/force', [AdminController::class, 'forceDestroyCategory'])->name('categories.force-destroy');

    
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/trashed', [AdminController::class, 'trashedUsers'])->name('users.trashed');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::patch('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::post('/users/{id}/restore', [AdminController::class, 'restoreUser'])->name('users.restore');
    Route::delete('/users/{id}/force', [AdminController::class, 'forceDestroyUser'])->name('users.force-destroy');

    
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews.index');
    Route::get('/reviews/trashed', [AdminController::class, 'trashedReviews'])->name('reviews.trashed');
    Route::delete('/reviews/{review}', [AdminController::class, 'destroyReview'])->name('reviews.destroy');
    Route::post('/reviews/{id}/restore', [AdminController::class, 'restoreReview'])->name('reviews.restore');
    Route::delete('/reviews/{id}/force', [AdminController::class, 'forceDestroyReview'])->name('reviews.force-destroy');

    
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
});

Route::middleware('guest')->group(function () {
   
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
        ->name('password.request.custom');

    Route::post('/forgot-password/find', [ForgotPasswordController::class, 'findAccount'])
        ->name('password.find');

   
    Route::get('/forgot-password/verify', [ForgotPasswordController::class, 'showVerifyForm'])
        ->name('password.verify.step');

    Route::post('/forgot-password/verify', [ForgotPasswordController::class, 'verifyEmail'])
        ->name('password.verify.email');

    Route::get('/reset-password/custom', [ForgotPasswordController::class, 'showResetForm'])
        ->name('password.reset.form');

    Route::post('/reset-password/custom', [ForgotPasswordController::class, 'resetPassword'])
        ->name('password.reset.custom');
});

require __DIR__.'/auth.php';