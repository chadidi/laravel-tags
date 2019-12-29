<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::saving(function (Model $model) {
            collect($model->getTranslatedLocales('name'))
                ->each(function (string $locale) use ($model) {
                    $model->setTranslation(
                        'slug',
                        $locale,
                        $model->generateSlug($model->getTranslation('name', $locale))
                    );
                });
        });
    }

    protected function generateSlug(string $string): string
    {
        $slugger = config('tags.slugger');

        $slugger = $slugger ?: '\Illuminate\Support\Str::slug';

        return call_user_func($slugger, $string);
    }
}
