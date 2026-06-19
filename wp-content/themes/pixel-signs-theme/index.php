<?php get_header(); ?>
<section class="section narrow">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('content-card'); ?>>
                <h1><?php the_title(); ?></h1>
                <div><?php the_content(); ?></div>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <article class="content-card">
            <h1>Pixel Signs & Printing</h1>
            <p>Create a page and assign it as the front page to start.</p>
        </article>
    <?php endif; ?>
</section>
<?php get_footer(); ?>
