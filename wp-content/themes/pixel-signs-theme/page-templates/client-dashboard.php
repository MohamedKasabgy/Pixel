<?php
/**
 * Template Name: Client Dashboard
 */
get_header();
?>
<div class="portal-layout">
    <aside class="portal-sidebar">
        <h2>Client Portal</h2>
        <p>Manage your print jobs</p>
        <a class="active" href="<?php echo esc_url(home_url('/client-dashboard/')); ?>">▦ Dashboard</a>
        <a href="<?php echo esc_url(home_url('/order-tracking/')); ?>">▣ Active Orders</a>
        <a href="<?php echo esc_url(home_url('/request-quote/')); ?>">▤ Quote Requests</a>
        <a href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">▱ Design Files</a>
        <a href="#">⚙ Settings</a>
        <a class="new-project" href="<?php echo esc_url(home_url('/request-quote/')); ?>">＋ New Project</a>
    </aside>
    <section class="portal-main">
        <div class="portal-heading">
            <div>
                <h1>Welcome back!</h1>
                <p>Here's the latest on your print projects.</p>
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
