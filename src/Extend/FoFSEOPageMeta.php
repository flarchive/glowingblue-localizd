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

use FoF\Seo\Page\PageDriverInterface;
use FoF\Seo\SeoProperties;
use GlowingBlue\Localizd\Helpers\DynamicKeys;
use Psr\Http\Message\ServerRequestInterface;

class FoFSEOPageMeta implements PageDriverInterface
{
    public function __construct(
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
    ): void {
        $properties->setDescription($this->dynamicKeys->settingOrTranslation('forum_description'));
        $properties->setKeywords($this->dynamicKeys->settingOrTranslation('forum_keywords') ?? []);

        $title = $this->dynamicKeys->settingOrTranslation('forum_title');

        if ($title) {
            $properties->setTitle($title);
        }
    }
}
