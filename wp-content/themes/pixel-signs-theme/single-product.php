<?php
get_header();

$post_id = get_the_ID();
$product_slug = $post_id ? get_post_field('post_name', $post_id) : 'business-cards';

$demo_products = [
    'vinyl-banners' => [
        'title' => 'Vinyl Banners',
        'description' => 'Weather-ready banners for storefronts, events, fences, and promotions.',
        'price' => '$49.00',
        'badge' => 'Outdoor Ready',
    ],
    'posters' => [
        'title' => 'Posters',
        'description' => 'Vibrant poster printing on premium stocks for retail, events, and campaigns.',
        'price' => '$15.00',
        'badge' => 'Fast Turnaround',
    ],
    'roll-up-banners' => [
        'title' => 'Roll Up Banners',
        'description' => 'Portable retractable displays with crisp graphics and professional hardware.',
        'price' => '$129.00',
        'badge' => 'Event Favorite',
    ],
    'flyers' => [
        'title' => 'Flyers',
        'description' => 'High-impact flyers for campaigns, menus, announcements, and local promotions.',
        'price' => '$35.00',
        'badge' => 'Popular',
    ],
    'brochures' => [
        'title' => 'Brochures',
        'description' => 'Bi-fold, tri-fold, and custom folded brochures for sales and service teams.',
        'price' => '$55.00',
        'badge' => 'Custom Folds',
    ],
    'postcards' => [
        'title' => 'Postcards',
        'description' => 'Direct mail and handout postcards with polished color and durable finishes.',
        'price' => '$42.00',
        'badge' => 'Mail Ready',
    ],
    'menus' => [
        'title' => 'Menus',
        'description' => 'Restaurant menus, counter cards, and laminated service sheets built for daily use.',
        'price' => '$65.00',
        'badge' => 'Lamination',
    ],
    'yard-signs' => [
        'title' => 'Yard Signs',
        'description' => 'Corrugated plastic signs for real estate, construction, political, and event messaging.',
        'price' => '$24.00',
        'badge' => 'Stake Options',
    ],
    'foam-boards' => [
        'title' => 'Foam Boards',
        'description' => 'Lightweight presentation boards for lobbies, meetings, displays, and exhibitions.',
        'price' => '$38.00',
        'badge' => 'Rigid Display',
    ],
    'stickers' => [
        'title' => 'Stickers',
        'description' => 'Durable vinyl stickers with custom shapes for packaging, windows, and giveaways.',
        'price' => '$20.00',
        'badge' => 'Die Cut',
    ],
    'labels' => [
        'title' => 'Labels',
        'description' => 'Custom product labels with durable finishes for bottles, boxes, bags, and retail packaging.',
        'price' => '$30.00',
        'badge' => 'Custom Shapes',
    ],
    'roll-labels' => [
        'title' => 'Roll Labels',
        'description' => 'Roll-format labels for packaging lines, bottles, jars, shipping, and promotions.',
        'price' => '$44.00',
        'badge' => 'Roll Format',
    ],
    't-shirts' => [
        'title' => 'T-Shirts',
        'description' => 'Custom printed shirts for crews, events, launches, and branded merchandise.',
        'price' => '$12.00',
        'badge' => 'Bulk Pricing',
    ],
    'staff-polos' => [
        'title' => 'Staff Polos',
        'description' => 'Professional branded polos with print or embroidery options for teams.',
        'price' => 'Request Quote',
        'badge' => 'Team Wear',
    ],
    'business-cards' => [
        'title' => 'Business Cards',
        'description' => 'Premium cards with matte, gloss, velvet, and soft touch finish options.',
        'price' => '$19.99',
        'badge' => 'Best Seller',
    ],
    'appointment-cards' => [
        'title' => 'Appointment Cards',
        'description' => 'Compact reminder cards for clinics, salons, consultants, and service teams.',
        'price' => '$28.00',
        'badge' => 'Writable Stock',
    ],
    'packaging-boxes' => [
        'title' => 'Packaging Boxes',
        'description' => 'Branded folding cartons and product boxes for retail, gifts, launches, and promotions.',
        'price' => 'Request Quote',
        'badge' => 'Retail Ready',
    ],
    'paper-bags' => [
        'title' => 'Paper Bags',
        'description' => 'Custom printed paper bags for retail pickup, events, product launches, and gifts.',
        'price' => 'Request Quote',
        'badge' => 'Retail Bags',
    ],
    'packing-tape' => [
        'title' => 'Packing Tape',
        'description' => 'Branded packing tape for shipping cartons, subscription boxes, and fulfillment teams.',
        'price' => 'Request Quote',
        'badge' => 'Fulfillment',
    ],
    'custom-quote-product' => [
        'title' => 'Custom Quote Product',
        'description' => 'Specialty print projects with custom sizing, materials, finishing, or installation needs.',
        'price' => 'Request Quote',
        'badge' => 'Custom Scope',
    ],
];

