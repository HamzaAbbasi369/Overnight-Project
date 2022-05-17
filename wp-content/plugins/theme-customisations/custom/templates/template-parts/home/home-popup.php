<?php
if ( carbon_get_theme_option( 'popup_for_main_show' ) ):
	?>
	<?php echo do_shortcode( carbon_get_theme_option( 'popup_for_main_text' ) ); ?>
	<?php
endif;
?>
