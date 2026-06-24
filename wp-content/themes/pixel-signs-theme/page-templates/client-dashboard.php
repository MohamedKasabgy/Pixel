<?php
/**
 * Template Name: Client Dashboard
 */
get_header();
<<<<<<< Updated upstream
=======
?>
<?php if (!is_user_logged_in()) : ?>
<div class="portal-guest-layout" style="max-width: 800px; margin: 4rem auto; padding: 2rem;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1>Client Portal</h1>
        <p>Please sign in or create an account to view your orders, quotes, and saved files.</p>
    </div>
    <div class="account-panel-grid">
        <form class="account-card" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="pixel_login_customer">
            <?php wp_nonce_field('pixel_login_action', 'pixel_login_nonce'); ?>
            <h3>Sign in</h3>
            <?php if (isset($_GET['login_error'])) : ?>
                <p class="error-text" style="color:var(--px-accent);font-size:0.875rem;margin-bottom:0.5rem;">Login failed. Please check your credentials.</p>
            <?php endif; ?>
            <label>Email<input type="email" name="log" autocomplete="email"></label>
            <label>Password<input type="password" name="pwd" autocomplete="current-password"></label>
            <button class="btn btn-primary full" type="submit">Login</button>
        </form>
        <form class="account-card account-register" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
            <input type="hidden" name="action" value="pixel_register_customer">
            <?php wp_nonce_field('pixel_register_action', 'pixel_register_nonce'); ?>
            <h3>Create account</h3>
            <?php if (isset($_GET['register_error'])) : ?>
                <p class="error-text" style="color:var(--px-accent);font-size:0.875rem;margin-bottom:0.5rem;">Registration failed. Please try again.</p>
            <?php endif; ?>
            <div class="form-grid compact">
                <label>First name<input name="first_name" autocomplete="given-name"></label>
                <label>Last name<input name="last_name" autocomplete="family-name"></label>
            </div>
            <label>Email<input type="email" name="user_email" autocomplete="email"></label>
            <label>Phone<input type="tel" name="phone" autocomplete="tel"></label>
            <label>Password<input type="password" name="password" autocomplete="new-password"></label>
            <label class="checkbox-line"><input type="checkbox" name="marketing_opt_in" value="1"> Send me print tips and Pixel offers.</label>
            <button class="btn btn-dark full" type="submit">Create Account</button>
        </form>
    </div>
</div>
<?php else : 
$portal_user = wp_get_current_user();
$portal_name = $portal_user->first_name !== '' ? $portal_user->first_name : $portal_user->display_name;
>>>>>>> Stashed changes
?>
<div class="portal-layout">
    <aside class="portal-sidebar">
        <h2>Client Portal</h2>
<<<<<<< Updated upstream
        <p>Manage your print jobs</p>
        <a class="active" href="<?php echo esc_url(home_url('/client-dashboard/')); ?>">▦ Dashboard</a>
        <a href="<?php echo esc_url(home_url('/order-tracking/')); ?>">▣ Active Orders</a>
        <a href="<?php echo esc_url(home_url('/request-quote/')); ?>">▤ Quote Requests</a>
        <a href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">▱ Design Files</a>
        <a href="#">⚙ Settings</a>
        <a class="new-project" href="<?php echo esc_url(home_url('/request-quote/')); ?>">＋ New Project</a>
=======
        <p>Orders, quotes, files, and settings</p>
        <a class="active" href="<?php echo esc_url(home_url('/client-dashboard/')); ?>">Dashboard</a>
        <a href="<?php echo esc_url(home_url('/order-tracking/')); ?>">Order Status</a>
        <a href="<?php echo esc_url(home_url('/request-quote/')); ?>">Quotes</a>
        <a href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">Upload Artwork</a>
        <a href="<?php echo esc_url(home_url('/my-account/')); ?>">Account</a>
        <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>">Logout</a>
        <a class="new-project" href="<?php echo esc_url(home_url('/request-quote/')); ?>">New Project</a>
>>>>>>> Stashed changes
    </aside>
    <section class="portal-main">
        <div class="portal-heading">
            <div>
<<<<<<< Updated upstream
                <h1>Welcome back!</h1>
                <p>Here's the latest on your print projects.</p>
=======
                <span class="eyebrow">Pixel Customer Workspace</span>
                <h1>Welcome, <?php echo esc_html($portal_name); ?></h1>
                <p>Manage print orders, proofs, quote requests, saved designs, mailing lists, and account details from one polished portal.</p>
>>>>>>> Stashed changes
            </div>
            <div class="portal-actions">
                <a class="btn btn-dark" href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">Upload Artwork</a>
                <a class="btn btn-primary" href="<?php echo esc_url(home_url('/request-quote/')); ?>">Request Quote</a>
            </div>
        </div>

        <?php echo do_shortcode('[pixel_client_portal]'); ?>

        <?php if (isset($_GET['quote_success'])) : ?>
        <div class="notice" style="margin-top:16px;">Your quote request was submitted. We will contact you shortly.</div>
        <?php endif; ?>
        <?php if (isset($_GET['upload_success'])) : ?>
        <div class="notice" style="margin-top:16px;">Your artwork was uploaded successfully. Our team will review it shortly.</div>
        <?php endif; ?>

    </section>
</div>
<?php endif; ?>
<?php get_footer(); ?>
