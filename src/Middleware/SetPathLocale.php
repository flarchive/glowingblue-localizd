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

use Flarum\Extension\ExtensionManager;
use Flarum\Http\Middleware\SetLocale as BaseSetLocale;
use Flarum\Http\RequestUtil;
use Flarum\Locale\LocaleManager;
use Illuminate\Support\Str;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

/**
 * This middleware is used to provide a language prefix in the URL to set the language.
 *
 * NOTE: When glowingblue/core is also enabled, this middleware takes priority over the SetLocale middleware provided in gb/core.
 * If making any changes to this logic, please ensure that the logic in the other middleware is also updated. https://github.com/glowingblue/flarum-ext-core
 */
class SetPathLocale extends BaseSetLocale
{
    protected $extensions;

    public function __construct(LocaleManager $locales, ExtensionManager $extensions)
    {
        parent::__construct($locales);

        $this->extensions = $extensions;
    }

    public function process(Request $request, Handler $handler): Response
    {
        // Get the path from the request URI
        $path = $request->getUri()->getPath();

        // get the first 4 characters of the path
        $firstFour = substr($path, 0, 4);

        // If the path starts and ends with /, then we have a potential locale prefix.
        if (Str::startsWith($firstFour, '/') && (Str::endsWith($firstFour, '/') || Str::length($firstFour) === 3)) {
            // Get the second and third characters of the path
            $localeFromPath = substr($firstFour, 1, 2);

            if ($this->locales->hasLocale($localeFromPath)) {
                // Update the language cookie
                setcookie('locale', $localeFromPath, 0, '/');

                $actor = RequestUtil::getActor($request);

                // If we have a logged in user, we need to save the updated language preference,
                // as this is the first parameter Flarum will check for subsequent requests
                if ($actor->exists) {
                    $actor->setPreference('locale', $localeFromPath);

                    if ($actor->isDirty()) {
                        $actor->save();
                    }
                }

                $newPath = $this->sanitizePath($path);

                // If the newPath is '/', simply add the language attribute without redirecting, unless fof/discussion-language is enabled
                if (($newPath === '/' || $newPath === '') && !$this->extensions->isEnabled('fof-discussion-language')) {
                    $this->locales->setLocale($localeFromPath);
                    $request = $request->withUri(
                        $request->getUri()
                            ->withPath($newPath)
                    )
                        ->withQueryParams(
                            $request->getQueryParams()
                        )->withAttribute('locale', $localeFromPath);

                    return $handler->handle($request);
                } else {
                    // Redirect to the URL without the locale prefix
                    return new RedirectResponse($newPath);
                }
            }
        }

        // If no locale prefix detected or locale prefix is invalid, defer to the parent's logic
        return parent::process($request, $handler);
    }

    protected function sanitizePath(string $path): string
    {
        // Remove the locale code from the path
        $newPath = substr($path, 3); // 3 characters: / + 2 letters

        // If the path starts with http:// or https://, remove it
        if (Str::startsWith($newPath, '//') || Str::startsWith($newPath, 'http://') || Str::startsWith($newPath, 'https://')) {
            $this->logUnexpectedPath($path);
            $newPath = Str::afterLast($newPath, '//');
        }

        if (empty($newPath)) {
            $newPath = '/';
        }

        if (!Str::startsWith($newPath, '/')) {
            $newPath = '/'.$newPath;
        }

        return $newPath;
    }

    protected function logUnexpectedPath(string $path): void
    {
        resolve('log')->info("[glowingblue/localizd] Language redirect: unexpected path: $path");
    }
}
