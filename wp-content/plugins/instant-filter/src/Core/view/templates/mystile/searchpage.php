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

get_header();
global $woo_options;
?>
    <div id="content" class="col-full">

        <?php woo_main_before(); ?>

        <section id="main" class="col-left">
            <div class="os-page-container"></div>
        </section><!-- /#main -->

        <?php woo_main_after(); ?>

        <?php get_sidebar(); ?>

    </div><!-- /#content -->

<?php get_footer(); ?>