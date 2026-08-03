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
use GlowingBlue\Localizd\Listeners\CollectNewKeys as ListenersCollectNewKeys;
use Illuminate\Contracts\Container\Container;

class CollectNewKeys implements ExtenderInterface
{
    private array $watchExtensions = [];
    private array $watchEvents = [];

    public function watchExtension(string $extensionId)
    {
        $this->watchExtensions[] = $extensionId;

        return $this;
    }

    public function watchEvent(string $event)
    {
        $this->watchEvents[] = $event;

        return $this;
    }

    public function extend(Container $container, ?Extension $extension = null)
    {
        foreach ($this->watchExtensions as $extensionId) {
            ListenersCollectNewKeys::addWatchedExtension($extensionId);
        }

        foreach ($this->watchEvents as $event) {
            ListenersCollectNewKeys::addWatchedEvent($event);
        }
    }
}
