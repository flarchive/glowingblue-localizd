<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd\Extend;

use Flarum\Extend\ExtenderInterface;
use Flarum\Extension\Extension;
use GlowingBlue\Localizd\Helpers\DynamicKeys as HelpersDynamicKeys;
use Illuminate\Contracts\Container\Container;

class DynamicKeys implements ExtenderInterface
{
    private array $models = [];

    public function addModel(string $model)
    {
        $this->models[] = $model;

        return $this;
    }

    public function extend(Container $container, ?Extension $extension = null)
    {
        foreach ($this->models as $model) {
            HelpersDynamicKeys::addDynamicModel($model);
        }
    }
}
