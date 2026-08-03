<?php

/*
 * This file is part of glowingblue/localizd.
 *
 * Copyright (c) Glowing Blue AG.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace GlowingBlue\Localizd\Helpers\Models;

use Flamarkt\Taxonomies\Term;

class TaxonomyTerms extends Taxonomies
{
    public static function getModelClass(): string
    {
        return Term::class;
    }

    public static function getKeyType(): string
    {
        return 'taxonomy-term.';
    }
}
