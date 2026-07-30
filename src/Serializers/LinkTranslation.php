<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd\Serializers;

use FoF\Links\Api\Serializer\LinkSerializer;
use FoF\Links\Link;
use GlowingBlue\Localizd\Helpers\DynamicKeys;

class LinkTranslation
{
    protected DynamicKeys $dynamicKeys;

    /**
     * @param DynamicKeys $dynamicKeys
     */
    public function __construct(DynamicKeys $dynamicKeys)
    {
        $this->dynamicKeys = $dynamicKeys;
    }

    /**
     * Modifies specified attrs to provide translations where set (fof/links).
     *
     * @param LinkSerializer $serializer
     * @param Link           $link
     * @param array          $attributes
     *
     * @return array
     */
    public function __invoke(LinkSerializer $serializer, Link $link, array $attributes): array
    {
        // Support for programatically added links introduced in fof/links 1.4.0
        if ($link->exists()) {
            $attributes['title'] = $this->dynamicKeys->objectOrTranslation($link, 'title');
            $attributes['url'] = $this->dynamicKeys->objectOrTranslation($link, 'url');
        }

        return $attributes;
    }
}
