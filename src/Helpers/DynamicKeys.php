<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd\Helpers;

use Flarum\Database\AbstractModel;
use Flarum\Extension\ExtensionManager;
use Flarum\Foundation\Paths;
use Flarum\Locale\LocaleManager;
use Flarum\Locale\Translator;
use Flarum\Settings\SettingsRepositoryInterface;
use GlowingBlue\Localizd\Helpers\Models\AbstractDynamicModel;
use GlowingBlue\Localizd\Helpers\Models\Groups;
use GlowingBlue\Localizd\Helpers\Models\Links;
use GlowingBlue\Localizd\Helpers\Models\ProfileFields;
use GlowingBlue\Localizd\Helpers\Models\Reactions;
use GlowingBlue\Localizd\Helpers\Models\Tags;
use GlowingBlue\Localizd\Helpers\Models\Taxonomies;
use GlowingBlue\Localizd\Helpers\Models\TaxonomyTerms;
use GlowingBlue\Localizd\Helpers\Models\TermsPolicies;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Symfony\Component\Yaml\Yaml;

class DynamicKeys
{
    protected SettingsRepositoryInterface $settings;

    protected Translator $translator;

    protected LocaleManager $locale;

    protected ExtensionManager $extensions;

    protected Paths $paths;

    protected Filesystem $fs;

    const dynamicPrefix = 'glowingblue-localizd.dynamic.';

    protected static $models = [
        Tags::class,
        Links::class,
        TermsPolicies::class,
        Taxonomies::class,
        TaxonomyTerms::class,
        Reactions::class,
        Groups::class,
        ProfileFields::class,
    ];

    public function __construct(SettingsRepositoryInterface $settings, Translator $translator, LocaleManager $locale, ExtensionManager $extensions, Paths $paths, Filesystem $fs)
    {
        $this->settings = $settings;
        $this->translator = $translator;
        $this->locale = $locale;
        $this->extensions = $extensions;
        $this->paths = $paths;
        $this->fs = $fs;
    }

    public static function addDynamicModel(string $model): void
    {
        self::$models[] = $model;
    }

    /**
     * Collect and store supported dynamic keys in our cached locale file. This will persist on the filesystem
     * to aid performance when there are a large number of tags, links, terms, taxonomies, etc.
     *
     * To force a rebuild, for example after creating a new tag, pass `true` to this method.
     *
     * @param bool $force
     *
     * @return void
     */
    public function collect(bool $force = false): void
    {
        $path = $this->paths->storage.DIRECTORY_SEPARATOR.'localizd';
        $file = $path.DIRECTORY_SEPARATOR.'en.yml';

        // If the file already exists,
        if ($this->fs->exists($file) && !$force) {
            return;
        }

        $allKeys = [];

        $this->registerModels($allKeys);

        $allKeys = Arr::add($allKeys, 'glowingblue-localizd.dynamic.refreshed_at', Carbon::now()->toISOString());

        $yaml = Yaml::dump($allKeys, 10, 2);
        // Clear the yml file so that all values are empty and not ''
        // If we don't do that, there might be dynamic values serialized as ''.
        $yaml = str_replace(" ''", '', $yaml);

        if (!$this->fs->isDirectory($path)) {
            $this->fs->makeDirectory($path);
        }

        $this->fs->put($file, $yaml);
    }

    public function registerModels(array &$keys): void
    {
        foreach (self::$models as $model) {
            /** @var AbstractDynamicModel $model */
            $extensionId = $model::getExtensionId();
            if ($extensionId && !$this->extensions->isEnabled($extensionId)) {
                continue;
            }

            $modelClass = $model::getModelClass();
            if ($modelClass && !class_exists($modelClass)) {
                continue;
            }

            $this->addKeys($keys, $model::getAll(), $model::getKeys());
        }
    }

    private function addKeys(array &$keys, Collection $resources, array $props)
    {
        foreach ($resources as $resource) {
            foreach ($props as $prop) {
                $this->addKey($keys, $resource, $prop);
            }
        }
    }

    private function addKey(array &$keys, AbstractModel $model, string $prop)
    {
        $key = $this->makeKey($model, $prop);

        // Algorithm found here: https://stackoverflow.com/questions/9635968/convert-dot-syntax-like-this-that-other-to-multi-dimensional-array-in-php
        $parts = explode('.', $key);
        foreach ($parts as $part) {
            $keys = &$keys[$part];
        }
        $keys = '';
    }

    /**
     * Create the dynamic translation key.
     *
     * @param AbstractModel $model
     * @param string        $prop
     *
     * @return string
     */
    private function makeKey(AbstractModel $model, string $prop): string
    {
        return self::dynamicPrefix.$this->keyType($model)."$model->id.$prop";
    }

    public function clearCache(): void
    {
        $this->locale->clearCache();
    }

    /**
     * Get a translation for a value usually stored in the DB.
     *
     * @param string $key
     *
     * @return string|null
     */
    public function settingOrTranslation(string $key): ?string
    {
        $check = "glowingblue-localizd.forum.$key";
        $translation = $this->translator->trans($check);

        if ($translation === $check) {
            $translation = $this->settings->get($key);
        }

        return $translation;
    }

    /**
     * Returns either the translated value for the supplied `prop` (name, description),
     * if available, or the value stored in the DB otherwise.
     *
     * @param AbstractModel $model
     * @param string        $prop
     *
     * @return string|null
     */
    public function objectOrTranslation(AbstractModel $model, string $prop, ?string $locale = null): ?string
    {
        $check = self::dynamicPrefix.$this->keyType($model)."$model->id.$prop";
        $translation = $this->translator->trans($check, [], null, $locale);
        if ($translation === $check) {
            $translation = $model->{$prop};
        }

        return $translation;
    }

    /**
     * Determine the partial dynamic key prefix.
     *
     * @param AbstractModel $model
     *
     * @return string
     */
    private function keyType(AbstractModel $model): string
    {
        // Find the dynamic model that matches the class name of the model
        $models = array_filter(self::$models, function ($class) use ($model) {
            /** @var AbstractDynamicModel $class */
            return $class::getModelClass() === get_class($model);
        });

        // Get the first model
        /** @var AbstractDynamicModel $dynModel */
        $dynModel = array_shift($models);

        // Get the key type
        return $dynModel::getKeyType();
    }
}
