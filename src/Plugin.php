<?php

declare(strict_types=1);

namespace Itineris\SearchWpConfig;

final class Plugin
{
    public static function register(): void
    {
        add_filter('searchwp_basic_auth_creds', [self::class, 'setBasicAuthCredentials']);
        add_filter('searchwp\indexer\http_basic_auth_credentials', [self::class, 'setBasicAuthCredentials']);
        add_filter('cron_request', [self::class, 'setCronRequestBasicAuthCredentials'], 999);
        add_filter('searchwp\indexer\alternate', [self::class, 'setIndexerAlternateCondition']);
    }

    public static function setBasicAuthCredentials(): array
    {
        if (defined('HTTP_BASIC_AUTH_USERNAME') && defined('HTTP_BASIC_AUTH_PASSWORD')) {
            return [
                'username' => HTTP_BASIC_AUTH_USERNAME,
                'password' => HTTP_BASIC_AUTH_PASSWORD,
            ];
        }

        return [
            'username' => '',
            'password' => '',
        ];
    }

    public static function setCronRequestBasicAuthCredentials(array $cron_request): array
    {
        if (! defined('HTTP_BASIC_AUTH_USERNAME') || ! defined('HTTP_BASIC_AUTH_PASSWORD')) {
            return $cron_request;
        }

        if (! isset($cron_request['args']['headers'])) {
            $cron_request['args']['headers'] = [];
        }

        if (isset($cron_request['args']['headers']['Authorization'])) {
            return $cron_request;
        }

        $cron_request['args']['headers']['Authorization'] = sprintf(
            'Basic %s',
            // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
            base64_encode(HTTP_BASIC_AUTH_USERNAME . ':' . HTTP_BASIC_AUTH_PASSWORD)
        );

        return $cron_request;
    }

    /**
     * Only enable alternative filter on local.
     */
    public static function setIndexerAlternateCondition(): bool
    {
        return defined('WP_ENV') && 'development' === WP_ENV;
    }
}
