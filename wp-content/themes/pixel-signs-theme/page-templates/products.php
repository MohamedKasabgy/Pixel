<?php
/**
 * Template Name: Products Catalog
 */
get_header();

$category_terms = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);

if (is_wp_error($category_terms)) {
    $category_terms = [];
}

$selected_category = '';

if (isset($_GET['category'])) {
    $selected_category = sanitize_title(wp_unslash($_GET['category']));
}

$selected_category_term = $selected_category !== ''
    ? get_term_by('slug', $selected_category, 'product_cat')
    : null;

$products = pixel_signs_get_catalog_products($selected_category);

if ($selected_category_term instanceof WP_Term) {
    $page_title = $selected_category_term->name;
    $page_description = $selected_category_term->description !== ''
        ? $selected_category_term->description
        : sprintf(__('Browse Pixel products in %s.', 'pixel-signs'), $selected_category_term->name);
} elseif ($selected_category !== '') {
    $page_title = sprintf(__('Products: %s', 'pixel-signs'), ucwords(str_replace('-', ' ', $selected_category)));
    $page_description = __('No matching WooCommerce category was found for this filter yet.', 'pixel-signs');
} else {
    $page_title = __('Our Products', 'pixel-signs');
    $page_description = __('High-fidelity printing for every commercial need.', 'pixel-signs');
}
?>
<section class="section layout-sidebar">
    <aside class="filter-card">
        <h2><?php echo esc_html__('Filters', 'pixel-signs'); ?></h2>
        <h3><?php echo esc_html__('Categories', 'pixel-signs'); ?></h3>

        <label class="<?php echo $selected_category === '' ? 'active' : ''; ?>">
            <input type="checkbox" <?php checked($selected_category, ''); ?>>
            <a href="<?php echo esc_url(home_url('/products/')); ?>">
                <?php echo esc_html__('All Products', 'pixel-signs'); ?>
            </a>
        </label>

        <?php foreach ($category_terms as $category_term) : ?>
            <label class="<?php echo $selected_category === $category_term->slug ? 'active' : ''; ?>">
                <input type="checkbox" <?php checked($selected_category, $category_term->slug); ?>>
                <a href="<?php echo esc_url(add_query_arg('category', $category_term->slug, home_url('/products/'))); ?>">
                    <?php echo esc_html($category_term->name); ?>
                </a>
            </label>
        <?php endforeach; ?>

        <h3><?php echo esc_html__('Artwork Ready', 'pixel-signs'); ?></h3>
        <p class="filter-help"><?php echo esc_html__('Choose a product, request a quote, or upload files directly to the Pixel pre-press team.', 'pixel-signs'); ?></p>
    </aside>

    <div>
        <div class="section-heading split">
            <div>
                <span class="eyebrow"><?php echo esc_html__('WooCommerce Catalog', 'pixel-signs'); ?></span>
                <h1><?php echo esc_html($page_title); ?></h1>
                <p><?php echo esc_html($page_description); ?></p>
            </div>
            <div class="catalog-count">
                <?php
                printf(
                    esc_html(_n('%d product', '%d products', count($products), 'pixel-signs')),
                    esc_html((string) count($products))
                );
                ?>
            </div>
        </div>

        <?php if (!empty($products)) : ?>
            <div class="product-grid">
                <?php foreach ($products as $product) : ?>
                    <?php
                    if (!$product instanceof WC_Product) {
                        continue;
                    }

                    $product_slug = $product->get_slug();
                    $product_url = get_permalink($product->get_id());
                    $request_quote_url = add_query_arg('product', $product_slug, home_url('/request-quote/'));
                    $upload_artwork_url = add_query_arg('product', $product_slug, home_url('/upload-artwork/'));
                    $price_html = $product->get_price_html();
                    ?>
                    <article class="product-card pixel-catalog-product-card">
                        <a class="product-card-media" href="<?php echo esc_url($product_url); ?>" aria-label="<?php echo esc_attr(sprintf(__('View %s', 'pixel-signs'), $product->get_name())); ?>">
                            <?php pixel_signs_render_product_visual($product); ?>
                        </a>
                        <div class="product-card-content">
                            <span class="product-card-category"><?php echo esc_html(pixel_signs_get_product_category_label($product)); ?></span>
                            <h2><?php echo esc_html($product->get_name()); ?></h2>
                            <p><?php echo esc_html(pixel_signs_get_product_card_description($product)); ?></p>
                        </div>
                        <div class="product-price">
                            <span><?php echo $price_html !== '' ? esc_html__('Starting at', 'pixel-signs') : esc_html__('Pricing', 'pixel-signs'); ?></span>
                            <strong><?php echo $price_html !== '' ? wp_kses_post($price_html) : esc_html__('Request Quote', 'pixel-signs'); ?></strong>
                        </div>
                        <div class="product-actions">
                            <a href="<?php echo esc_url($product_url); ?>"><?php echo esc_html__('View Details', 'pixel-signs'); ?></a>
                            <a href="<?php echo esc_url($request_quote_url); ?>"><?php echo esc_html__('Request Quote', 'pixel-signs'); ?></a>
                            <a href="<?php echo esc_url($upload_artwork_url); ?>"><?php echo esc_html__('Upload Artwork', 'pixel-signs'); ?></a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="empty-panel catalog-empty-state">
                <span class="eyebrow"><?php echo esc_html__('Catalog Empty', 'pixel-signs'); ?></span>
                <h2><?php echo esc_html__('No products are published yet', 'pixel-signs'); ?></h2>
                <p><?php echo esc_html__('Published WooCommerce products will appear here automatically. Add a product in WordPress admin, assign a category, image, description, and price, then refresh this page.', 'pixel-signs'); ?></p>
                <a class="btn btn-primary" href="<?php echo esc_url(home_url('/request-quote/')); ?>"><?php echo esc_html__('Request a Custom Quote', 'pixel-signs'); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
