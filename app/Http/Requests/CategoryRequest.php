<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('admin');
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:40', Rule::unique('categories')->ignore($this->route('category'))],
            'description' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_featured' => ['boolean'],
            'is_visible' => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripción',
            'image' => 'imagen',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function categoryData(): array
    {
        return [
            ...$this->safe()->only(['name', 'description']),
            'is_featured' => $this->boolean('is_featured'),
            'is_visible' => $this->boolean('is_visible'),
        ];
    }
}
