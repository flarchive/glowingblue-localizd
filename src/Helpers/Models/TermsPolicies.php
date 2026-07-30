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

use FoF\Terms\Policy;

class TermsPolicies extends AbstractDynamicModel
{
    public static function getKeys(): array
    {
        return ['name', 'update_message', 'url'];
    }

    public static function getExtensionId(): string
    {
        return 'fof-terms';
    }

    public static function getModelClass(): string
    {
        return Policy::class;
    }

    public static function getKeyType(): string
    {
        return 'policy.';
    }
}
