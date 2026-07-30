<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd;

use Flarum\Extend\LifecycleInterface;
use Flarum\Extension\Extension;
use Flarum\Foundation\Paths;
use Illuminate\Contracts\Container\Container;
use Illuminate\Filesystem\Filesystem;

class Lifecycle implements LifecycleInterface
{
    public function onEnable(Container $container, Extension $extension)
    {
        // Not yet required
    }

    public function onDisable(Container $container, Extension $extension)
    {
        if ($extension->getId() === 'glowingblue-localizd') {
            /**
             * @var Paths
             */
            $paths = resolve(Paths::class);

            /**
             * @var Filesystem
             */
            $fs = resolve(Filesystem::class);

            $path = $paths->storage.DIRECTORY_SEPARATOR.'localizd';

            if ($fs->exists($path)) {
                $fs->deleteDirectory($path);
            }
        }
    }

    public function extend(Container $container, ?Extension $extension = null)
    {
        // Not yet required
    }
}
