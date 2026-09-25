<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Blog público
Route::get('/', HomeController::class)->name('home');
Route::get('/articulos/{article}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/categorias', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categorias/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/autores/{user}', [AuthorController::class, 'show'])->name('authors.show');

Route::middleware('auth')->group(function () {
    // Comentarios
    Route::post('/articulos/{article}/comentarios', [CommentController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('comments.store');
    Route::delete('/comentarios/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Panel del autor
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('dashboard/articulos', Dashboard\ArticleController::class)
        ->except('show')
        ->parameters(['articulos' => 'article'])
        ->names('dashboard.articles');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Administración
    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('categorias', Admin\CategoryController::class)
            ->except('show')
            ->parameters(['categorias' => 'category'])
            ->names('categories');
        Route::get('comentarios', [Admin\CommentController::class, 'index'])->name('comments.index');
        Route::get('usuarios', [Admin\UserController::class, 'index'])->name('users.index');
        Route::patch('usuarios/{user}/admin', [Admin\UserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    });
});

require __DIR__.'/auth.php';
