<?php

add_action('ong_theme_top_bar_menu', 'ong_theme_top_bar_menu_cart_link', 30);

function ong_theme_top_bar_menu_cart_link()
{
    ?>
    <li class="hide-for-small-only">
        <a class="cart-customlocation header--card-link" href="<?php echo wc_get_cart_url(); ?>"
           title="<?php _e('View your shopping cart'); ?>"><?php echo sprintf(_n(
               'Cart (%d item)',
               'Cart (%d items)',
               WC()->cart->get_cart_contents_count()
           ), WC()->cart->get_cart_contents_count());
            ?>
            - <?php echo WC()->cart->get_cart_total(); ?></a>
    </li>
    <?php
}
