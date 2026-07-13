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

use Flarum\Group\Group;

class Groups extends AbstractDynamicModel
{
    public static function getKeys(): array
    {
        return ['name_singular', 'name_plural'];
    }

    public static function getExtensionId(): string
    {
        return '';
    }

    public static function getModelClass(): string
    {
        return Group::class;
    }

    public static function getKeyType(): string
    {
        return 'groups.';
    }
}