$fallback_product = $demo_products[$product_slug] ?? $demo_products['business-cards'];
$wc_product = null;

if (function_exists('wc_get_product') && $post_id) {
    $wc_product = wc_get_product($post_id);
}

$product_title = $fallback_product['title'];
$product_description = $fallback_product['description'];
$product_price = $fallback_product['price'];
$product_badge = $fallback_product['badge'];
$product_image_url = '';
$add_to_cart_url = home_url('/cart/');

if ($wc_product) {
    $product_title = $wc_product->get_name() ?: $product_title;

    $wc_description = $wc_product->get_short_description() ?: $wc_product->get_description();
    if ($wc_description) {
        $product_description = wp_strip_all_tags($wc_description);
    }

    $wc_price = wp_strip_all_tags($wc_product->get_price_html());
    if ($wc_price) {
        $product_price = $wc_price;
    }

    if ($wc_product->is_on_sale()) {
        $product_badge = 'Featured Offer';
    }

    $image_id = $wc_product->get_image_id();
    if ($image_id) {
        $product_image_url = wp_get_attachment_image_url($image_id, 'large');
    }

    if ($wc_product->is_purchasable()) {
        $add_to_cart_url = $wc_product->add_to_cart_url();
    }
} elseif ($post_id && get_the_title($post_id)) {
    $product_title = get_the_title($post_id);
}

$request_quote_url = add_query_arg('product', $product_slug, home_url('/request-quote/'));
$upload_artwork_url = add_query_arg('product', $product_slug, home_url('/upload-artwork/'));
?>
<section class="section product-detail-layout">
    <div>
        <div class="breadcrumb">
            <?php echo esc_html('Home'); ?> &rsaquo; <?php echo esc_html('Products'); ?> &rsaquo; <?php echo esc_html($product_title); ?>
        </div>
        <div class="product-gallery-card">
            <div class="main-product-image">
                <?php if ($product_image_url) : ?>
                    <img src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr($product_title); ?>">
                <?php else : ?>
                    <div class="product-mockup-scene" data-product="<?php echo esc_attr($product_slug); ?>">
                        <span><?php echo esc_html($product_badge); ?></span>
                        <strong><?php echo esc_html($product_title); ?></strong>
                    </div>
                <?php endif; ?>
            </div>
            <div class="thumb-row"><span></span><span></span><span></span><span></span></div>
        </div>
        <span class="eyebrow"><?php echo esc_html($product_badge); ?></span>
        <h1><?php echo esc_html($product_title); ?></h1>
        <p class="lead"><?php echo esc_html($product_description); ?></p>
        <div class="benefit-row">
            <span><?php echo esc_html('Fast Turnaround'); ?></span>
            <span><?php echo esc_html('Quality Checked'); ?></span>
            <span><?php echo esc_html('Artwork Support'); ?></span>
        </div>
    </div>
    <aside class="config-card">
        <?php if ($wc_product && $wc_product->is_purchasable() && $wc_product->is_in_stock()) : ?>
            <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $wc_product->get_permalink())); ?>" method="post" enctype="multipart/form-data">
                
                <?php do_action('woocommerce_before_add_to_cart_button'); ?>

                <div class="estimated-total">
                    <span><?php echo esc_html('Starting Price'); ?></span>
                    <strong><?php echo esc_html($product_price); ?></strong>
                </div>

                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($wc_product->get_id()); ?>" />
                
                <?php
                if (function_exists('woocommerce_quantity_input')) {
                    woocommerce_quantity_input([
                        'min_value'   => apply_filters('woocommerce_quantity_input_min', $wc_product->get_min_purchase_quantity(), $wc_product),
                        'max_value'   => apply_filters('woocommerce_quantity_input_max', $wc_product->get_max_purchase_quantity(), $wc_product),
                        'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $wc_product->get_min_purchase_quantity(),
                        'classes'     => apply_filters('woocommerce_quantity_input_classes', ['input-text', 'qty', 'text'], $wc_product),
                    ], $wc_product);
                } else {
                    echo '<input type="hidden" name="quantity" value="1" />';
                }
                ?>
                
                <button type="submit" class="single_add_to_cart_button btn btn-primary full"><?php echo esc_html('Add to Cart'); ?></button>
            </form>
        <?php else : ?>
            <div class="estimated-total">
                <span><?php echo esc_html('Starting Price'); ?></span>
                <strong><?php echo esc_html($product_price); ?></strong>
            </div>
            <a class="btn btn-primary full" href="<?php echo esc_url($add_to_cart_url); ?>"><?php echo esc_html('Add to Cart'); ?></a>
        <?php endif; ?>

        <a class="btn btn-outline full" href="<?php echo esc_url($request_quote_url); ?>"><?php echo esc_html('Request Quote'); ?></a>
        <a class="btn btn-dark full" href="<?php echo esc_url($upload_artwork_url); ?>"><?php echo esc_html('Upload Artwork'); ?></a>
    </aside>
