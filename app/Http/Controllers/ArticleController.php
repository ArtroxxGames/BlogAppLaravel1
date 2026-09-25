<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function show(Request $request, Article $article): View
    {
        Gate::authorize('view', $article);

        $article->load(['author', 'category'])
            ->loadCount('comments')
            ->loadAvg('comments', 'rating');

        $comments = $article->comments()
            ->with('author')
            ->latest()
            ->paginate(10);

        // La policy de borrado consulta el artículo de cada comentario.
        $comments->getCollection()->each->setRelation('article', $article);

        $related = Article::published()
            ->whereBelongsTo($article->category)
            ->whereKeyNot($article->getKey())
            ->with(['author', 'category'])
            ->withCount('comments')
            ->withAvg('comments', 'rating')
            ->latest('published_at')
            ->limit(3)
            ->get();

        $canComment = $request->user()?->can('create', [Comment::class, $article]) ?? false;

        return view('blog.articles.show', compact('article', 'comments', 'related', 'canComment'));
    }
}
