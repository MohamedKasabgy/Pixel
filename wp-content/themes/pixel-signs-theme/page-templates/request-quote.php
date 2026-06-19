<?php
/**
 * Template Name: Request Quote
 */
get_header();
?>
<section class="section narrow">
    <div class="section-heading center">
        <h1>Request a Custom Quote</h1>
        <p>Tell us about your print project and upload your artwork or specifications.</p>
    </div>
    <?php echo do_shortcode('[pixel_quote_form]'); ?>
</section>
<?php get_footer(); ?>
