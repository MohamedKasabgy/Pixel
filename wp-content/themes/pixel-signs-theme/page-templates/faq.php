<?php
/**
 * Template Name: FAQ
 */
get_header();
?>
<section class="section narrow">
    <div class="section-heading">
        <span class="eyebrow">Help &amp; answers</span>
        <h1>Frequently Asked Questions</h1>
        <p>Everything you need to know about quotes, files, turnaround, and delivery.</p>
    </div>

    <div class="faq-list">
        <details open>
            <summary>How do I request a quote?</summary>
            <p>Use the <a href="<?php echo esc_url(home_url('/request-quote/')); ?>">Request a Quote</a> page, tell us the product, quantity, and any specs (size, material, finish, deadline), and our team will send transparent pricing.</p>
        </details>
        <details>
            <summary>Can I upload my own design?</summary>
            <p>Yes. Upload print-ready artwork on the <a href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">Upload Artwork</a> page. Every file gets a free review before printing, and we will flag anything that could affect quality.</p>
        </details>
        <details>
            <summary>What file types do you accept?</summary>
            <p>We accept PDF, PNG, JPG/JPEG, AI, PSD, EPS, TIFF, and ZIP. For best results, send high-resolution files with fonts outlined or embedded.</p>
        </details>
        <details>
            <summary>How long does printing take?</summary>
            <p>Most standard jobs are produced within a few business days after artwork approval. Rush options are available when you have a tight deadline — just let us know your date.</p>
        </details>
        <details>
            <summary>Can I track my order?</summary>
            <p>Yes. Use the <a href="<?php echo esc_url(home_url('/order-tracking/')); ?>">Order Tracking</a> page to follow your order from review through production to delivery or pickup.</p>
        </details>
        <details>
            <summary>Do you offer delivery?</summary>
            <p>We offer local delivery and in-store pickup. See <a href="<?php echo esc_url(home_url('/shipping-delivery-policy/')); ?>">Shipping &amp; Delivery</a> and <a href="<?php echo esc_url(home_url('/pickup-locations/')); ?>">Pickup Locations</a> for details.</p>
        </details>
        <details>
            <summary>Can I reorder previous prints?</summary>
            <p>Absolutely. Reorders are quick — reference your previous order number on the <a href="<?php echo esc_url(home_url('/request-quote/')); ?>">Request a Quote</a> page or from your client dashboard, and we will reprint to the same spec.</p>
        </details>
    </div>
</section>
<?php get_footer(); ?>
