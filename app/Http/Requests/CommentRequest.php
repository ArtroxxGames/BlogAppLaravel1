<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'body' => ['required', 'string', 'min:3', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'rating' => 'calificación',
            'body' => 'comentario',
        ];
    }
}
