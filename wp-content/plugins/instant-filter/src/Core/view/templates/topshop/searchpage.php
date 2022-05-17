<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
/**
 * ONG search template
 */

get_header(); ?>

    <section id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php if (function_exists('bcn_display')) : ?>
                <div class="breadcrumbs">
                    <?php bcn_display(); ?>
                </div>
            <?php endif; ?>

            <header class="page-header">
                <h1 class="page-title"><?php printf(__('Search Results for: %s', 'topshop'), '<span>' . get_search_query() . '</span>'); ?></h1>
            </header><!-- .page-header -->
            <div class="os-page-container"></div>

        </main><!-- #main -->
    </section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>