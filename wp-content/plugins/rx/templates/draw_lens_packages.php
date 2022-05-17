<?php
/**
 * Created by PhpStorm.
 * User: oksanabekrenyova
 * Date: 12.04.2018
 * Time: 16:47
 */
?>

<div itemscope id="product-<?php the_ID(); ?>" class="lense-package">

    <div class="flex-wrapper row middle--row">
        <div class="right-content-block">
            <!--<div class="product--left-content large-4 columns">-->
            <!-- <?php //the_title('<p itemprop="name" class="product--name-title">', '+ 1 </p>'); ?>-->
            <div class="description-content-lense">
                <div class="logo-box">
                    <?php the_post_thumbnail( 'full', 'Lense Package' ); ?>
                </div>
                <div class="info-box">
                    <p class="product--name-title"><?php the_title(); ?></p>
                    <p class="description">
                        <?php the_excerpt(); ?>
                    </p>
                </div>
            </div>

            <?php the_content(); ?>

            <p class="title-option">
                To learn more about lens technology visit our <a href="/focus/" class="href-blue">technology blog</a>
            </p>
        </div>

        <div class="left-side-bar">
            <p class="price">From <span class="price"><?php woocommerce_template_single_price(); ?></span></p>
            <p class="description">
                Actual prices depends on specific lens
                configuration, available promotions and
                inceptives for specific frames. Please use
                lens configurator after selecting frame
                option to price the lens package.
            </p>
            <div class="for-frame-option">
                <p class="title-option">Select Frame Option</p>
                <ul class="select-options">
                    <li>
                        <p class="title-for-button">I Need New Frame</p>
                        <a href="/all-glasses/" class="hollow button free--button">Shop Frames</a>
                    </li>
                    <li>
                        <p class="title-for-button">I will send In the frame I already have</p>
                        <a href="/product_category/your-frames/" class="hollow button free--button">Use My Frame</a>
                    </li>
                </ul>
                <p class="description">
                    $22 service charge for lens replacement
                    includes shipping. Prepaid shipping label will
                    be supplied after checkout.
            </p>
            </div>
        </div>
    </div>

</div>
