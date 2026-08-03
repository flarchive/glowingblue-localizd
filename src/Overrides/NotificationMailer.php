<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd\Overrides;

use Flarum\Notification\MailableInterface;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use GlowingBlue\Localizd\Helpers\DynamicKeys;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Mail\Message;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationMailer extends \Flarum\Notification\NotificationMailer
{
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @var DynamicKeys
     */
    protected $dynamicKeys;

    /**
     * @param Mailer                         $mailer
     * @param TranslatorInterface&Translator $translator
     */
    public function __construct(Mailer $mailer, TranslatorInterface $translator, SettingsRepositoryInterface $settings, DynamicKeys $dynamicKeys)
    {
        parent::__construct($mailer, $translator, $settings);

        $this->settings = $settings;
        $this->dynamicKeys = $dynamicKeys;
    }

    /**
     * @param MailableInterface $blueprint
     * @param User              $user
     */
    public function send(MailableInterface $blueprint, User $user)
    {
        // Ensure that notifications are delivered to the user in their default language, if they've selected one.
        $locale = $user->getPreference('locale');
        if (empty($locale)) {
            $locale = $this->settings->get('default_locale');
        }

        $this->translator->setLocale($locale);

        /** @phpstan-ignore-next-line */
        $this->mailer->alwaysFrom($this->settings->get('mail_from'), $this->dynamicKeys->settingOrTranslation('forum_title'));

        $this->mailer->send(
            $blueprint->getEmailView(),
            compact('blueprint', 'user'),
            function (Message $message) use ($blueprint, $user) {
                $message->to($user->email, $user->display_name)
                    ->subject($blueprint->getEmailSubject($this->translator));
            }
        );
    }
}
