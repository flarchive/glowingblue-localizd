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

use Flarum\Tags\Tag;

class Tags extends AbstractDynamicModel
{
    public static function getKeys(): array
    {
        return ['name', 'description'];
    }

    public static function getExtensionId(): string
    {
        return 'flarum-tags';
    }

    public static function getModelClass(): string
    {
        return Tag::class;
    }

    public static function getKeyType(): string
    {
        return 'tags.';
    }
}
