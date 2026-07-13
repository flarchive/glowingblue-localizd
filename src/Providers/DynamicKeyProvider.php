<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd\Providers;

use Flarum\Foundation\AbstractServiceProvider;
use Flarum\Foundation\ErrorHandling\Registry;
use Flarum\Foundation\ErrorHandling\Reporter;
use Flarum\Foundation\ErrorHandling\WhoopsFormatter;
use Flarum\Foundation\Paths;
use Flarum\Http\Middleware\HandleErrors;
use GlowingBlue\Localizd\Helpers\DynamicKeys;
use GlowingBlue\Localizd\TranslatableViewFormatter;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\View\Factory;
use Illuminate\Filesystem\Filesystem;

class DynamicKeyProvider extends AbstractServiceProvider
{
    public function register()
    {
        $this->container->singleton(DynamicKeys::class);

        // If the directory does not exists yet, we need to create it for the first time, else the `Extend\Locales` in `extend.php` will fail.
        /** @var Paths */
        $paths = resolve(Paths::class);
        /** @var Filesystem */
        $fs = resolve(Filesystem::class);

        $path = $paths->storage.DIRECTORY_SEPARATOR.'localizd';

        if (!$fs->exists($path)) {
            $fs->makeDirectory($path);
        }

        $this->container->bind('flarum.forum.error_handler', function (Container $container) {
            return new HandleErrors(
                $container->make(Registry::class),
                $container['flarum.config']->inDebugMode() ? $container->make(WhoopsFormatter::class) : $container->make(TranslatableViewFormatter::class),
                $container->tagged(Reporter::class)
            );
        });
    }

    public function boot(Container $container, Factory $view)
    {
        /** @var DynamicKeys */
        $keys = $container->make(DynamicKeys::class);

        $keys->collect();

        $view->share([
            'dynamicKeys' => $keys,
        ]);
    }
}
