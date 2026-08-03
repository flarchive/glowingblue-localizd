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
use Flarum\Notification\NotificationMailer;
use Flarum\Settings\SettingsRepositoryInterface;
use GlowingBlue\Localizd\Helpers\DynamicKeys;
use GlowingBlue\Localizd\Overrides;

class MailerProvider extends AbstractServiceProvider
{
    public function boot()
    {
        $this->container->extend(NotificationMailer::class, function (NotificationMailer $mailer) {
            return resolve(Overrides\NotificationMailer::class);
        });

        $this->container->extend('mailer', function ($mailer) {
            /** @var SettingsRepositoryInterface $settings */
            $settings = resolve(SettingsRepositoryInterface::class);

            /** @var DynamicKeys $dynamicKeys */
            $dynamicKeys = resolve(DynamicKeys::class);

            $mailer->alwaysFrom($settings->get('mail_from'), $dynamicKeys->settingOrTranslation('forum_title'));

            return $mailer;
        });
    }
}
