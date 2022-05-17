<?php
/**
 * rx
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */
/** @var WC_Product_Simple $product */
?>
<div class="description-lens-package-is-in-the-cart">
		<?=sprintf(__('<div class="box-step-lense">
                            <h4 class="step first"><span class="span-step"><i class="fa fa-check" aria-hidden="true"></i></span> Change Frame</h4>
                            <p class="description-text">Click “Add To Cart” to replace <b>%1$s</b> in your shopping cart with this frame. </p>
						</div>
                        ','woocommerce-rx'), $product->get_title())?>
</div>
