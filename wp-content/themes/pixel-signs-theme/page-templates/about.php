<?php
/**
 * Template Name: About
 */
get_header();
?>
<section class="section narrow">
    <article class="content-card policy-content" style="padding: 36px;">
        <span class="eyebrow">About Pixel</span>
        <h1>Commercial Printing, Done Right</h1>
        <p class="lead">Pixel Signs &amp; Printing is a full-service print shop helping growing businesses look their best — from a single banner to a full brand rollout across signage, marketing, apparel, and packaging.</p>

        <h2>What we do</h2>
        <p>We combine commercial-grade equipment with a hands-on team that reviews every job before it runs. Whether you need large-format signage for a storefront, a stack of business cards, branded apparel for your team, or custom packaging, we produce work that is sharp, durable, and on-brand.</p>

        <h2>How we work</h2>
        <ul>
            <li><strong>Free file review</strong> on every order, so problems are caught before printing.</li>
            <li><strong>Transparent custom quotes</strong> sized to your spec and quantity.</li>
            <li><strong>Fast turnaround</strong> with rush options when deadlines are tight.</li>
            <li><strong>Local delivery or pickup</strong> to fit how you work.</li>
        </ul>

        <h2>Built for growing businesses</h2>
        <p>From first-time orders to high-volume reorders, we make professional printing simple. Tell us what you need and we will help you get it produced.</p>

        <p style="margin-top: 28px;">
            <a class="btn btn-primary" href="<?php echo esc_url(home_url('/request-quote/')); ?>">Request a Quote</a>
            <a class="btn btn-outline" href="<?php echo esc_url(home_url('/products/')); ?>">Browse Products</a>
        </p>

        <?php
        // Allow optional editor content to append below the baked starter content.
        while (have_posts()) :
            the_post();
            the_content();
        endwhile;
        ?>
    </article>
</section>
<?php get_footer(); ?>
