<?php
/**
 * Template Name: Products Catalog
 */
get_header();

$supported_categories = [
    'all' => [
        'label' => 'All Products',
        'title' => 'Our Products',
        'description' => 'High-fidelity printing for every commercial need.',
    ],
    'large-format' => [
        'label' => 'Large Format',
        'title' => 'Large Format Printing',
        'description' => 'Oversized print products built for storefronts, events, campaigns, and high-visibility brand moments.',
    ],
    'marketing' => [
        'label' => 'Marketing',
        'title' => 'Marketing Materials',
        'description' => 'Sharp, reliable print collateral for promotions, sales teams, launches, and everyday customer touchpoints.',
    ],
    'signage' => [
        'label' => 'Signage',
        'title' => 'Signs & Display Graphics',
        'description' => 'Durable indoor and outdoor signage for retail spaces, job sites, events, and directional displays.',
    ],
    'apparel' => [
        'label' => 'Apparel',
        'title' => 'Custom Apparel Printing',
        'description' => 'Branded apparel for staff uniforms, campaigns, events, and merchandise programs.',
    ],
    'business-cards' => [
        'label' => 'Business Cards',
        'title' => 'Business Cards & Stationery',
        'description' => 'Premium business essentials with crisp color, strong paper stocks, and polished finishing options.',
    ],
];

$selected_category = 'all';

if (isset($_GET['category'])) {
    $requested_category = sanitize_key(wp_unslash($_GET['category']));

    if (isset($supported_categories[$requested_category])) {
        $selected_category = $requested_category;
    }
}

$products = [
    [
        'title' => 'Vinyl Banners',
        'category' => 'large-format',
        'description' => 'Weather-ready banners for storefronts, events, fences, and promotions.',
        'price' => '$49.00',
        'badge' => 'Outdoor Ready',
        'slug' => 'vinyl-banners',
    ],
    [
        'title' => 'Posters',
        'category' => 'large-format',
        'description' => 'Vibrant poster printing on premium stocks for retail, events, and campaigns.',
        'price' => '$15.00',
        'badge' => 'Fast Turnaround',
        'slug' => 'posters',
    ],
    [
        'title' => 'Roll Up Banners',
        'category' => 'large-format',
        'description' => 'Portable retractable displays with crisp graphics and professional hardware.',
        'price' => '$129.00',
        'badge' => 'Event Favorite',
        'slug' => 'roll-up-banners',
    ],
    [
        'title' => 'Flyers',
        'category' => 'marketing',
        'description' => 'High-impact flyers for campaigns, menus, announcements, and local promotions.',
        'price' => '$35.00',
        'badge' => 'Popular',
        'slug' => 'flyers',
    ],
    [
        'title' => 'Brochures',
        'category' => 'marketing',
        'description' => 'Bi-fold, tri-fold, and custom folded brochures for sales and service teams.',
        'price' => '$55.00',
        'badge' => 'Custom Folds',
        'slug' => 'brochures',
    ],
    [
        'title' => 'Postcards',
        'category' => 'marketing',
        'description' => 'Direct mail and handout postcards with polished color and durable finishes.',
        'price' => '$42.00',
        'badge' => 'Mail Ready',
        'slug' => 'postcards',
    ],
    [
        'title' => 'Menus',
        'category' => 'marketing',
        'description' => 'Restaurant menus, counter cards, and laminated service sheets built for daily use.',
        'price' => '$65.00',
        'badge' => 'Lamination',
        'slug' => 'menus',
    ],
    [
        'title' => 'Yard Signs',
        'category' => 'signage',
        'description' => 'Corrugated plastic signs for real estate, construction, political, and event messaging.',
        'price' => '$24.00',
        'badge' => 'Stake Options',
        'slug' => 'yard-signs',
    ],
    [
        'title' => 'Foam Boards',
        'category' => 'signage',
        'description' => 'Lightweight presentation boards for lobbies, meetings, displays, and exhibitions.',
        'price' => '$38.00',
        'badge' => 'Rigid Display',
        'slug' => 'foam-boards',
    ],
    [
        'title' => 'Stickers',
        'category' => 'signage',
        'description' => 'Durable vinyl stickers with custom shapes for packaging, windows, and giveaways.',
        'price' => '$20.00',
        'badge' => 'Die Cut',
        'slug' => 'stickers',
    ],
    [
        'title' => 'T-Shirts',
        'category' => 'apparel',
        'description' => 'Custom printed shirts for crews, events, launches, and branded merchandise.',
        'price' => '$12.00',
        'badge' => 'Bulk Pricing',
        'slug' => 't-shirts',
    ],
    [
        'title' => 'Staff Polos',
        'category' => 'apparel',
        'description' => 'Professional branded polos with print or embroidery options for teams.',
        'price' => 'Request Quote',
        'badge' => 'Team Wear',
        'slug' => 'staff-polos',
    ],
    [
        'title' => 'Business Cards',
        'category' => 'business-cards',
        'description' => 'Premium cards with matte, gloss, velvet, and soft touch finish options.',
        'price' => '$19.99',
        'badge' => 'Best Seller',
        'slug' => 'business-cards',
    ],
    [
        'title' => 'Appointment Cards',
        'category' => 'business-cards',
        'description' => 'Compact reminder cards for clinics, salons, consultants, and service teams.',
        'price' => '$28.00',
        'badge' => 'Writable Stock',
        'slug' => 'appointment-cards',
    ],
    [
        'title' => 'Custom Quote Product',
        'category' => 'large-format',
        'description' => 'Specialty print projects with custom sizing, materials, finishing, or installation needs.',
        'price' => 'Request Quote',
        'badge' => 'Custom Scope',
        'slug' => 'custom-quote-product',
    ],
];

