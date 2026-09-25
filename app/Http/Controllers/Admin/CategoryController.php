<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('articles')->orderBy('name')->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create', ['category' => new Category(['is_visible' => true])]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = new Category($request->categoryData());

        if ($request->hasFile('image')) {
            $category->image_path = $request->file('image')->store('categories', 'public');
        }

        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->fill($request->categoryData());

        if ($request->hasFile('image')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }

            $category->image_path = $request->file('image')->store('categories', 'public');
        }

        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->articles()->exists()) {
            return back()->with('error', 'No se puede eliminar una categoría que tiene artículos. Ocúltala o mueve sus artículos primero.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada.');
    }
}