</section>
<section class="section">
    <div class="section-heading split">
        <div>
            <span class="eyebrow"><?php echo esc_html('Related Products'); ?></span>
            <h2><?php echo esc_html('Complete the print set'); ?></h2>
            <p><?php echo esc_html('Pair this product with other Pixel print pieces for a polished campaign or launch kit.'); ?></p>
        </div>
        <a class="btn btn-outline" href="<?php echo esc_url(home_url('/products/')); ?>"><?php echo esc_html('Browse All Products'); ?></a>
    </div>
    <div class="featured-product-grid compact-related">
        <?php
        $related_products = [
            ['title' => 'Flyers', 'slug' => 'flyers', 'copy' => 'Promotional handouts and local campaign pieces.'],
            ['title' => 'Custom Labels', 'slug' => 'labels', 'copy' => 'Packaging labels with durable print finishes.'],
            ['title' => 'Upload Artwork', 'slug' => 'upload', 'copy' => 'Send files to the Pixel pre-press team.'],
        ];
        foreach ($related_products as $related) :
            $related_url = $related['slug'] === 'upload'
                ? home_url('/upload-artwork/')
                : home_url('/product/' . $related['slug'] . '/');
        ?>
            <article class="commerce-product-card mini">
                <a class="commerce-product-visual" data-product-mockup="<?php echo esc_attr($related['slug'] === 'labels' ? 'labels' : 'flyer'); ?>" href="<?php echo esc_url($related_url); ?>">
                    <span><?php echo esc_html($related['title']); ?></span>
                </a>
                <div class="commerce-product-body">
                    <h3><?php echo esc_html($related['title']); ?></h3>
                    <p><?php echo esc_html($related['copy']); ?></p>
                    <div class="commerce-product-meta"><a href="<?php echo esc_url($related_url); ?>"><?php echo esc_html('View'); ?></a></div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<section class="section narrow">
    <h2 class="center-text"><?php echo esc_html('Frequently Asked Questions'); ?></h2>
    <details open>
        <summary><?php echo esc_html('What file formats do you accept for artwork?'); ?></summary>
        <p><?php echo esc_html('We recommend high-resolution PDF files with crop marks and bleed. We also accept TIFF, JPEG, EPS, AI, and PSD when production-ready.'); ?></p>
    </details>
    <details>
        <summary><?php echo esc_html('Can I request a custom size or material?'); ?></summary>
        <p><?php echo esc_html('Yes. Choose custom options on this page or send the project details through the quote request flow.'); ?></p>
    </details>
    <details>
        <summary><?php echo esc_html('Can I upload artwork after checkout?'); ?></summary>
        <p><?php echo esc_html('Yes. Use Upload Artwork with your order number, or upload files from the client portal after the order is placed.'); ?></p>
    </details>
</section>
<?php get_footer(); ?>
