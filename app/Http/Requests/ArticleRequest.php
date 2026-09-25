<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'excerpt' => ['required', 'string', 'min:10', 'max:300'],
            'body' => ['required', 'string', 'min:20'],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')->where('is_visible', true)],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_cover' => ['boolean'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'excerpt' => 'resumen',
            'body' => 'contenido',
            'category_id' => 'categoría',
            'cover' => 'imagen de portada',
            'status' => 'estado',
            'published_at' => 'fecha de publicación',
        ];
    }

    /**
     * Datos listos para guardar en el modelo (sin archivos).
     *
     * @return array<string, mixed>
     */
    public function articleData(): array
    {
        return [
            ...$this->safe()->only(['title', 'excerpt', 'body', 'category_id']),
            'published_at' => $this->input('status') === 'published'
                ? ($this->date('published_at') ?? now())
                : null,
        ];
    }
}
