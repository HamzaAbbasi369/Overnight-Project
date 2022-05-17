<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$d_mode = (isset($_REQUEST['d_mode'])) ? $_REQUEST['d_mode'] : '';
$product_id = (isset($_REQUEST['product_id'])) ? $_REQUEST['product_id'] : '';

$opt_ind = ($d_mode=='fashion') ? 1 : 0;
require(dirname(__FILE__) . '/rx_functions.php');
?>
<script type="text/javascript">
rx_params = rx_params || {};
rx_params['d_mode'] = '<?=$d_mode?>';
rx_params['opt_ind'] = <?=$opt_ind?>;
</script>
<div id='mypd'></div>
<?php

$product_cats = wp_get_post_terms($product_id, 'product_cat', ["fields" => "ids"]);
$category = get_term_by( 'slug', GROUPON_NEW_CATEGORY, 'product_cat' );
$id_cat_groupon_value = $category->term_id;

if (in_array($id_cat_groupon_value, $product_cats)) {
    $offer = 'groupon_value';
    ?>
    <script type="text/javascript" src="<?= WcRx::get_assets_url() ?>js/rx_jscript_groupon.js"></script>
    <?php
}

?>



<div class="container rx-form-container row middle--row">

    <div class="reveal" id="alertError" data-reveal>
         <button class="close-button" data-close="" aria-label="Close modal" type="button">
            <span aria-hidden="true">×</span>
         </button>
         <p class='popup-rx-title'>Attention</p>
         <hr class='gold-line'>
         <div class='popup-rx-text'></div>
    </div>

<!--    <div class="reveal" id="materialModalRx" data-reveal>-->
<!--        <h1>Select material</h1>-->
<!--        <fieldset class="large-6 columns" style="width: 100%;">-->
<!--            <legend>Choose Your Favorite</legend>-->
<!--            <input type="radio" name="material" value="1.50 Lenses Package" id="lenses_package" checked="checked">-->
<!--            <label for="lenses_package">1.50 Lenses Package</label>-->
<!--            <input type="radio" name="material" value="Polycarbonate Lenses Package" id="polycarbonate_lenses_package">-->
<!--            <label for="polycarbonate_lenses_package">Polycarbonate Lenses Package</label>-->
<!--            <input type="radio" name="material" value="Trivex HD Lenses Package" id="trivex_lenses_package">-->
<!--            <label for="trivex_lenses_package">Trivex HD Lenses Package</label>-->
<!--        </fieldset>-->
<!--        <div>-->
<!--           /*<button class="button button_true" data-close onclick="rx.step.set(2)">Ok</button>*/-->
<!--            <button class="button button_true" data-close>Ok</button>-->
<!--        </div>-->
<!--        <button class="close-button" data-close aria-label="Close reveal" type="button">-->
<!--            <span aria-hidden="true">&times;</span>-->
<!--        </button>-->
<!--    </div>-->

