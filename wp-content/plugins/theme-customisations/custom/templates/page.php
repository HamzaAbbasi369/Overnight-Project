<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package FoundationPress
 * @since   FoundationPress 1.0.0
 */

get_header();
?>
    <div id="page" role="main">
        <?php do_action('foundationpress_before_content'); ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('main-content') ?> id="post-<?php the_ID(); ?>">
                <div class="entry-content">
                    <?php if (is_front_page()): ?>
                        <?php the_content(); ?>
                    <?php else: ?>
                    <div class="row middle--row middle--block--content">
                        <?php the_content(); ?>
                        <?php endif ?>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>

<?php get_footer();
