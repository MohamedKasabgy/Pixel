<?php get_header(); ?>
<section class="section narrow">
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('content-card'); ?>>
            <h1><?php the_title(); ?></h1>
            <div><?php the_content(); ?></div>
        </article>
    <?php endwhile; ?>
</section>
<?php get_footer(); ?>
