<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd\Helpers\Models;

use FoF\Links\Link;

class Links extends AbstractDynamicModel
{
    public static function getKeys(): array
    {
        return ['title', 'url'];
    }

    public static function getExtensionId(): string
    {
        return 'fof-links';
    }

    public static function getModelClass(): string
    {
        return Link::class;
    }

    public static function getKeyType(): string
    {
        return 'links.';
    }
}
