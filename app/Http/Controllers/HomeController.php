<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = $request->string('q')->trim()->toString();

        $articles = Article::published()
            ->search($search)
            ->with(['author', 'category'])
            ->withCount('comments')
            ->withAvg('comments', 'rating')
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('blog.home', compact('articles', 'search'));
    }
}
