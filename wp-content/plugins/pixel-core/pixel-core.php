<?php
/**
 * Plugin Name: Pixel Core
 * Plugin URI: https://example.com
 * Description: Print-specific features for Pixel Signs & Printing: quote requests, artwork uploads, client portal shortcodes, print order statuses, and staff roles.
 * Version: 0.1.0
 * Author: Pixel Signs & Printing
 * Text Domain: pixel-core
 */

if (!defined('ABSPATH')) {
    exit;
}

define('PIXEL_CORE_VERSION', '0.1.0');
define('PIXEL_CORE_PATH', plugin_dir_path(__FILE__));
define('PIXEL_CORE_URL', plugin_dir_url(__FILE__));

require_once PIXEL_CORE_PATH . 'includes/class-pixel-core.php';
require_once PIXEL_CORE_PATH . 'includes/class-pixel-admin-fields.php';

register_activation_hook(__FILE__, ['Pixel_Core', 'activate']);
register_deactivation_hook(__FILE__, ['Pixel_Core', 'deactivate']);

add_action('plugins_loaded', static function (): void {
    Pixel_Core::instance();
    Pixel_Admin_Fields::init();
});
