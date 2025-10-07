<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProposalController;
use App\Http\Controllers\ChurchController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicPostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', [ChurchController::class, 'index'])->name('home');
Route::get('/churches', [ChurchController::class, 'list'])->name('churches.index');
Route::get('/churches/{church}', [ChurchController::class, 'show'])->name('churches.show');

// Public posts routes
Route::get('/berita', [PublicPostController::class, 'index'])->name('public.posts.index');
Route::get('/berita/{post:slug}', [PublicPostController::class, 'show'])->name('public.posts.show');
Route::get('/kategori/{category:slug}', [PublicPostController::class, 'category'])->name('public.posts.category');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Post routes
    Route::resource('posts', PostController::class);
    Route::patch('/posts/{post}/toggle-featured', [PostController::class, 'toggleFeatured'])->name('posts.toggle-featured');
    Route::patch('/posts/{post}/toggle-status', [PostController::class, 'toggleStatus'])->name('posts.toggle-status');
    
    // Category routes
    Route::resource('categories', CategoryController::class);
    Route::patch('/categories/{category}/toggle-active', [CategoryController::class, 'toggleActive'])->name('categories.toggle-active');
    
    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Superadmin routes
        Route::middleware('superadmin')->group(function () {
            Route::get('/users', [AdminController::class, 'users'])->name('users');
            Route::get('/users/{user}/reset-password', [AdminController::class, 'showResetPasswordForm'])->name('reset-password');
            Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('reset-password.store');
        });
        
        // Admin gereja routes
        Route::middleware('admin_gereja')->group(function () {
            Route::get('/edit-church', [AdminController::class, 'editChurch'])->name('edit-church');
            Route::patch('/edit-church', [AdminController::class, 'updateChurch'])->name('edit-church.update');
        });
    });
});

Route::get('/cekproposal', [PublicProposalController::class, 'index'])->name('public.proposals.index');
Route::get('/cekproposal/{proposal}', [PublicProposalController::class, 'show'])->name('public.proposals.show');

// Route admin login dihapus karena Filament sudah handle otomatis

require __DIR__.'/auth.php';
