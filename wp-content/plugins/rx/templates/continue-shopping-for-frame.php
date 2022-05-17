<?php
/**
 * rx
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */
?>
<div class="shopping--content-summary-wrap large-3 medium-12 small-12 column" id="continue-shopping-for-frame">
	<div class="shopping--content-summary-block">
		<div class="continue-shopping continue-shopping-message">
			<p class="continue-shopping-text"><?=/** @var string $message */
			$message?></p>
		</div>

        <a href="https://www.overnightglasses.com/all-glasses/?filter%5Bsize%5D%5Bpa_lens-height%5D%5B%5D=28&filter%5Bsize%5D%5Bpa_lens-height%5D%5B%5D=60" class="button btn-black wc-forward">Select Frame</a>
		<?php

		$product_obj     = get_page_by_path( 'your-frames', OBJECT, 'product' );
		$your_frame_link = sprintf( '<a href="%s" class="button btn-white wc-forward">%s</a>', esc_url( get_permalink(
		        $product_obj ) ), esc_html( 'Send Your Frame', 'woocommerce' ) );

		?>

		<div class="continue-shopping continue-shopping-all-frames">
<!--			<p class="select-text"><small>Please select a frame to proceed with-->
<!--                                                               checkout</small></p>-->
			<?=$shop_link?>
		</div>
		<div class="continue-shopping continue-shopping-your-frames">
<!--			<p class="select-text"><small>You can always use Lens Replacement-->
<!--                                                                              service with</small></p>-->
			<?=$your_frame_link?>
		</div>
	</div>
</div>
