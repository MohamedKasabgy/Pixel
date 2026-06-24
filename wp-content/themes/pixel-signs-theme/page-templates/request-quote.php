<?php
/**
 * Template Name: Request Quote
 */
get_header();
?>
<section class="section quote-hero">
    <div class="quote-hero-copy">
        <span class="eyebrow">Custom Print Jobs</span>
        <h1>Custom Print Jobs - Our Specialty</h1>
        <p>Need a custom size, special material, unusual quantity, installation detail, or a file review before production? Send the project details and the Pixel team will prepare a practical quote path.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="#quote-form">Start Quote</a>
            <a class="btn btn-outline" href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">Upload Artwork</a>
        </div>
    </div>
    <div class="quote-hero-card">
        <h2>3 Ways to Get a Custom Print Quote</h2>
        <div class="quote-way">
            <strong>1. Fill out the form</strong>
            <span>Send product details, quantity, size, delivery needs, and deadline.</span>
        </div>
        <div class="quote-way">
            <strong>2. Call us</strong>
            <span>Talk through complex signage, packaging, or production timing.</span>
        </div>
        <div class="quote-way">
            <strong>3. Upload artwork</strong>
            <span>Attach a file or request a free pre-press review before pricing.</span>
        </div>
    </div>
</section>

<section class="section quote-process">
    <div class="section-heading center">
        <span class="eyebrow">Quote Builder</span>
        <h2>Tell us what you want to print</h2>
        <p>The form below keeps backend saving logic intact while collecting richer print-production details for the demo.</p>
    </div>
    <div id="quote-form" class="quote-form-shell">
        <?php echo do_shortcode('[pixel_quote_form]'); ?>
    </div>
</section>
<?php get_footer(); ?>
