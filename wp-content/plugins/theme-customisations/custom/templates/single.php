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
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <div id="page" role="main">
        <?php do_action('foundationpress_before_content'); ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('main-content') ?> id="post-<?php the_ID(); ?>">
                <div class="entry-content">
                    <?php if (is_front_page()): ?>
                        <?php the_content(); ?>
                    <?php else: ?>
                    <div class="">
                        <?php the_content(); ?>
                        <?php endif ?>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>

<?php get_footer();
