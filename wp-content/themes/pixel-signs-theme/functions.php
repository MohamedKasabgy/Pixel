<?php
/**
 * Pixel Signs Theme functions.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/includes/class-pixel-auth.php';
require_once get_template_directory() . '/includes/class-pixel-product-options.php';
require_once get_template_directory() . '/includes/db-migration.php';

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
    $main_css_path = get_template_directory() . '/assets/css/main.css';
    $main_js_path  = get_template_directory() . '/assets/js/main.js';

    wp_enqueue_style(
        'pixel-signs-main',
        get_template_directory_uri() . '/assets/css/main.css',
        [],
        file_exists($main_css_path) ? (string) filemtime($main_css_path) : '0.3.0'
    );

    wp_enqueue_script(
        'pixel-signs-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        file_exists($main_js_path) ? (string) filemtime($main_js_path) : '0.3.0',
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

function pixel_signs_get_product_primary_category(WC_Product $product): ?WP_Term
{
    $terms = get_the_terms($product->get_id(), 'product_cat');

    if (empty($terms) || is_wp_error($terms)) {
        return null;
    }

    return array_values($terms)[0];
}

function pixel_signs_get_product_category_label(WC_Product $product): string
{
    $category = pixel_signs_get_product_primary_category($product);

    return $category ? $category->name : __('Printing', 'pixel-signs');
}

function pixel_signs_get_product_card_description(WC_Product $product, int $word_limit = 24): string
{
    $description = $product->get_short_description();

    if ($description === '') {
        $description = $product->get_description();
    }

    if ($description === '') {
        return __('Professional Pixel print product ready for quoting, artwork upload, and production.', 'pixel-signs');
    }

    return wp_trim_words(wp_strip_all_tags($description), $word_limit);
}

function pixel_signs_get_product_mockup_key(WC_Product $product): string
{
    $category = pixel_signs_get_product_primary_category($product);
    $haystack = strtolower($product->get_slug() . ' ' . $product->get_name() . ' ' . ($category ? $category->slug . ' ' . $category->name : ''));

    if (strpos($haystack, 'card') !== false) {
        return 'cards';
    }

    if (strpos($haystack, 'label') !== false || strpos($haystack, 'sticker') !== false) {
        return 'labels';
    }

    if (strpos($haystack, 'shirt') !== false || strpos($haystack, 'polo') !== false || strpos($haystack, 'apparel') !== false) {
        return 'shirt';
    }

    if (strpos($haystack, 'package') !== false || strpos($haystack, 'box') !== false || strpos($haystack, 'bag') !== false || strpos($haystack, 'tape') !== false) {
        return 'packaging';
    }

    if (strpos($haystack, 'flyer') !== false || strpos($haystack, 'brochure') !== false || strpos($haystack, 'menu') !== false || strpos($haystack, 'postcard') !== false) {
        return 'flyer';
    }

    if (strpos($haystack, 'banner') !== false || strpos($haystack, 'poster') !== false || strpos($haystack, 'sign') !== false || strpos($haystack, 'board') !== false) {
        return 'banner';
    }

    return 'print';
}

function pixel_signs_render_product_visual(WC_Product $product, string $class_name = 'product-image-placeholder'): void
{
    if ($product->get_image_id()) {
        echo wp_kses_post(
            $product->get_image(
                'woocommerce_thumbnail',
                [
                    'class' => 'pixel-product-card-image',
                    'alt'   => $product->get_name(),
                ]
            )
        );
        return;
    }

    printf(
        '<div class="%1$s" data-product-slug="%2$s" data-product-mockup="%3$s"><span>%4$s</span></div>',
        esc_attr($class_name),
        esc_attr($product->get_slug()),
        esc_attr(pixel_signs_get_product_mockup_key($product)),
        esc_html(pixel_signs_get_product_category_label($product))
    );
}

function pixel_signs_get_catalog_products(string $category_slug = '', int $limit = -1): array
{
    if (!function_exists('wc_get_products')) {
        return [];
    }

    $args = [
        'status'  => 'publish',
        'limit'   => $limit,
        'orderby' => 'menu_order',
        'order'   => 'ASC',
        'return'  => 'objects',
    ];

    if ($category_slug !== '') {
        $args['category'] = [$category_slug];
    }

    return wc_get_products($args);
}

function pixel_signs_get_featured_home_products(int $limit = 6): array
{
    if (!function_exists('wc_get_products')) {
        return [];
    }

    $products = wc_get_products([
        'status'   => 'publish',
        'featured' => true,
        'limit'    => $limit,
        'orderby'  => 'menu_order',
        'order'    => 'ASC',
        'return'   => 'objects',
    ]);

    if (!empty($products)) {
        return $products;
    }

    return wc_get_products([
        'status'  => 'publish',
        'limit'   => $limit,
        'orderby' => 'date',
        'order'   => 'DESC',
        'return'  => 'objects',
    ]);
}
