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

use FoF\Masquerade\Api\Serializers\FieldSerializer;
use FoF\Masquerade\Field;
use GlowingBlue\Localizd\Helpers\DynamicKeys;

class ProfileFieldTranslation
{
    public function __construct(
        protected DynamicKeys $dynamicKeys
    ) {
    }

    public function __invoke(FieldSerializer $serializer, Field $field, array $attributes): array
    {
        $attributes['name'] = $this->dynamicKeys->objectOrTranslation($field, 'name');
        $attributes['description'] = $this->dynamicKeys->objectOrTranslation($field, 'description');

        return $attributes;
    }
}
