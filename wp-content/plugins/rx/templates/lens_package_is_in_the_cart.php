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
                            <h4 class="step first"><span class="span-step"><i class="fa fa-check" aria-hidden="true"></i></span> Lenses</h4>
                            <p class="description-text"><b>%1$s</b> has already been added to the Cart.</p>
						</div>
                        <div class="box-step-frame">
                            <h4 class="step second"><span class="span-step">2</span> Frame</h4>
                            <p class="description-text">You need add the frame to the cart to proceed with checkout!</p>
                        </div>','woocommerce-rx'), $product->get_title())?>
</div>
