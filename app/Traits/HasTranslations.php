<?php

namespace App\Traits;

use App\Models\Translation;

trait HasTranslations
{
    /**
     * Polymorphic relationship to translations.
     */
    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * Get a translated value for a field, falling back to the original column.
     *
     * @param string      $field  The field name (e.g. 'name', 'description')
     * @param string|null $locale The locale code (e.g. 'ur', 'ar'). Defaults to app locale.
     * @return string
     */
    public function trans(string $field, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        // English is stored in the original columns — no lookup needed
        if ($locale === 'en') {
            return $this->{$field} ?? '';
        }

        // If translations are eager-loaded, search the collection
        if ($this->relationLoaded('translations')) {
            $translation = $this->translations
                ->where('locale', $locale)
                ->where('field', $field)
                ->first();
        } else {
            // Fallback to a query (avoid N+1 by eager-loading where possible)
            $translation = $this->translations()
                ->where('locale', $locale)
                ->where('field', $field)
                ->first();
        }

        // Return translation if it exists and is non-empty, otherwise fall back to English
        return (!empty($translation?->value)) ? $translation->value : ($this->{$field} ?? '');
    }

    /**
     * Bulk save translations for a given locale.
     *
     * @param string $locale The locale code
     * @param array  $fields Associative array of field => value
     */
    public function setTranslations(string $locale, array $fields): void
    {
        foreach ($fields as $field => $value) {
            $value = trim((string) $value);

            if ($value === '') {
                // Remove empty translations to keep the table clean
                $this->translations()
                    ->where('locale', $locale)
                    ->where('field', $field)
                    ->delete();
                continue;
            }

            $this->translations()->updateOrCreate(
                ['locale' => $locale, 'field' => $field],
                ['value' => $value]
            );
        }
    }

    /**
     * Get all translations for this model grouped by locale.
     *
     * @return array  e.g. ['ur' => ['name' => 'برگر', 'description' => '...'], 'ar' => [...]]
     */
    public function getTranslationsGrouped(): array
    {
        $grouped = [];
        $translations = $this->relationLoaded('translations')
            ? $this->translations
            : $this->translations()->get();

        foreach ($translations as $t) {
            $grouped[$t->locale][$t->field] = $t->value;
        }

        return $grouped;
    }
}
