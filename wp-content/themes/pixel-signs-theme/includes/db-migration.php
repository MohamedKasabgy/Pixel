<?php
function pixel_force_woo_shortcodes(): void
{
    if (get_option('pixel_woo_shortcode_migration_v2') === 'yes') {
        return;
    }

    if (!function_exists('wc_get_page_id')) {
        return;
    }

    $cart_id = wc_get_page_id('cart');
    if ($cart_id && $cart_id > 0) {
        $cart_post = get_post($cart_id);
        if ($cart_post && (empty($cart_post->post_content) || strpos($cart_post->post_content, 'wp:woocommerce/cart') !== false)) {
            wp_update_post(['ID' => $cart_id, 'post_content' => '[woocommerce_cart]']);
        }
    }

    $checkout_id = wc_get_page_id('checkout');
    if ($checkout_id && $checkout_id > 0) {
        $checkout_post = get_post($checkout_id);
        if ($checkout_post && (empty($checkout_post->post_content) || strpos($checkout_post->post_content, 'wp:woocommerce/checkout') !== false)) {
            wp_update_post(['ID' => $checkout_id, 'post_content' => '[woocommerce_checkout]']);
        }
    }

    update_option('pixel_woo_shortcode_migration_v2', 'yes');
}
add_action('init', 'pixel_force_woo_shortcodes');
