<?php
/**
 *
 * Plugin Name:       EBC Donations Form
 * Description:       Integrates iPay payment gateway with user filled form
 * Version:	1.2.1
 * Requires at least: 5.2
 * Requires PHP:      7.3
 * Author:            Slash Dot Labs Limited
 * Author URI:        https://slashdotlabs.com/
 */

// Prevent direct access
defined('ABSPATH') or die("We don't do that here :/");

//global plugin name
define('SLASH_COUPON_PLUGIN_NAME', plugin_basename(__FILE__));

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

register_activation_hook(__FILE__, [SlashEbc\Base\Activate::class, 'run']);
register_deactivation_hook(__FILE__, [SlashEbc\Base\Deactivate::class, 'run']);

if (class_exists(SlashEbc\Init::class)) {
    SlashEbc\Init::register_services();
}
