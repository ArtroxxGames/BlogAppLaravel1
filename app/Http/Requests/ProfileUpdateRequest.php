<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'profession' => ['nullable', 'string', 'max:60'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'remove_avatar' => ['boolean'],
            'twitter_url' => ['nullable', 'url:https,http', 'max:255'],
            'linkedin_url' => ['nullable', 'url:https,http', 'max:255'],
            'github_url' => ['nullable', 'url:https,http', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'profession' => 'profesión',
            'bio' => 'biografía',
            'avatar' => 'foto de perfil',
            'twitter_url' => 'Twitter / X',
            'linkedin_url' => 'LinkedIn',
            'github_url' => 'GitHub',
        ];
    }
}
