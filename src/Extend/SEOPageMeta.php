<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd\Extend;

use Flarum\Settings\SettingsRepositoryInterface;
use GlowingBlue\Localizd\Helpers\DynamicKeys;
use Psr\Http\Message\ServerRequestInterface;
use V17Development\FlarumSeo\Page\PageDriverInterface;
use V17Development\FlarumSeo\SeoProperties;

class SEOPageMeta implements PageDriverInterface
{
    public function __construct(
        protected SettingsRepositoryInterface $settings,
        protected DynamicKeys $dynamicKeys
    ) {
    }

    public function extensionDependencies(): array
    {
        return [];
    }

    public function handleRoutes(): array
    {
        return ['default', 'index'];
    }

    public function handle(
        ServerRequestInterface $request,
        SeoProperties $properties
    ) {
        $properties->setDescription($this->dynamicKeys->settingOrTranslation('forum_description'));
        $keywords = $this->dynamicKeys->settingOrTranslation('forum_keywords') ?? [];
        $properties->setKeywords($keywords);
        $properties->setTitle($this->dynamicKeys->settingOrTranslation('forum_title'));
    }
}