$filtered_products = 'all' === $selected_category
    ? $products
    : array_values(
        array_filter(
            $products,
            static function ($product) use ($selected_category) {
                return $selected_category === $product['category'];
            }
        )
    );

$page_title = $supported_categories[$selected_category]['title'];
$page_description = $supported_categories[$selected_category]['description'];
?>
<section class="section layout-sidebar">
    <aside class="filter-card">
        <h2>Filters</h2>
        <h3>Categories</h3>
        <?php foreach ($supported_categories as $category_slug => $category) : ?>
            <label>
                <input type="checkbox" <?php checked($selected_category, $category_slug); ?>>
                <a href="<?php echo esc_url('all' === $category_slug ? home_url('/products/') : add_query_arg('category', $category_slug, home_url('/products/'))); ?>">
                    <?php echo esc_html($category['label']); ?>
                </a>
            </label>
        <?php endforeach; ?>
        <h3>Materials</h3>
        <label><input type="checkbox"> Standard Paper</label>
        <label><input type="checkbox"> Premium Cardstock</label>
        <label><input type="checkbox"> Vinyl</label>
        <label><input type="checkbox"> Recycled</label>
    </aside>

    <div>
        <div class="section-heading split">
            <div>
                <h1><?php echo esc_html($page_title); ?></h1>
                <p><?php echo esc_html($page_description); ?></p>
            </div>
            <div class="view-icons">▦ ▤</div>
        </div>
        <div class="product-grid">
            <?php foreach ($filtered_products as $product) : ?>
                <article class="product-card">
                    <div class="product-image-placeholder"><?php echo esc_html($product['badge']); ?></div>
                    <h2><?php echo esc_html($product['title']); ?></h2>
                    <p><?php echo esc_html($product['description']); ?></p>
                    <div class="product-price">
                        <span><?php echo esc_html('Request Quote' === $product['price'] ? 'Pricing' : 'Starting at'); ?></span>
                        <strong><?php echo esc_html($product['price']); ?></strong>
                    </div>
                    <a href="<?php echo esc_url(home_url('/product/' . $product['slug'] . '/')); ?>">View Details →</a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>