<!--    --><?php //echo do_shortcode(carbon_get_theme_option('text_popup_for_rx_material')); ?>

    <div class="reveal" id="materialModalRx" data-reveal style="text-align: center; padding-top: 2rem; padding-bottom: 2rem;">
        <p class="rx-popup-material-normal rx-popup-material_text">Your prescription is a good fit for best lenses on the market<br><br><span class="rx-popup-material">Upgrade to Trivex HD</span> </p>
        <p class="rx-popup-material">Light, Shatter Proof, Premium Clarity</p>

        <div class="clearfix large-12 medium-12 small-12 clear-padding margin-top">
            <div class="medium-5 small-5 large-5 columns clear-padding">
                <button class="btn btn-warning footer first_button button_true popup-no-btn" data-close data-material=false>No, Thank You</button>
            </div>
            <div class="large-2 medium-2 small-2 columns clear-padding">&nbsp;</div>
            <div class="medium-5 small-5 large-5 columns clear-padding">
                <button class="btn btn-warning footer last button_true popup-yes-btn" data-close data-material="Trivex HD Lenses Package">Yes, Please</button>
            </div>
        </div>
        <button class="close-button" data-close aria-label="Close reveal" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>


    <!-- top block for step-7 (PACKAGES)-->
    <?php
	$visibility = "display: none;";
	$hdr_text = 'LENS PACKAGES SELECTED<br>FOR YOUR PRESCRIPTION VALUES.';
	if ($d_mode == "fashion") {
		$visibility = "";
		$hdr_text = 'NONE PRESCRIPTION LENSES';
	}
    ?>
    <div class="row rx-add-lens-package-top" style="<?= $visibility ?> padding-right: 10px; padding-left: 10px">
        <div class="large-2 medium-7 small-12 columns columns">&nbsp;</div>
        <div class="large-10 medium-7 small-12 columns rx-packages clear-padding">
            <p class="rx-product-delimiter"><?= $hdr_text ?></p>
        </div>

        <div class="large-1 medium-1 small-1 columns">&nbsp; </div>
    </div>

    <div class="row">
        <div class="large-2 medium-7 small-12 columns">&nbsp; </div>
        <div class="large-10 medium-10 small-12 columns">

            <?php
            if ($d_mode != "fashion") {
            ?>
            <div class="steps" id="rx_stepnavigator">
                <ul class="steps-container">
                    <li style="width:25%;" class="activated" id="rx_stepnavigator_1">
                        <div class="step">
                            <div class="step-image"><span></span></div>
                            <div class="step-current"></div>
                            <div class="step-description">Type</div>
                        </div>
                    </li>
                    <li style="width:25%;"  id="rx_stepnavigator_2">
                        <div class="step">
                            <div class="step-image"><span></span></div>
                            <div class="step-current"></div>
                            <div class="step-description">Prescription</div>
                        </div>
                    </li>
                    <li style="width:25%;"  id="rx_stepnavigator_3">
                        <div class="step">
                            <div class="step-image"><span></span></div>
                            <div class="step-current"></div>
                            <div class="step-description">Package</div>
                        </div>
                    </li>
                    <li style="width:25%;"  id="rx_stepnavigator_4">
                        <div class="step">
                            <div class="step-image"><span></span></div>
                            <div class="step-current"></div>
                            <div class="step-description">Review</div>
                        </div>
                    </li>
                </ul>
                <div class="step-bar" style="width: 25%;"  id="rx_stepnavigator_bar"></div>
            </div>
            <?php
            }
            ?>

        </div>
    </div>

    <div class="row">
        <div class="large-2 medium-7 small-12 columns">&nbsp; </div>
        <div class="large-5 medium-7 small-12 columns">
            <?php

            if ($offer != 'groupon_value') {
                require(dirname(__FILE__) . '/rx_progress.php');

                if ($d_mode != 'fashion') {
                    require(dirname(__FILE__) . '/rx_usage.php');
                    require(dirname(__FILE__) . '/rx_purpose.php');  /* load purpose template */
                    require(dirname(__FILE__) . '/rx_distance.php');  /* load distance template */
                    require(dirname(__FILE__) . '/rx_core.php');
                    require(dirname(__FILE__) . '/rx_premium.php');  /* load type template */
                }
                require(dirname(__FILE__) . '/rx_tint.php');
                if ($d_mode != 'fashion') {
                    require(dirname(__FILE__) . '/rx_material.php');
                    require(dirname(__FILE__) . '/rx_coating.php');
                }
                require(dirname(__FILE__) . '/rx_review.php');
            } else {
                // hardcoding for groupon pilot
                ?>
                <h1 id="progress-step-name"
                    style="text-align: center; font-size: 15px; margin-bottom: 20px; margin-top: 15px;">PLEASE ENTER
                    PRESCRIPTION</h1>
                <?php
                require(dirname(__FILE__) . '/rx_core.php');
            }

            ?>
            <?php
            if ($offer != 'groupon_value') {
                draw_navigation_control($d_mode);
            } else {
                draw_navbar("nb_step3", "Frame", "rx.navigation.hideRx()", "Add To Cart", "ToCart()");
            }
            ?>
        </div>

        <div class="large-1 hide-for-medium-only hide-for-small-only columns">&nbsp;</div>

        <div id="rx_right" class="large-4 medium-5 small-12 columns">
