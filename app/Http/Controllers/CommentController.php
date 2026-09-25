<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Article $article): RedirectResponse
    {
        Gate::authorize('create', [Comment::class, $article]);

        $comment = new Comment($request->validated());
        $comment->author()->associate($request->user());
        $article->comments()->save($comment);

        return redirect()
            ->to(route('articles.show', $article).'#comentarios')
            ->with('success', '¡Gracias por tu comentario!');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Comentario eliminado.');
    }
}
