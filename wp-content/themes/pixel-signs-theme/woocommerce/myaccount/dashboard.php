<?php
/**
 * My Account dashboard.
 *
 * @package PixelSignsTheme
 * @version 4.4.0
 */

defined('ABSPATH') || exit;

$customer_name = $current_user->display_name !== '' ? $current_user->display_name : $current_user->user_login;
$orders_url    = wc_get_endpoint_url('orders');
$address_url   = wc_get_endpoint_url('edit-address');
$account_url   = wc_get_endpoint_url('edit-account');
?>

<div class="pixel-account-dashboard">
	<section class="pixel-account-dashboard-hero">
		<span class="eyebrow">Account Center</span>
		<h2>Welcome back, <?php echo esc_html($customer_name); ?>.</h2>
		<p>Manage your print orders, billing details, saved addresses, artwork files, and client portal activity from one polished workspace.</p>
		<div class="hero-actions">
			<a class="btn btn-primary" href="<?php echo esc_url(home_url('/client-dashboard/')); ?>">Open Client Dashboard</a>
			<a class="btn btn-outline" href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">Upload Artwork</a>
		</div>
	</section>

	<div class="pixel-account-action-grid">
		<a class="pixel-account-action-card" href="<?php echo esc_url($orders_url); ?>">
			<span>01</span>
			<strong>Orders</strong>
			<em>Review order history and production updates.</em>
		</a>
		<a class="pixel-account-action-card" href="<?php echo esc_url(home_url('/order-tracking/')); ?>">
			<span>02</span>
			<strong>Track Jobs</strong>
			<em>Search a job and follow its print status.</em>
		</a>
		<a class="pixel-account-action-card" href="<?php echo esc_url($address_url); ?>">
			<span>03</span>
			<strong>Addresses</strong>
			<em>Keep billing and delivery details ready.</em>
		</a>
		<a class="pixel-account-action-card" href="<?php echo esc_url($account_url); ?>">
			<span>04</span>
			<strong>Profile</strong>
			<em>Update your password and account details.</em>
		</a>
	</div>
</div>

<?php
/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action('woocommerce_account_dashboard');

/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_before_my_account');

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_after_my_account');
