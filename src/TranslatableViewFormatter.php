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

use Flarum\Extension\ExtensionManager;
use Flarum\Foundation\ErrorHandling\HandledError;
use Flarum\Foundation\ErrorHandling\ViewFormatter;
use Flarum\Settings\SettingsRepositoryInterface;
use GlowingBlue\Localizd\Helpers\DynamicKeys;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslatableViewFormatter extends ViewFormatter
{
    /** @var ExtensionManager */
    protected $extensions;

    public function __construct(ViewFactory $view, TranslatorInterface $translator, SettingsRepositoryInterface $settings, ExtensionManager $extensions)
    {
        parent::__construct($view, $translator, $settings);

        $this->extensions = $extensions;
    }

    public function format(HandledError $error, Request $request): Response
    {
        // If `fof/html-errors` is enabled, use it to generate the view, if it exists
        if ($this->extensions->isEnabled('fof-html-errors')) {
            // Get the custom html for that error if it exists
            // This supports more codes than what is exposed in the extension settings
            $html = $this->settings->get('flagrow-html-errors.custom'.$error->getStatusCode().'ErrorHtml');

            if ($html) {
                return new HtmlResponse($html, $error->getStatusCode());
            }
        }

        // Otherwise, use the localizd or Flarum default view
        $view = $this->view->make($this->determineView($error))
            ->with('error', $error->getException())
            ->with('message', $this->getMessage($error))
            ->with('forumTitle', $this->getForumTitle());

        return new HtmlResponse($view->render(), $error->getStatusCode());
    }

    private function determineView(HandledError $error): string
    {
        $type = $error->getType();

        $errorsWithViews = array_merge(self::ERRORS_WITH_VIEWS, ['permission_denied']);

        if (in_array($type, $errorsWithViews)) {
            return "flarum.forum::error.$type";
        } else {
            return 'flarum.forum::error.default';
        }
    }

    private function getMessage(HandledError $error)
    {
        return $this->getTranslationIfExists($error->getType())
            ?? $this->getTranslationIfExists('unknown')
            ?? 'An error occurred while trying to load this page.';
    }

    private function getTranslationIfExists(string $errorType)
    {
        $key = "core.views.error.$errorType";
        $translation = $this->translator->trans($key, ['forum' => $this->getForumTitle()]);

        return $translation === $key ? null : $translation;
    }

    protected function getForumTitle(): string
    {
        /** @var DynamicKeys */
        $dynamicKeys = resolve(DynamicKeys::class);

        return $dynamicKeys->settingOrTranslation('forum_title');
    }
}
