<?php
if (function_exists('is_product') && is_product()) {
    get_template_part('single-product');
    return;
}

get_header();
?>
<section class="section">
    <?php woocommerce_content(); ?>
</section>
<?php get_footer(); ?>
