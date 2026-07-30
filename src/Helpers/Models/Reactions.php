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

use FoF\Reactions\Reaction;

class Reactions extends AbstractDynamicModel
{
    public static function getKeys(): array
    {
        return ['display'];
    }

    public static function getExtensionId(): string
    {
        return 'fof-reactions';
    }

    public static function getModelClass(): string
    {
        return Reaction::class;
    }

    public static function getKeyType(): string
    {
        return 'reactions.';
    }
}
