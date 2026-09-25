<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::visible()
            ->withCount(['articles' => fn (Builder $query) => $query->published()])
            ->orderBy('name')
            ->get();

        return view('blog.categories.index', compact('categories'));
    }

    public function show(Category $category): View
    {
        abort_unless($category->is_visible, 404);

        $articles = $category->articles()
            ->published()
            ->with(['author', 'category'])
            ->withCount('comments')
            ->withAvg('comments', 'rating')
            ->latest('published_at')
            ->paginate(9);

        return view('blog.categories.show', compact('category', 'articles'));
    }
}
