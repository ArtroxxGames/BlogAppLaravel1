<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Genera automáticamente un slug único a partir de otro atributo
 * al crear el modelo. El slug no cambia al editar, para no romper enlaces.
 */
trait HasUniqueSlug
{
    abstract protected function slugSource(): string;

    protected static function bootHasUniqueSlug(): void
    {
        static::creating(function (self $model) {
            if (blank($model->slug)) {
                $model->slug = $model->uniqueSlug((string) $model->{$model->slugSource()});
            }
        });
    }

    protected function uniqueSlug(string $value): string
    {
        $base = Str::slug($value) ?: Str::lower(Str::random(8));
        $slug = $base;
        $suffix = 2;

        while (static::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
