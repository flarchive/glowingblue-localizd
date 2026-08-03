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

use Illuminate\Database\Eloquent\Collection;

abstract class AbstractDynamicModel
{
    abstract public static function getKeys(): array;

    public static function getAll(): Collection
    {
        return static::getModelClass()::all();
    }

    abstract public static function getExtensionId(): string;

    abstract public static function getModelClass(): string;

    abstract public static function getKeyType(): string;
}
