<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd\Listeners;

use Flarum\Locale\LocaleManager;
use Flarum\User\Event\Registered;

class SetUserRegistrationLanguage
{
    protected LocaleManager $locale;

    public function __construct(LocaleManager $locale)
    {
        $this->locale = $locale;
    }

    public function handle(Registered $event): void
    {
        /**
         * @var \Flarum\User\User
         */
        $user = $event->user;

        $user->setPreference('locale', $this->locale->getLocale());
        $user->save();
    }
}
