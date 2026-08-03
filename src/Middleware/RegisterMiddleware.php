<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd\Middleware;

use Flarum\Http\RequestUtil;
use Flarum\Locale\LocaleManager;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RegisterMiddleware implements MiddlewareInterface
{
    protected LocaleManager $locales;
    protected SettingsRepositoryInterface $settings;

    public function __construct(LocaleManager $locales, SettingsRepositoryInterface $settings)
    {
        $this->locales = $locales;
        $this->settings = $settings;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $acceptLangs = Arr::get($request->getServerParams(), 'HTTP_ACCEPT_LANGUAGE');
        $requestLocale = Arr::get($request->getCookieParams(), 'locale');
        $englishDisabled = (bool) $this->settings->get('glowingblue-localizd.redirect-en', false);

        /**
         * @var User
         */
        $actor = RequestUtil::getActor($request);

        // We only want to change the locale if the actor is `Guest`, there is no `locale` cookie present AND the browser supplies at least one language in HTTP_ACCEPT_LANGUAGE
        if (!$actor->exists && !isset($requestLocale) && isset($acceptLangs)) {
            $langs = [];
            // break up string into pieces (languages and q factors)
            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $acceptLangs, $lang_parse);

            if (count($lang_parse[1])) {
                // create a list like "en" => 0.8
                $langs = array_combine($lang_parse[1], $lang_parse[4]);

                // set default to 1 for any without q factor
                foreach ($langs as $lang => $val) {
                    if ($val === '') {
                        $langs[$lang] = 1;
                    }
                }

                // sort list based on value
                arsort($langs, SORT_NUMERIC);
            }

            // look through sorted list and use first one that matches our installed languages
            foreach ($langs as $lang => $val) {
                if ($this->locales->hasLocale($lang)) {
                    if ($englishDisabled && $lang === 'en') {
                        continue;
                    }

                    // Once we find a match, set the locale, and add the `locale` attribute so the Flarum UI displays accordingly
                    $this->locales->setLocale($lang);
                    setcookie('locale', $lang, 0, '/');
                    $request = $request->withAttribute('locale', $lang);
                    break;
                }
            }
        }

        return $handler->handle($request);
    }
}
