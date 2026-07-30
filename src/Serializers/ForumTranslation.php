<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd\Serializers;

use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Extension\ExtensionManager;
use Flarum\Flags\Flag;
use FoF\CookieConsent\Providers\AssetProvider;
use FoF\Terms\Policy;
use GlowingBlue\Localizd\Helpers\DynamicKeys;

class ForumTranslation
{
    protected DynamicKeys $dynamicKeys;

    protected ExtensionManager $extensions;

    /**
     * @param DynamicKeys      $dynamicKeys
     * @param ExtensionManager $extensions
     */
    public function __construct(DynamicKeys $dynamicKeys, ExtensionManager $extensions)
    {
        $this->dynamicKeys = $dynamicKeys;
        $this->extensions = $extensions;
    }

    /**
     * Modifies specified forum attrs to provide translations where set.
     *
     * @param ForumSerializer $serializer
     * @param array           $attributes
     *
     * @return array
     */
    public function __invoke(ForumSerializer $serializer, array $forumRelations, array $attributes): array
    {
        $attributes['welcomeTitle'] = $this->dynamicKeys->settingOrTranslation('welcome_title');
        $attributes['welcomeMessage'] = $this->dynamicKeys->settingOrTranslation('welcome_message');
        $attributes['title'] = $this->dynamicKeys->settingOrTranslation('forum_title');
        $attributes['description'] = $this->dynamicKeys->settingOrTranslation('forum_description');
        $attributes['headerHtml'] = $this->dynamicKeys->settingOrTranslation('custom_header');
        $attributes['footerHtml'] = $this->dynamicKeys->settingOrTranslation('custom_footer');

        if ($this->extensions->isEnabled('fof-terms') && class_exists(Policy::class)) {
            $attributes['fof-terms.signup-legal-text'] = $this->dynamicKeys->settingOrTranslation('fof-terms.signup-legal-text');
        }

        if ($this->extensions->isEnabled('flarum-flags') && class_exists(Flag::class)) {
            $attributes['guidelinesUrl'] = $this->dynamicKeys->settingOrTranslation('flarum-flags.guidelines_url');
        }

        if ($this->extensions->isEnabled('fof-cookie-consent') && class_exists(AssetProvider::class)) {
            $attributes['fof-cookie-consent.consentText'] = $this->dynamicKeys->settingOrTranslation('fof-cookie-consent.consentText');
            $attributes['fof-cookie-consent.buttonText'] = $this->dynamicKeys->settingOrTranslation('fof-cookie-consent.buttonText');
            $attributes['fof-cookie-consent.learnMoreLinkText'] = $this->dynamicKeys->settingOrTranslation('fof-cookie-consent.learnMoreLinkText');
            $attributes['fof-cookie-consent.learnMoreLinkUrl'] = $this->dynamicKeys->settingOrTranslation('fof-cookie-consent.learnMoreLinkUrl');
        }

        return $attributes;
    }
}
