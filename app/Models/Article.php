<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory, HasUniqueSlug;

    /** Palabras por minuto usadas para estimar el tiempo de lectura. */
    private const WORDS_PER_MINUTE = 200;

    protected $fillable = [
        'category_id',
        'title',
        'excerpt',
        'body',
        'cover_path',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    protected function slugSource(): string
    {
        return 'title';
    }

    protected static function booted(): void
    {
        static::deleted(function (Article $article) {
            if ($article->cover_path) {
                Storage::disk('public')->delete($article->cover_path);
            }
        });
    }

    /** @return BelongsTo<User, $this> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** @return HasMany<Comment, $this> */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Artículos publicados cuya categoría es visible.
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereHas('category', fn (Builder $q) => $q->visible());
    }

    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $term = trim((string) $term);

        if ($term === '') {
            return;
        }

        $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $term).'%';

        $query->where(fn (Builder $q) => $q
            ->where('title', 'like', $like)
            ->orWhere('excerpt', 'like', $like));
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->isPast();
    }

    public function isScheduled(): bool
    {
        return $this->published_at !== null && $this->published_at->isFuture();
    }

    public function coverUrl(): ?string
    {
        return $this->cover_path ? Storage::disk('public')->url($this->cover_path) : null;
    }

    /**
     * El cuerpo se escribe en Markdown. El HTML crudo se elimina para evitar XSS.
     */
    public function bodyHtml(): HtmlString
    {
        return new HtmlString(Str::markdown($this->body, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]));
    }

    public function readingTime(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->body)) / self::WORDS_PER_MINUTE));
    }
}
