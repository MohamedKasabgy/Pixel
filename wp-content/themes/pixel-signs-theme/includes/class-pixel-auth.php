<?php
/**
 * Pixel Auth Handlers
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pixel_Auth {
    public static function init() {
        add_action('admin_post_nopriv_pixel_login_customer', [self::class, 'handle_login']);
        add_action('admin_post_pixel_login_customer', [self::class, 'handle_login']);
        
        add_action('admin_post_nopriv_pixel_register_customer', [self::class, 'handle_register']);
        add_action('admin_post_pixel_register_customer', [self::class, 'handle_register']);
    }

    public static function handle_login() {
        check_admin_referer('pixel_login_action', 'pixel_login_nonce');

        $email = sanitize_user(wp_unslash($_POST['log'] ?? ''));
        $password = wp_unslash($_POST['pwd'] ?? '');

        $user = wp_signon([
            'user_login'    => $email,
            'user_password' => $password,
            'remember'      => true,
        ]);

        if (is_wp_error($user)) {
            // Redirect back to home with an error parameter
            wp_safe_redirect(add_query_arg('login_error', 'failed', wp_get_referer() ?: home_url()));
            exit;
        }

        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true);

        wp_safe_redirect(home_url('/client-dashboard/'));
        exit;
    }

    public static function handle_register() {
        check_admin_referer('pixel_register_action', 'pixel_register_nonce');

        $first_name = sanitize_text_field(wp_unslash($_POST['first_name'] ?? ''));
        $last_name  = sanitize_text_field(wp_unslash($_POST['last_name'] ?? ''));
        $email      = sanitize_email(wp_unslash($_POST['user_email'] ?? ''));
        $phone      = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
        $password   = wp_unslash($_POST['password'] ?? '');
        $marketing  = isset($_POST['marketing_opt_in']) ? 1 : 0;

        if (!is_email($email) || empty($password)) {
            wp_safe_redirect(add_query_arg('register_error', 'invalid_fields', wp_get_referer() ?: home_url()));
            exit;
        }

        if (email_exists($email)) {
            wp_safe_redirect(add_query_arg('register_error', 'email_exists', wp_get_referer() ?: home_url()));
            exit;
        }

        $customer_id = 0;

        // Use WooCommerce function if available
        if (function_exists('wc_create_new_customer')) {
            $customer_id = wc_create_new_customer($email, '', $password);
        } else {
            // Fallback to WP standard
            $customer_id = wp_insert_user([
                'user_login' => $email,
                'user_email' => $email,
                'user_pass'  => $password,
                'role'       => 'customer', // fallback role
            ]);
        }

        if (is_wp_error($customer_id)) {
            wp_safe_redirect(add_query_arg('register_error', 'creation_failed', wp_get_referer() ?: home_url()));
            exit;
        }

        // Assign standard customer role
        $user_obj = get_user_by('id', $customer_id);
        if ($user_obj) {
            $user_obj->set_role('customer'); // Ensures they are strictly a customer
        }

        // Update custom fields
        wp_update_user([
            'ID'         => $customer_id,
            'first_name' => $first_name,
            'last_name'  => $last_name,
        ]);

        update_user_meta($customer_id, 'billing_phone', $phone);
        update_user_meta($customer_id, 'billing_first_name', $first_name);
        update_user_meta($customer_id, 'billing_last_name', $last_name);

        if ($marketing) {
            update_user_meta($customer_id, 'pixel_marketing_opt_in', 1);
        }

        // Set user and auth cookie
        wp_set_current_user($customer_id);
        wp_set_auth_cookie($customer_id, true);

        wp_safe_redirect(home_url('/client-dashboard/'));
        exit;
    }
}
Pixel_Auth::init();