<!--            <div class="button-hide-container">-->
<!--                <button class="hide-packages">View standard features</button>-->
<!--            </div>-->
            <?php
            require(dirname(__FILE__) . '/rx_package_data.php');
            do_action('after_package_data_list');
            ?>

        </div>

        <!-- left sidebar for step-7 (PACKAGES)-->
        <div id="rx_right_packages" class="large-4 medium-5 small-12 columns clear-padding" style="display: none">

            <div class="button-show-container row">
                <button class="view-packages">Hide package features</button>
            </div>

            <div class="row rx-packages">
                <p class="rx-product-delimiter">All Lens Packages Include:</p>
                <div class="small-3 columns images-packages">
                    <img src="<?= WcRx::get_assets_url() ?>/image/Premium-Anti-Glare-glasses.png" alt="">
                </div>
                <div class="small-9 columns rx-text-package">
                    <p class="rx-description-packages">Premium Anti-Glare Coating</p>
                    <p class="rx-product-description">Reduces all glare from external lights sources such as office and street lighting.</p>
                </div>

                <div class="small-3 columns images-packages">
                    <img src="<?= WcRx::get_assets_url() ?>/image/UV-glasses.png" alt="">
                </div>
                <div class="small-9 columns rx-text-package">
                    <p class="rx-description-packages">UV Shield</p>
                    <p class="rx-product-description">Protects your eyes by reducing UV radiation</p>
                </div>

                <div class="small-3 columns images-packages">
                    <img src="<?= WcRx::get_assets_url() ?>/image/Anti-Scratch-glasses.png" alt="">
                </div>
                <div class="small-9 columns rx-text-package">
                    <p class="rx-description-packages">Scratch Shield</p>
                    <p class="rx-product-description">Adds protection from scratches.</p>
                </div>

                <div class="small-3 columns images-packages">
                    <img src="<?= WcRx::get_assets_url() ?>/image/Easy-Clean-glasses.png" alt="">
                </div>
                <div class="small-9 columns rx-text-package">
                    <p class="rx-description-packages">Easy-Clean Coating</p>
                    <p class="rx-product-description">Makes your lens slippery and easy to clean.</p>
                </div>
            </div>

            <div class="row">
                <div class="small-3 columns images-packages">
                    <img src="<?= WcRx::get_assets_url() ?>/image/Polarized-glasses.png" alt="">
                </div>
                <div class="small-9 columns rx-text-package">
                    <p class="rx-description-packages">Polarized Lenses</p>
                    <p class="rx-product-description">Provide the ultimate sun protection and reduce glare for crisp vision.</p>
                </div>

                <div class="small-3 columns images-packages">
                    <img src="<?= WcRx::get_assets_url() ?>/image/Blue-Light-glasses.png" alt="">
                </div>
                <div class="small-9 columns rx-text-package">
                    <p class="rx-description-packages">Blue Armor ™ Lenses</p>
                    <p class="rx-product-description">protect your eyes from the harmful blue light emitted by digital screens.</p>
                </div>

                <div class="small-3 columns images-packages">
                    <img src="<?= WcRx::get_assets_url() ?>/image/Outdoors-indoors-glasses.png" alt="">
                </div>
                <div class="small-9 columns rx-text-package">
                    <p class="rx-description-packages">Transitions</p>
                    <p class="rx-product-description">Light responsive lenses. Clear / Dark based on UV intensity</p>
                </div>
            </div>
        </div>

        <div class="large-1 medium-1 small-1 columns">&nbsp; </div>


    </div>

         <div class='modalRXpackages' id='modalRXpackages' data-reveal>
                <a class = 'close' >x</a >

                 <!-- left sidebar for step-7 (PACKAGES)-->
                <div id="rx_right_packages" class="">
                    <div class="row rx-packages">
                            <p class="rx-product-delimiter">All Lens Packages Include:</p>
                            <div class="small-3 columns images-packages">
                                <img src="<?= WcRx::get_assets_url() ?>/image/Premium-Anti-Glare-glasses.svg" alt="Premium-Anti-Glare-glasses" class="packages-icon">
                            </div>
                            <div class="small-9 columns rx-text-package">
                                <p class="rx-description-packages">Premium Anti-Glare Coating</p>
                                <p class="rx-product-description">Reduces all glare from external lights sources such as office and street lighting.</p>
                            </div>

                            <div class="small-3 columns images-packages">
                                <img src="<?= WcRx::get_assets_url() ?>/image/UV-glasses.svg" alt="UV-glasses" class="packages-icon">
                            </div>
                            <div class="small-9 columns rx-text-package">
                                <p class="rx-description-packages">UV Shield</p>
                                <p class="rx-product-description">Protects your eyes by reducing UV radiation</p>
                            </div>

                            <div class="small-3 columns images-packages">
                                <img src="<?= WcRx::get_assets_url() ?>/image/Anti-Scratch-glasses.svg" alt="Anti-Scratch-glasses" class="packages-icon">
                            </div>
                            <div class="small-9 columns rx-text-package">
                                <p class="rx-description-packages">Scratch Shield</p>
                                <p class="rx-product-description">Adds protection from scratches.</p>
                            </div>

                            <div class="small-3 columns images-packages">
                                <img src="<?= WcRx::get_assets_url() ?>/image/Easy-Clean-glasses.svg" alt="Easy-Clean-glasses" class="packages-icon">
                            </div>
                            <div class="small-9 columns rx-text-package">
                                <p class="rx-description-packages">Easy-Clean Coating</p>
                                <p class="rx-product-description">Makes your lens slippery and easy to clean.</p>
                            </div>
                    </div>
                </div>
         </div>
</div>
<script type="text/javascript">
    jQuery(document).trigger('rx-loaded');
</script>

