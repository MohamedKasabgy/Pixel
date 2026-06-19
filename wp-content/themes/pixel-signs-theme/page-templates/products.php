<?php
/**
 * Template Name: Products Catalog
 */
get_header();
?>
<section class="section layout-sidebar">
    <aside class="filter-card">
        <h2>Filters</h2>
        <h3>Categories</h3>
        <label><input type="checkbox" checked> All Products</label>
        <label><input type="checkbox"> Marketing Materials</label>
        <label><input type="checkbox"> Large Format</label>
        <label><input type="checkbox"> Packaging</label>
        <h3>Materials</h3>
        <label><input type="checkbox"> Standard Paper</label>
        <label><input type="checkbox"> Premium Cardstock</label>
        <label><input type="checkbox"> Vinyl</label>
        <label><input type="checkbox"> Recycled</label>
    </aside>

    <div>
        <div class="section-heading split">
            <div>
                <h1>Our Products</h1>
                <p>High-fidelity printing for every commercial need.</p>
            </div>
            <div class="view-icons">▦ ▤</div>
        </div>
        <div class="product-grid">
            <?php
            $products = [
                ['Business Cards', 'Premium finishes including matte, gloss, and soft touch.', '$19.99', 'Best Seller'],
                ['Flyers', 'High-quality, vibrant flyers for promotions and campaigns.', '$35.00', ''],
                ['Brochures', 'Tri-fold, bi-fold, and custom folding options.', '$55.00', ''],
                ['Stickers', 'Durable, weather-resistant vinyl stickers.', '$20.00', ''],
                ['Labels', 'Roll labels and sheet labels for product packaging.', '$40.00', ''],
                ['Posters', 'Large format posters printed on premium paper.', '$15.00', ''],
            ];
            foreach ($products as $product) :
                [$name, $desc, $price, $badge] = $product;
                ?>
                <article class="product-card">
                    <div class="product-image-placeholder"><?php echo esc_html($badge); ?></div>
                    <h2><?php echo esc_html($name); ?></h2>
                    <p><?php echo esc_html($desc); ?></p>
                    <div class="product-price"><span>Starting at</span><strong><?php echo esc_html($price); ?></strong></div>
                    <a href="<?php echo esc_url(home_url('/product/business-cards/')); ?>">View Details →</a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>
