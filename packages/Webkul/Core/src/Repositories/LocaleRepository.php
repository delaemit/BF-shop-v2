<?php

declare(strict_types=1);

namespace Webkul\Core\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Contracts\Locale;
use Webkul\Core\Eloquent\Repository;

class LocaleRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Locale::class;
    }

    /**
     * Create.
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch('core.locale.create.before');

        $locale = parent::create($attributes);

        $this->uploadImage($attributes, $locale);

        Event::dispatch('core.locale.create.after', $locale);

        return $locale;
    }

    /**
     * Update.
     *
     * @param array $attributes
     * @param mixed $id
     *
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        Event::dispatch('core.locale.update.before', $id);

        $locale = parent::update($attributes, $id);

        $this->uploadImage($attributes, $locale);

        Event::dispatch('core.locale.update.after', $locale);

        return $locale;
    }

    /**
     * Delete.
     *
     * @param int $id
     *
     * @return void
     */
    public function delete($id): void
    {
        Event::dispatch('core.locale.delete.before', $id);

        $locale = parent::find($id);

        $locale->delete($id);

        Storage::delete((string) $locale->logo_path);

        Event::dispatch('core.locale.delete.after', $id);
    }

    /**
     * Upload image.
     *
     * @param array $attributes
     * @param \Webkul\Core\Models\Locale $locale
     * @param mixed $localeImages
     *
     * @return void
     */
    public function uploadImage($localeImages, $locale): void
    {
        if (!isset($localeImages['logo_path'])) {
            if (!empty($localeImages['logo_path'])) {
                Storage::delete((string) $locale->logo_path);
            }

            $locale->logo_path = null;

            $locale->save();

            return;
        }

        foreach ($localeImages['logo_path'] as $image) {
            if ($image instanceof UploadedFile) {
                $locale->logo_path = $image->storeAs(
                    'locales',
                    $locale->code . '.' . $image->getClientOriginalExtension()
                );

                $locale->save();
            }
        }
    }
}
