<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_admin' => false,
        'profession' => null,
        'bio' => null,
        'avatar_path' => null,
        'twitter_url' => null,
        'linkedin_url' => null,
        'github_url' => null,
    ];

    /**
     * `is_admin` queda fuera a propósito: solo se cambia desde el panel de administración.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profession',
        'bio',
        'avatar_path',
        'twitter_url',
        'linkedin_url',
        'github_url',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Los artículos se borran en cascada desde la base de datos, lo que no
     * dispara eventos de modelo; por eso aquí se eliminan sus archivos.
     */
    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            $files = $user->articles()->whereNotNull('cover_path')->pluck('cover_path')
                ->push($user->avatar_path)
                ->filter()
                ->all();

            Storage::disk('public')->delete($files);
        });
    }

    /** @return HasMany<Article, $this> */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /** @return HasMany<Comment, $this> */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function avatarUrl(): ?string
    {
        return $this->avatar_path ? Storage::disk('public')->url($this->avatar_path) : null;
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn (string $word) => Str::upper(Str::substr($word, 0, 1)))
            ->implode('');
    }

    /**
     * @return array<string, string>
     */
    public function socialLinks(): array
    {
        return array_filter([
            'Twitter / X' => $this->twitter_url,
            'LinkedIn' => $this->linkedin_url,
            'GitHub' => $this->github_url,
        ]);
    }
}
