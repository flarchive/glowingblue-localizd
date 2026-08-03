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

use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Database\AbstractModel;
use GlowingBlue\Localizd\Helpers\DynamicKeys;

class TaxonomyTranslation
{
    protected DynamicKeys $dynamicKeys;

    /**
     * @param DynamicKeys $dynamicKeys
     */
    public function __construct(DynamicKeys $dynamicKeys)
    {
        $this->dynamicKeys = $dynamicKeys;
    }

    public function __invoke(AbstractSerializer $serializer, AbstractModel $taxonomy, array $attributes): array
    {
        $attributes['name'] = $this->dynamicKeys->objectOrTranslation($taxonomy, 'name');
        $attributes['description'] = $this->dynamicKeys->objectOrTranslation($taxonomy, 'description');

        return $attributes;
    }
}
