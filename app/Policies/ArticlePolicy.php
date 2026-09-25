<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    /**
     * Los borradores y los artículos de categorías ocultas solo los ven
     * su autor y los administradores.
     */
    public function view(?User $user, Article $article): bool
    {
        if ($article->isPublished() && $article->category->is_visible) {
            return true;
        }

        return $user !== null && $this->update($user, $article);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Article $article): bool
    {
        return $user->id === $article->user_id || $user->isAdmin();
    }

    public function delete(User $user, Article $article): bool
    {
        return $this->update($user, $article);
    }
}
