<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Detecta consultas N+1 y asignaciones masivas silenciosas durante el desarrollo.
        Model::shouldBeStrict(! $this->app->isProduction());

        Route::resourceVerbs(['create' => 'crear', 'edit' => 'editar']);

        Gate::define('admin', fn (User $user) => $user->isAdmin());

        View::composer('layouts.partials.header', function ($view) {
            $view->with('featuredCategories', Category::featured()->orderBy('name')->limit(4)->get());
        });
    }
}
