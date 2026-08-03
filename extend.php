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

use Flamarkt\Taxonomies\Api\Serializer\TaxonomySerializer;
use Flamarkt\Taxonomies\Api\Serializer\TermSerializer as TaxonomyTermSerializer;
use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Api\Serializer\GroupSerializer;
use Flarum\Extend;
use Flarum\Foundation\Paths;
use Flarum\Http\Middleware\SetLocale;
use Flarum\Tags\Api\Serializer\TagSerializer;
use Flarum\User\Event\Registered;
use FoF\Links\Api\Serializer\LinkSerializer;
use FoF\Masquerade\Api\Serializers\FieldSerializer;
use FoF\Reactions\Api\Serializer\ReactionSerializer;
use FoF\Terms\Serializers\PolicySerializer;
use GlowingBlue\Localizd\Extend\V17SEOv2;
use GlowingBlue\Localizd\Listeners\SEOPageMeta;
use GlowingBlue\Localizd\Middleware\RegisterMiddleware;

return [
    new Lifecycle(),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/resources/less/admin.less'),

    (new Extend\Frontend('backoffice'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/resources/less/admin.less'),

    new Extend\Locales(__DIR__.'/resources/locale'),
    new Extend\Locales(resolve(Paths::class)->storage.DIRECTORY_SEPARATOR.'localizd'),

    (new Extend\Event())
        ->listen(Registered::class, Listeners\SetUserRegistrationLanguage::class)
        ->subscribe(Listeners\CollectNewKeys::class),

    (new Extend\ServiceProvider())
        ->register(Providers\DynamicKeyProvider::class)
        ->register(Providers\MailerProvider::class),

    (new Extend\ApiSerializer(ForumSerializer::class))
        ->attributes(Serializers\ForumTranslation::class),

    (new Extend\ApiSerializer(TagSerializer::class))
        ->attributes(Serializers\TagTranslation::class),

    (new Extend\ApiSerializer(LinkSerializer::class))
        ->attributes(Serializers\LinkTranslation::class),

    (new Extend\ApiSerializer(PolicySerializer::class))
        ->attributes(Serializers\TermsTranslation::class),

    (new Extend\ApiSerializer(FieldSerializer::class))
        ->attributes(Serializers\ProfileFieldTranslation::class),

    (new Extend\ApiSerializer(TaxonomySerializer::class))
        ->attributes(Serializers\TaxonomyTranslation::class),

    (new Extend\ApiSerializer(TaxonomyTermSerializer::class))
        ->attributes(Serializers\TaxonomyTranslation::class),

    (new Extend\ApiSerializer(ReactionSerializer::class))
        ->attributes(Serializers\ReactionTranslation::class),

    (new Extend\ApiSerializer(GroupSerializer::class))
        ->attributes(Serializers\GroupTranslation::class),

    (new Extend\View())
        ->extendNamespace('flarum.forum', __DIR__.'/resources/views'),

    (new Extend\Conditional())
        ->when(new V17SEOv2(), fn () => [
            (new \V17Development\FlarumSeo\Extend\SEO())
                ->addExtender('\GlowingBlue\Localizd\Extend\SEOPageMeta', \GlowingBlue\Localizd\Extend\SEOPageMeta::class),

            (new Extend\Frontend('forum'))
                ->content(SEOPageMeta::class),
        ])
        ->whenExtensionEnabled('fof-seo', fn () => [
            (new \FoF\Seo\Extend\SEO())
                ->addExtender('glowingblue-localizd', \GlowingBlue\Localizd\Extend\FoFSEOPageMeta::class),

            (new Extend\Event())
                ->listen(\FoF\Seo\Event\PreparingPageMeta::class, Listeners\FoFSEOPageMeta::class),
        ])
        ->whenExtensionDisabled('ianm-translate', fn () => [
            (new Extend\Middleware('forum'))
                ->replace(SetLocale::class, Middleware\SetPathLocale::class)
                ->add(RegisterMiddleware::class),
        ]),
];
