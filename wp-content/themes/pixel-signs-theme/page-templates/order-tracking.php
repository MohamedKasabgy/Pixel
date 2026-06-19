<?php
/**
 * Template Name: Order Tracking
 */
get_header();
?>
<div class="portal-layout">
    <aside class="portal-sidebar">
        <h2>Client Portal</h2>
        <p>Manage your print jobs</p>
        <a href="<?php echo esc_url(home_url('/client-dashboard/')); ?>">▦ Dashboard</a>
        <a class="active" href="<?php echo esc_url(home_url('/order-tracking/')); ?>">▣ Active Orders</a>
        <a href="<?php echo esc_url(home_url('/request-quote/')); ?>">▤ Quote Requests</a>
        <a href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">▱ Design Files</a>
    </aside>
    <section class="portal-main">
        <div class="section-heading split">
            <div>
                <h1>Order Tracking</h1>
                <p>Track the status of your print production and delivery.</p>
            </div>
            <form class="search-pill"><input placeholder="Enter Order Number (e.g. PX-8492)"></form>
        </div>
        <?php echo do_shortcode('[pixel_order_tracker]'); ?>
    </section>
</div>
<?php get_footer(); ?>
