<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProposalController;
use App\Http\Controllers\ChurchController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/cekproposal', [PublicProposalController::class, 'index'])->name('public.proposals.index');
Route::get('/cekproposal/{proposal}', [PublicProposalController::class, 'show'])->name('public.proposals.show');

// Route admin login dihapus karena Filament sudah handle otomatis

require __DIR__.'/auth.php';
