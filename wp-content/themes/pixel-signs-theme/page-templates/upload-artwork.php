<?php
/**
 * Template Name: Upload Artwork
 */
get_header();
?>
<section class="section upload-hero">
    <div>
        <span class="eyebrow">File Review Desk</span>
        <h1>Upload Artwork for Your Print Job</h1>
        <p>Send production files, reference layouts, or revised artwork tied to an order, quote, or project. Pixel validates file types and stores uploads for staff review through the existing workflow.</p>
        <div class="upload-rules">
            <span>PDF</span>
            <span>PNG</span>
            <span>JPG/JPEG</span>
            <span>AI</span>
            <span>PSD</span>
            <span>EPS</span>
            <span>ZIP</span>
        </div>
    </div>
    <div class="upload-checklist">
        <h2>Before you upload</h2>
        <ul>
            <li>Use CMYK artwork when possible.</li>
            <li>Include bleed for trimmed products.</li>
            <li>Package linked files or fonts in a ZIP when needed.</li>
            <li>Add your order or quote number if you have one.</li>
        </ul>
    </div>
</section>

<section class="section narrow">
    <div class="section-heading center">
        <span class="eyebrow">Submit Files</span>
        <h2>Artwork Upload Form</h2>
        <p>Accepted files are checked for safe artwork types before storage.</p>
    </div>
    <?php echo do_shortcode('[pixel_artwork_upload]'); ?>
</section>
<?php get_footer(); ?>
