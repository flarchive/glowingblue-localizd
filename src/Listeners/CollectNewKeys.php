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

use Flamarkt\Taxonomies\Events\Taxonomy\Created as TaxonomyCreated;
use Flamarkt\Taxonomies\Events\Taxonomy\Deleted as TaxonomyDeleted;
use Flamarkt\Taxonomies\Events\Term\Created as TaxonomyTermCreated;
use Flamarkt\Taxonomies\Events\Term\Deleted as TaxonomyTermDeleted;
use Flarum\Extension\Event\Disabled;
use Flarum\Extension\Event\Enabled;
use Flarum\Tags\Event\Creating as TagCreating;
use Flarum\Tags\Event\Deleting as TagDeleting;
use FoF\Links\Event\Created as LinkCreated;
use FoF\Links\Event\Deleted as LinkDeleted;
use FoF\Masquerade\Events\FieldCreated;
use FoF\Masquerade\Events\FieldDeleted;
use FoF\Reactions\Event\Created as ReactionCreated;
use FoF\Reactions\Event\Deleted as ReactionDeleted;
use FoF\Terms\Events\Created as TermCreated;
use FoF\Terms\Events\Deleted as TermDeleted;
use GlowingBlue\Localizd\Jobs\RebuildDynamicKeysJob;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class CollectNewKeys
{
    /**
     * These extensions, when enabled or disabled will trigger a rebuild of the dynamic keys.
     */
    protected static array $watchExtensions = [
        'flarum-tags'         => true,
        'fof-cookie-consent'  => true,
        'fof-links'           => true,
        'fof-reactions'       => true,
        'fof-terms'           => true,
        'fof-masquerade'      => true,
        'flamarkt-taxonomies' => true,
    ];

    protected static array $watchEvents = [
        LinkCreated::class,
        LinkDeleted::class,
        ReactionCreated::class,
        ReactionDeleted::class,
        TermCreated::class,
        TermDeleted::class,
        FieldCreated::class,
        FieldDeleted::class,
        TaxonomyCreated::class,
        TaxonomyDeleted::class,
        TaxonomyTermCreated::class,
        TaxonomyTermDeleted::class,
    ];

    public function subscribe(Dispatcher $events)
    {
        $events->listen(TagCreating::class, [$this, 'handleTagCreate']);
        $events->listen(TagDeleting::class, [$this, 'handleTagDelete']);
        $events->listen([Enabled::class, Disabled::class], [$this, 'handleExtension']);
        $events->listen(static::$watchEvents, [$this, 'rebuildDynamicKeys']);
    }

    public static function addWatchedExtension(string $extensionId): void
    {
        static::$watchExtensions[$extensionId] = true;
    }

    public static function addWatchedEvent(string $event): void
    {
        static::$watchEvents[] = $event;
    }

    /**
     * As `flarum/tags` dispatches `Creating` rather than `Created`, we register an `afterSave` function here,
     * rather than calling the rebuild too early.
     *
     * @param TagCreating $event
     *
     * @return void
     */
    public function handleTagCreate(TagCreating $event): void
    {
        $event->tag->afterSave(function ($tag) {
            $this->rebuildDynamicKeys();
        });
    }

    /**
     * As `flarum/tags` dispatches `Deleting` rather than `Deleted`, we register an `afterDelete` function here,
     * rather than calling the rebuild too early.
     *
     * @param TagDeleting $event
     *
     * @return void
     */
    public function handleTagDelete(TagDeleting $event): void
    {
        $event->tag->afterDelete(function ($tag) {
            $this->rebuildDynamicKeys();
        });
    }

    /**
     * Rebuild the dynamic keys when a watched extension is enabled or disabled.
     *
     * @param Enabled|Disabled $event
     *
     * @return void
     */
    public function handleExtension($event): void
    {
        if (Arr::has(static::$watchExtensions, $event->extension->getId())) {
            $this->rebuildDynamicKeys();
        }
    }

    /**
     * Trigger a rebuild of the dynamic keys. Will hand off to the queue worker if available,
     * else will run in the current process.
     *
     * @return void
     */
    public function rebuildDynamicKeys(): void
    {
        resolve('flarum.queue.connection')
            ->push(new RebuildDynamicKeysJob());
    }
}
