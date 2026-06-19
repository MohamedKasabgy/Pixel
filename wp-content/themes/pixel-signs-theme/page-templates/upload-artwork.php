<?php
/**
 * Template Name: Upload Artwork
 */
get_header();
?>
<section class="section narrow">
    <div class="section-heading center">
        <h1>Upload Artwork</h1>
        <p>Upload production-ready files or attach files to an existing order number.</p>
    </div>
    <?php echo do_shortcode('[pixel_artwork_upload]'); ?>
</section>
<?php get_footer(); ?>
