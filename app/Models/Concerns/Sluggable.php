<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait Sluggable
{
    /**
     * Define in the model: protected string $slugFrom = 'title'|'name';
     * Optional: protected string $slugField = 'slug';
     */
    public static function bootSluggable(): void
    {
        static::creating(function ($model) {
            $slugField = $model->slugField ?? 'slug';
            $from = $model->slugFrom ?? 'title';

            if (empty($model->{$slugField}) && !empty($model->{$from})) {
                $model->{$slugField} = static::makeUniqueSlug($model, Str::slug($model->{$from}));
            }
        });

        static::updating(function ($model) {
            $slugField = $model->slugField ?? 'slug';
            $from = $model->slugFrom ?? 'title';

            // Si el campo base cambió y quieres actualizar el slug automáticamente:
            if ($model->isDirty($from)) {
                $model->{$slugField} = static::makeUniqueSlug($model, Str::slug($model->{$from}));
            }
        });
    }

    protected static function makeUniqueSlug($model, string $base): string
    {
        $slugField = $model->slugField ?? 'slug';
        $original = $base ?: Str::random(8);
        $slug = $original;
        $i = 2;

        while ($model->newQuery()->where($slugField, $slug)->when($model->exists, function ($q) use ($model, $slugField) {
            $q->where('id', '!=', $model->id);
        })->exists()) {
            $slug = $original.'-'.$i;
            $i++;
        }

        return $slug;
    }

    // Route Model Binding por slug (opcional)
    public function getRouteKeyName(): string
    {
        return $this->slugField ?? 'slug';
    }
}

