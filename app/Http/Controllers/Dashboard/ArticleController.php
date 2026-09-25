<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $articles = $request->user()->articles()
            ->with('category')
            ->withCount('comments')
            ->search($request->string('q')->toString())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('dashboard.articles.create', [
            'article' => new Article,
            'categories' => $this->categories(),
        ]);
    }

    public function store(ArticleRequest $request): RedirectResponse
    {
        $article = new Article($request->articleData());
        $article->author()->associate($request->user());

        if ($request->hasFile('cover')) {
            $article->cover_path = $request->file('cover')->store('articles', 'public');
        }

        $article->save();

        return redirect()->route('dashboard.articles.index')
            ->with('success', 'Artículo creado correctamente.');
    }

    public function edit(Article $article): View
    {
        Gate::authorize('update', $article);

        return view('dashboard.articles.edit', [
            'article' => $article,
            'categories' => $this->categories(),
        ]);
    }

    public function update(ArticleRequest $request, Article $article): RedirectResponse
    {
        Gate::authorize('update', $article);

        $article->fill($request->articleData());

        if ($request->hasFile('cover') || $request->boolean('remove_cover')) {
            if ($article->cover_path) {
                Storage::disk('public')->delete($article->cover_path);
            }

            $article->cover_path = $request->file('cover')?->store('articles', 'public');
        }

        $article->save();

        return redirect()->route('dashboard.articles.index')
            ->with('success', 'Artículo actualizado correctamente.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        Gate::authorize('delete', $article);

        $article->delete();

        return redirect()->route('dashboard.articles.index')
            ->with('success', 'Artículo eliminado.');
    }

    private function categories()
    {
        return Category::visible()->orderBy('name')->get(['id', 'name']);
    }
}
