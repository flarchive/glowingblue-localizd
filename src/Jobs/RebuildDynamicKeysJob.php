<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd\Jobs;

use Flarum\Queue\AbstractJob;
use GlowingBlue\Localizd\Helpers\DynamicKeys;

class RebuildDynamicKeysJob extends AbstractJob
{
    public function handle(DynamicKeys $keys)
    {
        $keys->collect(true);
    }
}
