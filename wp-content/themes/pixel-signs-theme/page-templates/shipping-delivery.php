<?php
/**
 * Template Name: Shipping & Delivery
 */
get_header();
?>
<section class="section narrow">
    <article class="content-card policy-content" style="padding: 36px;">
        <h1>Shipping &amp; Delivery</h1>
        <p><em>Demo shipping and delivery content for Pixel Signs &amp; Printing. Confirm service areas and carrier details before launch.</em></p>

        <h2>Delivery options</h2>
        <ul>
            <li><strong>Local delivery</strong> within our service area for eligible orders.</li>
            <li><strong>Standard shipping</strong> via our carrier partners.</li>
            <li><strong>In-store pickup</strong> — see <a href="<?php echo esc_url(home_url('/pickup-locations/')); ?>">Pickup Locations</a>.</li>
        </ul>

        <h2>Processing &amp; turnaround</h2>
        <p>Orders are produced after artwork approval. Most standard jobs ship within a few business days; rush options are available on request. You will receive tracking once your order is on its way.</p>

        <h2>Delivery times</h2>
        <p>Delivery estimates depend on your location and the shipping method selected at checkout. Estimates are provided when your order ships and may vary during peak periods.</p>

        <h2>Tracking your order</h2>
        <p>Follow your order anytime on the <a href="<?php echo esc_url(home_url('/order-tracking/')); ?>">Order Tracking</a> page.</p>

        <p style="margin-top: 24px;"><strong>Last updated:</strong> June 26, 2026</p>
    </article>
</section>
<?php get_footer(); ?>
