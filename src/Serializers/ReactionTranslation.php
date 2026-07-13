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

use FoF\Reactions\Api\Serializer\ReactionSerializer;
use FoF\Reactions\Reaction;
use GlowingBlue\Localizd\Helpers\DynamicKeys;

class ReactionTranslation
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
     * Modifies specified attrs to provide translations where set (fof/reactions).
     *
     * @param ReactionSerializer $serializer
     * @param Reaction           $reaction
     * @param array              $attributes
     *
     * @return array
     */
    public function __invoke(ReactionSerializer $serializer, Reaction $reaction, array $attributes): array
    {
        $attributes['display'] = $this->dynamicKeys->objectOrTranslation($reaction, 'display');

        return $attributes;
    }
}
