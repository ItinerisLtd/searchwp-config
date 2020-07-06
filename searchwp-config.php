<?php

/**
 * Plugin Name:     Itineris SearchWP Configuration
 * Plugin URI:      https://github.com/ItinerisLtd/searchwp-config/
 * Description:     Sets default SearchWP configurations
 * Version:         0.1.1
 * Author:          Itineris Limited
 * Author URI:      https://www.itineris.co.uk/
 * Text Domain:     searchwp-config
 */

declare(strict_types=1);

namespace Itineris\SearchWPConfig;

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

add_filter('searchwp_basic_auth_creds', __NAMESPACE__ . '\\set_searchwp_basic_auth_credentials');
add_filter('searchwp\indexer\http_basic_auth_credentials', __NAMESPACE__ . '\\set_searchwp_basic_auth_credentials');

function set_searchwp_basic_auth_credentials(): array
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

add_filter('cron_request', __NAMESPACE__ . '\\set_cron_request_basic_auth_credentials', 999);

function set_cron_request_basic_auth_credentials(array $cron_request): array
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
        base64_encode(HTTP_BASIC_AUTH_USERNAME . ':' . HTTP_BASIC_AUTH_PASSWORD)
    );

    return $cron_request;
}
