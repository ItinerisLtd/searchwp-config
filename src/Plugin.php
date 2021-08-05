<?php

declare(strict_types=1);

namespace Itineris\SearchWpConfig;

final class Plugin
{
    public static function register(): void
    {
        BasicAuth::register();

        add_filter('searchwp\indexer\alternate', [self::class, 'setIndexerAlternateCondition']);
    }

    /**
     * Only enable alternative filter on local.
     */
    public static function setIndexerAlternateCondition(): bool
    {
        return defined('WP_ENV') && 'development' === WP_ENV;
    }
}
