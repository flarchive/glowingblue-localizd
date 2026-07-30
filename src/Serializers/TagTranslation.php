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

use Flarum\Tags\Api\Serializer\TagSerializer;
use Flarum\Tags\Tag;
use GlowingBlue\Localizd\Helpers\DynamicKeys;

class TagTranslation
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
     * Modifies specified attrs to provide translations where set (flarum/tags).
     *
     * @param TagSerializer $serializer
     * @param Tag           $tag
     * @param array         $attributes
     *
     * @return array
     */
    public function __invoke(TagSerializer $serializer, Tag $tag, array $attributes): array
    {
        $attributes['name'] = $this->dynamicKeys->objectOrTranslation($tag, 'name');
        $attributes['description'] = $this->dynamicKeys->objectOrTranslation($tag, 'description');

        return $attributes;
    }
}
