<?php
if (!defined('ABSPATH')) {
    exit;
}

$pixel_cart_count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;

$pixel_mega_menus = [
    'featured-products' => [
        'label' => 'Featured Products',
        'href'  => home_url('/products/'),
        'groups' => [
            'Popular right now' => ['Business Cards', 'Vinyl Banners', 'Flyers', 'Custom Stickers'],
            'Fast turnaround'   => ['Postcards', 'Foam Boards', 'Yard Signs', 'Roll Labels'],
            'Pixel services'    => ['Free File Review', 'Design Services', 'Custom Quotes', 'Upload Artwork'],
        ],
        'feature' => 'Launch-ready print bundles for storefronts, events, teams, and product packaging.',
    ],
    'stickers-labels' => [
        'label' => 'Stickers & Labels',
        'href'  => home_url('/products/?category=stickers-labels'),
        'groups' => [
            'Labels'  => ['Custom Labels', 'Roll Labels', 'Die Cut Labels', 'Waterproof Labels'],
            'Stickers'=> ['Custom Stickers', 'Vinyl Stickers', 'Bumper Stickers', 'QR Code Stickers'],
            'Finishes'=> ['Matte Labels', 'Gloss Labels', 'Clear Labels', 'Outdoor Vinyl'],
        ],
        'feature' => 'Durable custom labels and stickers for products, packaging, windows, and giveaways.',
    ],
    'marketing-materials' => [
        'label' => 'Marketing Materials',
        'href'  => home_url('/products/?category=marketing'),
        'groups' => [
            'Essentials' => ['Flyers', 'Brochures', 'Postcards', 'Business Cards'],
            'Sales tools'=> ['Door Hangers', 'Rack Cards', 'Catalogs', 'Booklets'],
            'Restaurant'=> ['Menus', 'Table Tents', 'Loyalty Cards', 'Coupons'],
        ],
        'feature' => 'Campaign-ready print pieces for sales teams, launches, mailers, and local promotions.',
    ],
    'signs-banners' => [
        'label' => 'Signs & Banners',
        'href'  => home_url('/products/?category=large-format'),
        'groups' => [
            'Banners' => ['Vinyl Banners', 'Retractable Banners', 'Mesh Banners', 'Large Format Posters'],
            'Signs'   => ['Yard Signs', 'Foam Boards', 'Car Magnets', 'Table Covers'],
            'Displays'=> ['Window Vinyls', 'Acrylic Signs', 'Lobby Signs', 'Event Backdrops'],
        ],
        'feature' => 'Large format production for storefronts, trade shows, streetside promos, and wayfinding.',
    ],
    'promotional-products' => [
        'label' => 'Promotional Products',
        'href'  => home_url('/products/?category=apparel'),
        'groups' => [
            'Apparel' => ['T-Shirts', 'Hats', 'Polo Shirts', 'Staff Uniforms'],
            'Giveaways'=> ['Notepads', 'Bookmarks', 'Calendars', 'Magnets'],
            'Teams'   => ['Event Shirts', 'Crew Merch', 'Launch Kits', 'Bulk Orders'],
        ],
        'feature' => 'Useful branded pieces that keep your company visible after the first impression.',
    ],
    'packaging' => [
        'label' => 'Packaging',
        'href'  => home_url('/products/?category=packaging'),
        'groups' => [
            'Labels & sleeves' => ['Labels', 'Pouches', 'Bag Toppers', 'Packaging Sleeves'],
            'Bags'             => ['Paper Bags', 'Plastic Bags', 'Gift Bags', 'Tissue Paper'],
            'Supplies'         => ['Packing Tape', 'Box Stickers', 'Thank You Cards', 'Hang Tags'],
        ],
        'feature' => 'Custom packaging pieces that make retail, delivery, and unboxing feel intentional.',
    ],
];
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header" data-commerce-header>
    <div class="commerce-topbar">
        <a href="<?php echo esc_url(home_url('/request-quote/')); ?>">Custom quotes in 1 business day</a>
        <span>Free artwork review on every submitted file</span>
        <a href="<?php echo esc_url(home_url('/order-tracking/')); ?>">Track an order</a>
    </div>

    <div class="commerce-mainbar">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Pixel Signs & Printing Home">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <span class="brand-mark">PX</span>
                <span class="brand-text"><strong>Pixel</strong><small>Signs &amp; Printing</small></span>
            <?php endif; ?>
        </a>

        <form class="commerce-search" role="search" method="get" action="<?php echo esc_url(home_url('/products/')); ?>">
            <label class="screen-reader-text" for="pixel-product-search">Search products</label>
            <input id="pixel-product-search" type="search" name="s" placeholder="Search products, banners, labels, business cards..." autocomplete="off">
            <button type="submit">Search</button>
        </form>

        <button class="nav-toggle" type="button" aria-label="Menu" aria-expanded="false" aria-controls="primary-nav"><span aria-hidden="true">Menu</span></button>

        <nav class="utility-nav" aria-label="Utility navigation">
            <a href="<?php echo esc_url(home_url('/products/')); ?>">Services</a>
            <a href="<?php echo esc_url(home_url('/faq/')); ?>">Help</a>
            <a href="tel:+10000000000">+1 (000) 000-0000</a>
            <?php if ( is_user_logged_in() ) : ?>
                <a href="<?php echo esc_url(home_url('/client-dashboard/')); ?>">Dashboard</a>
            <?php else : ?>
                <div class="account-menu">
                    <button class="account-trigger" type="button" aria-haspopup="true" aria-expanded="false">Account</button>
                    <div class="account-panel">
                        <div class="account-panel-grid">
                            <form class="account-card" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                <input type="hidden" name="action" value="pixel_login_customer">
                                <?php wp_nonce_field('pixel_login_action', 'pixel_login_nonce'); ?>
                                <h3>Sign in</h3>
                                <?php if (isset($_GET['login_error'])) : ?>
                                    <p class="error-text" style="color:var(--px-accent);font-size:0.875rem;margin-bottom:0.5rem;">Login failed. Please check your credentials.</p>
                                <?php endif; ?>
                                <label>Email<input type="email" name="log" autocomplete="email"></label>
                                <label>Password<input type="password" name="pwd" autocomplete="current-password"></label>
                                <button class="btn btn-primary full" type="submit">Login</button>
                            </form>
                            <form class="account-card account-register" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                                <input type="hidden" name="action" value="pixel_register_customer">
                                <?php wp_nonce_field('pixel_register_action', 'pixel_register_nonce'); ?>
                                <h3>Create account</h3>
                                <?php if (isset($_GET['register_error'])) : ?>
                                    <p class="error-text" style="color:var(--px-accent);font-size:0.875rem;margin-bottom:0.5rem;">Registration failed. Please try again.</p>
                                <?php endif; ?>
                                <div class="form-grid compact">
                                    <label>First name<input name="first_name" autocomplete="given-name"></label>
                                    <label>Last name<input name="last_name" autocomplete="family-name"></label>
                                </div>
                                <label>Email<input type="email" name="user_email" autocomplete="email"></label>
                                <label>Phone<input type="tel" name="phone" autocomplete="tel"></label>
                                <label>Password<input type="password" name="password" autocomplete="new-password"></label>
                                <label class="checkbox-line"><input type="checkbox" name="marketing_opt_in" value="1"> Send me print tips and Pixel offers.</label>
                                <button class="btn btn-dark full" type="submit">Create Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <a href="<?php echo esc_url(home_url('/order-tracking/')); ?>">My Orders</a>
            <a href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">My Designs</a>
            <a class="cart-link" href="<?php echo esc_url(home_url('/cart/')); ?>">Cart<?php echo $pixel_cart_count > 0 ? ' (' . esc_html((string) $pixel_cart_count) . ')' : ''; ?></a>
        </nav>
    </div>

    <nav id="primary-nav" class="primary-nav" aria-label="Product categories">
        <?php foreach ($pixel_mega_menus as $menu_key => $menu) : ?>
            <div class="mega-item">
                <a class="mega-link" href="<?php echo esc_url($menu['href']); ?>"><?php echo esc_html($menu['label']); ?></a>
                <div class="mega-panel">
                    <div class="mega-panel-inner">
                        <?php foreach ($menu['groups'] as $group_label => $items) : ?>
                            <div class="mega-column">
                                <h3><?php echo esc_html($group_label); ?></h3>
                                <?php foreach ($items as $item_label) : ?>
                                    <a href="<?php echo esc_url(add_query_arg('product', sanitize_title($item_label), home_url('/request-quote/'))); ?>"><?php echo esc_html($item_label); ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                        <a class="mega-feature" href="<?php echo esc_url($menu['href']); ?>">
                            <span><?php echo esc_html($menu['label']); ?></span>
                            <strong><?php echo esc_html($menu['feature']); ?></strong>
                            <em>Browse category</em>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </nav>
</header>
<main id="main" class="site-main">
