<?php
if (!defined('ABSPATH')) {
    exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Pixel Signs & Printing Home">
        <?php if (has_custom_logo()) : ?>
            <?php the_custom_logo(); ?>
        <?php else : ?>
            <span class="brand-mark">▣</span>
            <span class="brand-text"><strong>Pixel</strong> Signs & Printing</span>
        <?php endif; ?>
    </a>

    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav">☰</button>

    <nav id="primary-nav" class="primary-nav" aria-label="Primary navigation">
        <?php
        wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'fallback_cb'    => false,
            'items_wrap'     => '<ul>%3$s</ul>',
        ]);
        ?>
        <a class="nav-link" href="<?php echo esc_url(home_url('/products/')); ?>">Large Format</a>
        <a class="nav-link" href="<?php echo esc_url(home_url('/products/')); ?>">Marketing</a>
        <a class="nav-link" href="<?php echo esc_url(home_url('/products/')); ?>">Signage</a>
        <a class="nav-link" href="<?php echo esc_url(home_url('/products/')); ?>">Apparel</a>
        <a class="nav-link" href="<?php echo esc_url(home_url('/products/')); ?>">Business Cards</a>
    </nav>

    <div class="header-actions">
        <a class="ghost-link" href="<?php echo esc_url(wp_login_url()); ?>">Login</a>
        <a class="btn btn-primary" href="<?php echo esc_url(home_url('/request-quote/')); ?>">Request Quote</a>
    </div>
</header>
<main id="main" class="site-main">
