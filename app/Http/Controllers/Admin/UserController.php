<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->trim()->toString();

        $users = User::query()
            ->when($search !== '', fn ($query) => $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")))
            ->withCount(['articles', 'comments'])
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function toggleAdmin(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->with('error', 'No puedes quitarte a ti mismo los permisos de administrador.');
        }

        $user->forceFill(['is_admin' => ! $user->is_admin])->save();

        return back()->with('success', $user->is_admin
            ? "{$user->name} ahora es administrador."
            : "{$user->name} ya no es administrador.");
    }
}
