<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function show(User $user): View
    {
        $articles = $user->articles()
            ->published()
            ->with(['author', 'category'])
            ->withCount('comments')
            ->withAvg('comments', 'rating')
            ->latest('published_at')
            ->paginate(9);

        return view('blog.authors.show', ['author' => $user, 'articles' => $articles]);
    }
}
