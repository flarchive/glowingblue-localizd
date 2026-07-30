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

use Flarum\Extension\ExtensionManager;
use V17Development\FlarumSeo\Extend\SEO;

class V17SEOv2
{
    public function __invoke(): bool
    {
        /** @var ExtensionManager $extensions */
        $extensions = resolve(ExtensionManager::class);

        return $extensions->isEnabled('v17development-seo') && class_exists(SEO::class);
    }
}
