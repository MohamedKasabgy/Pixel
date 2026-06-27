<?php
/**
 * My Account page.
 *
 * @package PixelSignsTheme
 * @version 3.5.0
 */

defined('ABSPATH') || exit;
?>

<div class="pixel-myaccount-shell">
	<?php
	/**
	 * My Account navigation.
	 *
	 * @since 2.6.0
	 */
	do_action('woocommerce_account_navigation');
	?>

	<div class="woocommerce-MyAccount-content pixel-myaccount-content">
		<?php
		/**
		 * My Account content.
		 *
		 * @since 2.6.0
		 */
		do_action('woocommerce_account_content');
		?>
	</div>
</div>
