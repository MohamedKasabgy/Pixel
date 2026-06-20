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
                    <?php echo esc_html($product_badge); ?>
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
        <h2><?php echo esc_html('Configure Product'); ?></h2>

        <label for="pixel-product-size"><?php echo esc_html('Size'); ?></label>
        <select id="pixel-product-size">
            <option><?php echo esc_html('Standard'); ?></option>
            <option><?php echo esc_html('Large Format'); ?></option>
            <option><?php echo esc_html('Custom Size'); ?></option>
        </select>

        <label for="pixel-product-material"><?php echo esc_html('Material'); ?></label>
        <select id="pixel-product-material">
            <option><?php echo esc_html('Standard Paper'); ?></option>
            <option><?php echo esc_html('Premium Cardstock'); ?></option>
            <option><?php echo esc_html('Vinyl'); ?></option>
            <option><?php echo esc_html('Rigid Board'); ?></option>
        </select>

        <label for="pixel-product-finish"><?php echo esc_html('Finish'); ?></label>
        <select id="pixel-product-finish">
            <option><?php echo esc_html('Matte'); ?></option>
            <option><?php echo esc_html('Gloss'); ?></option>
            <option><?php echo esc_html('Laminated'); ?></option>
            <option><?php echo esc_html('No Finish'); ?></option>
        </select>

        <label for="pixel-product-quantity"><?php echo esc_html('Quantity'); ?></label>
        <select id="pixel-product-quantity">
            <option><?php echo esc_html('100'); ?></option>
            <option><?php echo esc_html('250'); ?></option>
            <option><?php echo esc_html('500'); ?></option>
            <option><?php echo esc_html('1000'); ?></option>
        </select>

        <label for="pixel-product-turnaround"><?php echo esc_html('Turnaround Time'); ?></label>
        <select id="pixel-product-turnaround">
            <option><?php echo esc_html('Standard: 3-5 Business Days'); ?></option>
            <option><?php echo esc_html('Rush: 1-2 Business Days'); ?></option>
            <option><?php echo esc_html('Custom Schedule'); ?></option>
        </select>

        <div class="estimated-total">
            <span><?php echo esc_html('Starting Price'); ?></span>
            <strong><?php echo esc_html($product_price); ?></strong>
        </div>

        <a class="btn btn-primary full" href="<?php echo esc_url($add_to_cart_url); ?>"><?php echo esc_html('Add to Cart'); ?></a>
        <a class="btn btn-outline full" href="<?php echo esc_url($request_quote_url); ?>"><?php echo esc_html('Request Quote'); ?></a>
        <a class="btn btn-dark full" href="<?php echo esc_url($upload_artwork_url); ?>"><?php echo esc_html('Upload Artwork'); ?></a>
    </aside>
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
</section>
<?php get_footer(); ?>
