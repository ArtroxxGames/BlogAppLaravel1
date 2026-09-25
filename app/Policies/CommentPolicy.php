<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    public function create(User $user, Article $article): Response
    {
        if (! $article->isPublished()) {
            return Response::deny('Solo se pueden comentar artículos publicados.');
        }

        if ($article->comments()->whereBelongsTo($user, 'author')->exists()) {
            return Response::deny('Ya has comentado este artículo.');
        }

        return Response::allow();
    }

    /**
     * Puede borrar un comentario su autor, el autor del artículo o un administrador.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id
            || $user->id === $comment->article->user_id
            || $user->isAdmin();
    }
}
