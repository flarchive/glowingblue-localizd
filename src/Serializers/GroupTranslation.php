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

use Flarum\Api\Serializer\GroupSerializer;
use Flarum\Group\Group;
use GlowingBlue\Localizd\Helpers\DynamicKeys;

class GroupTranslation
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
     * Modifies specified attrs to provide translations where set (groups).
     *
     * @param GroupSerializer $serializer
     * @param Group           $group
     * @param array           $attributes
     *
     * @return array
     */
    public function __invoke(GroupSerializer $serializer, Group $group, array $attributes): array
    {
        $attributes['nameSingular'] = $this->dynamicKeys->objectOrTranslation($group, 'name_singular');
        $attributes['namePlural'] = $this->dynamicKeys->objectOrTranslation($group, 'name_plural');

        return $attributes;
    }
}
