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

use Flarum\Extension\ExtensionManager;
use Flarum\Frontend\Document;
use GlowingBlue\Localizd\Helpers\DynamicKeys;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class SEOPageMeta
{
    /** @var DynamicKeys */
    protected $dynamicKeys;

    /** @var ExtensionManager */
    protected $extensions;

    public function __construct(DynamicKeys $dynamicKeys, ExtensionManager $extensions)
    {
        $this->dynamicKeys = $dynamicKeys;
        $this->extensions = $extensions;
    }

    public function __invoke(Document $document, ServerRequestInterface $request): void
    {
        if (!$this->extensions->isEnabled('v17development-seo')) {
            return;
        }

        $forumTitle = $this->dynamicKeys->settingOrTranslation('forum_title');
        $forumDescription = $this->dynamicKeys->settingOrTranslation('forum_description');

        // Update the JSON-LD schema
        $this->updateSchemaDotOrgJson($document, [
            'name'        => $forumTitle,
            'description' => $forumDescription,
        ]);
    }

    protected function setMetaTag(Document $document, string $name, string $content): void
    {
        $document->meta[$name] = $content;
    }

    protected function setMetaProperty(Document $document, string $name, string $content): void
    {
        $this->removeOldMetaProperty($document, $name);
        $document->head[] = '<meta property="'.e($name).'" content="'.e($content).'">';
    }

    private function removeOldMetaProperty(Document $document, string $name): void
    {
        $document->head = array_filter($document->head, function ($item) use ($name) {
            return strpos($item, $name) === false;
        });
    }

    protected function updateSchemaDotOrgJson(Document $document, array $updates): void
    {
        $script = array_filter($document->head, function ($item) {
            return strpos($item, 'application/ld+json') !== false;
        });

        if (empty($script)) {
            // No schema.org JSON-LD found
            return;
        }

        foreach ($script as $org) {
            // extract the json from the <script> tag and decode it
            $json = json_decode(substr($org, strpos($org, '>') + 1, -strlen('</script>')), true);

            //publisher info is at index 0
            foreach ($updates as $key => $value) {
                Arr::set($json, '0.publisher.'.$key, $value);

                if ($key === 'description') {
                    // description is also at the root of the object
                    Arr::set($json, '0.description', $value);
                }
            }

            // encode the json and replace the <script> tag
            $document->head = str_replace($org, '<script type="application/ld+json">'.json_encode($json).'</script>', $document->head);
        }
    }
}
