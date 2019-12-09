<?php

/**
 * Plugin Name:     Itineris SearchWP Security Configuration
 * Plugin URI:      https://github.com/ItinerisLtd/searchwp-config/
 * Description:     Sets default SearchWP configurations
 * Version:         0.1.0
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

add_filter('searchwp_basic_auth_creds', __NAMESPACE__ . '\\setBasicAuthCredentials');

function setBasicAuthCredentials(): array
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
