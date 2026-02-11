<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

trait Translatable
{
    /**
     * Get the translations for the model.
     */
    public function translations(): HasMany
    {
        return $this->hasMany($this->getTranslationModelName());
    }

    /**
     * Get the translation model name.
     */
    protected function getTranslationModelName(): string
    {
        return get_class($this).'Translation';
    }

    /**
     * Get the translation for a specific locale.
     */
    public function getTranslation(?string $locale = null)
    {
        $locale = $locale ?? App::getLocale();

        return $this->translations->where('locale', $locale)->first();
    }

    /**
     * Magic method to get translated attributes.
     */
    public function __get($key)
    {
        if (in_array($key, $this->translatable ?? [])) {
            $translation = $this->getTranslation();

            if ($translation && $translation->$key) {
                return $translation->$key;
            }

            // Fallback to fallback locale if current locale is missing
            $fallback = config('app.fallback_locale');
            if (App::getLocale() !== $fallback) {
                $translation = $this->getTranslation($fallback);
                if ($translation && $translation->$key) {
                    return $translation->$key;
                }
            }

            return null; // Or return original attribute if it existed in main table (but we removed them)
        }

        return parent::__get($key);
    }
}
