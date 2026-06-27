<?php
/**
 * Login form.
 *
 * @package PixelSignsTheme
 * @version 9.9.0
 */

if (!defined('ABSPATH')) {
	exit;
}

$registration_enabled = 'yes' === get_option('woocommerce_enable_myaccount_registration');

do_action('woocommerce_before_customer_login_form');
?>

<div class="pixel-account-auth<?php echo $registration_enabled ? ' has-register' : ''; ?>" id="customer_login">
	<section class="pixel-account-intro">
		<span class="eyebrow">Client Account</span>
		<h2>All your print work, quotes, files, and orders in one place.</h2>
		<p>Sign in to continue from quote request to artwork upload, checkout, production tracking, and delivery updates.</p>
		<ul class="pixel-account-feature-list">
			<li><strong>Track production</strong><span>Follow active jobs from proof review to delivery.</span></li>
			<li><strong>Manage files</strong><span>Keep print-ready artwork connected to your projects.</span></li>
			<li><strong>Move faster</strong><span>Reuse account details during quote and checkout flows.</span></li>
		</ul>
		<div class="pixel-account-support-card">
			<strong>Need help?</strong>
			<span>Call (555) 123-4567 or request a custom quote for complex print jobs.</span>
		</div>
	</section>

	<div class="pixel-account-auth-panel">
		<section class="pixel-account-card pixel-account-login-card">
			<div class="pixel-account-card-head">
				<span>Secure access</span>
				<h2><?php esc_html_e('Sign in', 'woocommerce'); ?></h2>
				<p>Use the email connected to your Pixel order or quote.</p>
			</div>

			<form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>
				<?php do_action('woocommerce_login_form_start'); ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="username"><?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username']) && is_string($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" /><?php // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
					<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
				</p>

				<?php do_action('woocommerce_login_form'); ?>

				<p class="form-row pixel-account-submit-row">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e('Remember me', 'woocommerce'); ?></span>
					</label>
					<?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
					<button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>" name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>"><?php esc_html_e('Log in', 'woocommerce'); ?></button>
				</p>
				<p class="woocommerce-LostPassword lost_password">
					<a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'woocommerce'); ?></a>
				</p>

				<?php do_action('woocommerce_login_form_end'); ?>
			</form>
		</section>

		<?php if ($registration_enabled) : ?>
			<section class="pixel-account-card pixel-account-register-card">
				<div class="pixel-account-card-head">
					<span>New customer</span>
					<h2><?php esc_html_e('Create account', 'woocommerce'); ?></h2>
					<p>Save your details for faster orders, quotes, and artwork reviews.</p>
				</div>

				<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>
					<?php do_action('woocommerce_register_form_start'); ?>

					<?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" /><?php // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>
						</p>
					<?php endif; ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
						<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required aria-required="true" /><?php // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>
					</p>

					<?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e('Required', 'woocommerce'); ?></span></label>
							<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
						</p>
					<?php else : ?>
						<p class="pixel-account-note"><?php esc_html_e('A link to set a new password will be sent to your email address.', 'woocommerce'); ?></p>
					<?php endif; ?>

					<?php do_action('woocommerce_register_form'); ?>

					<p class="woocommerce-form-row form-row pixel-account-submit-row">
						<?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
						<button type="submit" class="woocommerce-Button woocommerce-button button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Create account', 'woocommerce'); ?></button>
					</p>

					<?php do_action('woocommerce_register_form_end'); ?>
				</form>
			</section>
		<?php endif; ?>
	</div>
</div>

<?php do_action('woocommerce_after_customer_login_form'); ?>
