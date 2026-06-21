<?php
/**
 * Pixel Signs Theme functions.
 */

if (!defined('ABSPATH')) {
    exit;
}

function pixel_signs_theme_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'primary' => __('Primary Menu', 'pixel-signs'),
        'footer'  => __('Footer Menu', 'pixel-signs'),
    ]);
}
add_action('after_setup_theme', 'pixel_signs_theme_setup');

function pixel_signs_enqueue_assets(): void
{
    wp_enqueue_style(
        'pixel-signs-main',
        get_template_directory_uri() . '/assets/css/main.css',
        [],
        '0.2.0'
    );

    wp_enqueue_script(
        'pixel-signs-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        '0.2.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'pixel_signs_enqueue_assets');

function pixel_signs_body_classes(array $classes): array
{
    $classes[] = 'pixel-site';
    return $classes;
}
add_filter('body_class', 'pixel_signs_body_classes');

function pixel_signs_get_asset(string $path): string
{
    return esc_url(get_template_directory_uri() . '/assets/' . ltrim($path, '/'));
}
