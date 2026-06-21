<?php
/**
 * Template Name: Client Dashboard
 */
get_header();
$portal_user = wp_get_current_user();
$portal_name = is_user_logged_in()
    ? ($portal_user->first_name !== '' ? $portal_user->first_name : $portal_user->display_name)
    : '';
?>
<div class="portal-layout">
    <aside class="portal-sidebar">
        <h2>Client Portal</h2>
        <p>Manage your print jobs</p>
        <a class="active" href="<?php echo esc_url(home_url('/client-dashboard/')); ?>">▦ Dashboard</a>
        <a href="<?php echo esc_url(home_url('/order-tracking/')); ?>">▣ Active Orders</a>
        <a href="<?php echo esc_url(home_url('/request-quote/')); ?>">▤ Quote Requests</a>
        <a href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">▱ Design Files</a>
        <a href="<?php echo esc_url(home_url('/my-account/edit-account/')); ?>">⚙ Settings</a>
        <a class="new-project" href="<?php echo esc_url(home_url('/request-quote/')); ?>">＋ New Project</a>
    </aside>
    <section class="portal-main">
        <div class="portal-heading">
            <div>
                <h1>Welcome<?php echo $portal_name !== '' ? ', ' . esc_html($portal_name) : ''; ?>!</h1>
                <p>Here is the latest on your print projects.</p>
            </div>
            <div class="portal-actions">
                <a class="btn btn-dark" href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">Upload Artwork</a>
                <a class="btn btn-primary" href="<?php echo esc_url(home_url('/request-quote/')); ?>">New Quote</a>
            </div>
        </div>
        <?php echo do_shortcode('[pixel_client_portal]'); ?>
    </section>
</div>
<?php get_footer(); ?>
