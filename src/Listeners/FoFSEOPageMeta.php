<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd\Listeners;

use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use FoF\Seo\Event\PreparingPageMeta;
use GlowingBlue\Localizd\Helpers\DynamicKeys;

class FoFSEOPageMeta
{
    public function __construct(
        protected SettingsRepositoryInterface $settings,
        protected DynamicKeys $dynamicKeys,
        protected UrlGenerator $url
    ) {
    }

    public function handle(PreparingPageMeta $event): void
    {
        $properties = $event->properties;

        $forumTitle = $this->dynamicKeys->settingOrTranslation('forum_title');
        $forumDescription = $this->dynamicKeys->settingOrTranslation('forum_description');

        $applicationUrl = $this->url->to('forum')->base();
        $logo = $this->settings->get('logo_path');

        $properties->setSchemaJson('publisher', [
            '@type'       => 'Organization',
            'name'        => $forumTitle,
            'url'         => $applicationUrl,
            'description' => $forumDescription,
            'logo'        => $logo ? $applicationUrl.'/assets/'.$logo : null,
        ]);
    }
}
