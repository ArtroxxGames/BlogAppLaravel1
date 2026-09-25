<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory, HasUniqueSlug;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'is_featured',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }

    protected function slugSource(): string
    {
        return 'name';
    }

    protected static function booted(): void
    {
        static::deleted(function (Category $category) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
        });
    }

    /** @return HasMany<Article, $this> */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    #[Scope]
    protected function visible(Builder $query): void
    {
        $query->where('is_visible', true);
    }

    #[Scope]
    protected function featured(Builder $query): void
    {
        $query->visible()->where('is_featured', true);
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
    }
}
