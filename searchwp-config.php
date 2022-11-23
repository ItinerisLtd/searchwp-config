<?php

/**
 * Plugin Name:     Itineris SearchWP Configuration
 * Plugin URI:      https://github.com/ItinerisLtd/searchwp-config/
 * Description:     Sets default SearchWP configurations
 * Version:         1.1.0
 * Author:          Itineris Limited
 * Author URI:      https://www.itineris.co.uk/
 * Text Domain:     searchwp-config
 */

declare(strict_types=1);

namespace Itineris\SearchWpConfig;

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

if (! class_exists(Plugin::class) && is_readable(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

Plugin::register();
