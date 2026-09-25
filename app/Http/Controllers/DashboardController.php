<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $receivedComments = Comment::whereHas('article', fn (Builder $q) => $q->whereBelongsTo($user, 'author'));

        $stats = [
            'published' => $user->articles()->whereNotNull('published_at')->where('published_at', '<=', now())->count(),
            'drafts' => $user->articles()->where(fn (Builder $q) => $q->whereNull('published_at')->orWhere('published_at', '>', now()))->count(),
            'comments' => (clone $receivedComments)->count(),
            'rating' => round((float) (clone $receivedComments)->avg('rating'), 1),
        ];

        $latestComments = $receivedComments
            ->with(['author', 'article'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'latestComments'));
    }
}
