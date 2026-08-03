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

use FoF\Terms\Policy;
use FoF\Terms\Serializers\PolicySerializer;
use GlowingBlue\Localizd\Helpers\DynamicKeys;

class TermsTranslation
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
     * @param PolicySerializer $serializer
     * @param Policy           $policy
     * @param array            $attributes
     *
     * @return array
     */
    public function __invoke(PolicySerializer $serializer, Policy $policy, array $attributes): array
    {
        $attributes['name'] = $this->dynamicKeys->objectOrTranslation($policy, 'name');
        $attributes['update_message'] = $this->dynamicKeys->objectOrTranslation($policy, 'update_message');
        $attributes['url'] = $this->dynamicKeys->objectOrTranslation($policy, 'url');

        return $attributes;
    }
}
