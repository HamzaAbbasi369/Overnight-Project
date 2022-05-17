<?php
/**
 * theme-customisations
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

global $product;
if( $product->get_type()== 'simple' ){

    return;

}

$size = (string)$product->get_variation_default_attribute('pa_size');
if ($size) {
    $sizes = explode("-", $size);
} else {
    $sizes = [];
}
?>
<div class="single--page-size-block">
    <div class="perfect-fit<?= (!empty($sizes)? ' has-size' : '' ) ?>"> <!-- <-- class "has-size" show new elements in size guide -->
        <div class="head-perfect-fit">
            <h3 class="perfect-fit-title">perfect fit</h3>
            <button class="single--page-size-guide" data-open="sizeGuide">SIZE GUIDE</button>
        </div>

        <!--<div class="container-sizes">
            <ul>
                <li>This Frame Size</li>
                <li>
                    <a href='#'
                       data-tooltip
                       aria-haspopup="true"
                       class="has-tip top this-frame-size"
                       data-disable-hover="false"
                       tabindex="2">
                        <?php //echo  sprintf('<span class="lensWidth">%1$s</span> - <span class="bridgeWidth">%2$s</span> - <span class="templeLength">%3$s</span>',
                            //($sizes) ? $sizes[0] : '',
                            //($sizes) ? $sizes[1] : '',
                            //($sizes) ? $sizes[2] : ''); ?>
                    </a>
                </li>
            </ul>

            <ul class="user-frame-size-is">
                <li>My Frame Size is</li>
                <li class="my-size-li"><span class="lensWidth"></span> - <span class="bridgeWidth"></span> - <span class="templeLength"></span></li>
            </ul>

            <button class="btn-edit-my-size third-open">Change my size</button>
        </div> -->

        <?php
            if (empty($sizes)) {
                //size defaults
                $sizes[0] = 49;
                $sizes[1] = 19;
                $sizes[2] = 140;
            }
        ?>
        <div class="reveal" id="sizeGuide" data-reveal>
            <button class="close-button" data-close="" aria-label="Close modal" type="button">
                <span aria-hidden="true">×</span>
            </button>
            <svg width="100%" height="674px" viewBox="0 0 600 674" version="1.1">
                <!-- Generator: Sketch 43.2 (39069) - http://www.bohemiancoding.com/sketch -->
                <title>Overnight_Glasses_Popup_SizeGuide  </title>
                <desc>Created with Sketch.</desc>
                <defs>
                    <rect id="path-1" x="0" y="0" width="600" height="674"></rect>
                </defs>
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Overnight_Glasses_SizeGuide_var-2" transform="translate(-660.000000, -117.000000)">
                        <g id="Overnight_Glasses_Popup_SizeGuide--" transform="translate(660.000000, 117.000000)">
                            <g id="Cell">
                                <mask id="mask-2" fill="white">
                                    <use xlink:href="#path-1"></use>
                                </mask>
                                <use fill="#FFFFFF" xlink:href="#path-1"></use>
                                <rect fill="#333333" opacity="0.05" mask="url(#mask-2)" x="0" y="0" width="600" height="176"></rect>
                            </g>
                            <g id="Group-3" transform="translate(106.000000, 88.000000)">
                                <g id="Arrows" transform="translate(0.000000, 131.000000)">
                                    <rect id="Rectangle-2" fill="#9A9A9A" x="0" y="20" width="1" height="31"></rect>
                                    <rect id="Rectangle-2" fill="#9A9A9A" x="8" y="249" width="1" height="31"></rect>
                                    <rect id="Rectangle-2" fill="#9A9A9A" x="307" y="249" width="1" height="37"></rect>
                                    <rect id="Rectangle-2" fill="#9A9A9A" x="158" y="20" width="1" height="42"></rect>
                                    <rect id="Rectangle-2" fill="#9A9A9A" x="170" y="20" width="1" height="42"></rect>
                                    <rect id="Rectangle-2" fill="#9A9A9A" x="216" y="20" width="1" height="42"></rect>
                                    <rect id="Rectangle-2" fill="#9A9A9A" transform="translate(384.500000, 35.500000) scale(-1, 1) translate(-384.500000, -35.500000) " x="384" y="20" width="1" height="31"></rect>
                                    <rect id="Rectangle-2" fill="#9A9A9A" transform="translate(226.500000, 41.000000) scale(-1, 1) translate(-226.500000, -41.000000) " x="226" y="20" width="1" height="42"></rect>
                                    <path d="M305.970893,94.0097522 L305.970893,-47.0345406 L308.034541,-47.0345406 L305.526183,-55.0345406 L303.034541,-47.0345406 L305.057088,-47.0345406 L305.057088,94.0096537 L302.96512,94.0094281 L305.588887,101.963902 L307.965891,94.0099673 L305.970893,94.0097522 Z" id="Combined-Shape" fill="#3099CF" transform="translate(305.499830, 23.464681) scale(-1, 1) rotate(90.000000) translate(-305.499830, -23.464681) "></path>
                                    <path d="M79.9225582,94.0443579 L79.9526722,-47 L82,-47 L79.5,-55 L77,-47 L79.0223013,-47 L79.0223013,94.0441422 L76.9305765,94.0436411 L79.5359486,101.998763 L81.9313591,94.0448391 L79.9225582,94.0443579 Z" id="Combined-Shape" fill="#3099CF" transform="translate(79.500000, 23.499391) rotate(90.000000) translate(-79.500000, -23.499391) "></path>
                                    <path d="M193.955534,38.049238 L193.955534,9 L196,9 L193.534221,1 L191,9 L193.046998,9 L193.046998,38.0490205 L190.930608,38.0485137 L193.535932,45.9999819 L195.931391,38.0497111 L193.955534,38.049238 Z" id="Combined-Shape" fill="#FB5E5E" transform="translate(193.500000, 23.500000) rotate(90.000000) translate(-193.500000, -23.500000) "></path>
                                    <text id="Lenses-size" opacity="0.800000012" font-family="ArialMT, Arial" font-size="12" font-weight="normal" fill="#333333">
                                        <tspan x="40" y="11">Lens Width</tspan>
                                    </text>
                                    <path d="M158.597524,395.546237 L158.597524,113.5 L160.5,113.5 L158,105.5 L155.5,113.5 L157.672861,113.5 L157.672861,395.546016 L155.430582,395.545479 L158.035946,403.499982 L160.431364,395.546677 L158.597524,395.546237 Z" id="Combined-Shape" fill="#4EC558" transform="translate(158.000000, 254.500000) rotate(90.000000) translate(-158.000000, -254.500000) "></path>
                                    <text id="Lenses-size" opacity="0.800000012" font-family="ArialMT, Arial" font-size="12" font-weight="normal" fill="#333333">
                                        <tspan x="266" y="11">Lens Width</tspan>
                                    </text>
                                    <text id="BRIDGE" opacity="0.800000012" font-family="ArialMT, Arial" font-size="12" font-weight="normal" fill="#333333">
                                        <tspan x="170" y="11">BRIDGE</tspan>
                                    </text>
                                    <text id="Temple-size" opacity="0.800000012" font-family="ArialMT, Arial" font-size="12" font-weight="normal" fill="#333333">
                                        <tspan x="119" y="243">Temple size</tspan>
                                    </text>
                                </g>
                                <g id="Sizes" transform="translate(47.000000, 0.000000)">
                                    <rect id="Rectangle-3" fill="#3099CF" x="27" y="21" width="30" height="2"></rect>
                                    <text id="49--" opacity="0.800000012" font-family="ArialMT, Arial" font-size="32" font-weight="normal" fill="#333333">
                                        <tspan x="22" y="58"><?=$sizes[0]?>    -</tspan>
                                    </text>
                                    <rect id="Rectangle-3" fill="#FB5E5E" x="132" y="20" width="30" height="2"></rect>
                                    <text id="22--" opacity="0.800000012" font-family="ArialMT, Arial" font-size="32" font-weight="normal" fill="#333333">
                                        <tspan x="129" y="58"><?=$sizes[1]?>   -</tspan>
                                    </text>
                                    <rect id="Rectangle-3" fill="#4EC558" x="237" y="20" width="30" height="2"></rect>
                                    <text id="145" opacity="0.800000012" font-family="ArialMT, Arial" font-size="32" font-weight="normal" fill="#333333">
                                        <tspan x="225" y="58"><?=$sizes[2]?></tspan>
                                    </text>
                                    <text id="mm" opacity="0.800000012" font-family="ArialMT, Arial" font-size="18" font-weight="normal" fill="#333333">
                                        <tspan x="299" y="59">mm</tspan>
                                    </text>
                                    <text id="Lenses-size" opacity="0.800000012" font-family="ArialMT, Arial" font-size="12" font-weight="normal" fill="#333333">
                                        <tspan x="0" y="11">Lens Width</tspan>
                                    </text>
                                    <text id="BRIDGE-Size" opacity="0.800000012" font-family="ArialMT, Arial" font-size="12" font-weight="normal" fill="#333333">
                                        <tspan x="109" y="11">BRIDGE Size</tspan>
                                    </text>
                                    <text id="TEMPLE-Size" opacity="0.800000012" font-family="ArialMT, Arial" font-size="12" font-weight="normal" fill="#333333">
                                        <tspan x="212" y="11">TEMPLE Size</tspan>
                                    </text>
                                </g>
                            </g>
                            <g id="Frames" transform="translate(56.000000, 270.000000)" stroke="#9A9A9A">
                                <g id="Frame-front" transform="translate(14.000000, 0.000000)">
                                    <path d="M419.855828,82.5479862 C416.751534,97.293385 410.446712,121.76609 397.478521,129.50044 C382.733123,138.296459 353.501534,143.081729 329.055215,139.977434 C304.608896,136.87314 288.31135,127.560256 278.610429,109.322526 C270.758117,94.563158 259.208589,51.5050414 266.969325,36.7596426 C274.730061,22.0142439 315.861963,16.9697653 334.875767,15.8056549 C360.076429,14.2628205 398.125767,15.0295813 413.259202,25.5065751 C428.392638,35.983569 422.960123,67.8025874 419.855828,82.5479862 Z M180.825153,109.322526 C171.124233,127.560256 154.826687,136.87314 130.380368,139.977434 C105.934049,143.081729 76.7024601,138.296459 61.9570613,129.50044 C48.9904233,121.76609 42.6840491,97.293385 39.5797546,82.5479862 C36.4754601,67.8025874 31.0429448,35.983569 46.1763804,25.5065751 C61.309816,15.0295813 99.3591534,14.2628205 124.559816,15.8056549 C143.57362,16.9697653 184.705521,22.0142439 192.466258,36.7596426 C200.226994,51.5050414 188.677466,94.563158 180.825153,109.322526 Z M229.717791,17.3729576 C216.136503,17.3729576 200.177325,16.7757469 186.645706,12.3133236 C150.170245,0.284182507 58.2055215,-7.47655369 0,11.1492132 L1.74616564,43.3562684 C1.74616564,43.3562684 12.3209448,41.0280475 15.8117239,52.6691518 C19.3040552,64.3102561 27.4155767,112.302649 42.2478957,131.052587 C60.6641227,154.334796 100.326141,155.228833 127.145693,154.07714 C163.257951,152.524992 186.903362,136.485103 196.992319,109.322526 C207.081276,82.1599494 212.774552,58.8777408 219.234589,51.5050414 C222.720712,47.526888 226.650748,47.2443972 229.717791,47.2381886 C232.784834,47.2443972 236.714871,47.526888 240.200994,51.5050414 C246.661031,58.8777408 252.354307,82.1599494 262.443264,109.322526 C272.532221,136.485103 296.177632,152.524992 332.28989,154.07714 C359.109442,155.228833 398.77146,154.334796 417.187687,131.052587 C432.020006,112.302649 440.131528,64.3102561 443.623859,52.6691518 C447.11619,41.0280475 457.689417,43.3562684 457.689417,43.3562684 L459.435583,11.1492132 C401.230061,-7.47655369 309.265337,0.284182507 272.789877,12.3133236 C259.258258,16.7757469 243.29908,17.3729576 229.717791,17.3729576 Z" id="Fill-92"></path>
                                    <path d="M206.435583,18.0378222 C209.438079,38.2894168 215.258611,59.4561083 215.258611,59.4561083 C215.258611,59.4561083 229.680623,36.1758116 244.778076,59.3969578 C249.693287,37.9154449 253,18.0378222 253,18.0378222 C253,18.0378222 251.243469,13.9465749 246.760822,13.9784252 C242.278175,14.0102755 212.001242,13.9693252 212.001242,13.9693252 C212.001242,13.9693252 208.491908,13.9784252 206.435583,18.0378222 Z" id="Path-2" fill="#FFFFFF"></path>
                                </g>
                                <g id="Frame-side" transform="translate(0.000000, 220.000000)">
                                    <g id="Group-4">
                                        <path d="M58.325525,12.1907781 L245.299245,11.8284605 L357.179738,17.5773666 C357.179738,17.5773666 389.000596,23.7136136 415.525559,43.5971609 C442.050522,63.4807081 469.887488,80.1249489 469.887488,80.1249489 C469.887488,80.1249489 493.565788,92.8044512 486.42878,98.9478807 C479.291772,105.09131 461.1009,101.646106 461.1009,101.646106 C461.1009,101.646106 397.135971,46.2355316 369.976554,34.9326747 C342.817137,23.6298178 306.789729,29.7780357 306.789729,29.7780357 L250.873986,35.3468654 L58.325525,35.8460926 L58.325525,12.1907781 Z" id="Path-3"></path>
                                        <path d="M0.925896944,17.3766658 C0.925896944,17.3766658 1.37418904,5.07582481 3.37754025,3.56045056 C4.57564497,2.6541806 8.22402966,2.43289557 11.5135837,2.93432277 C13.7244938,3.27133212 15.7733133,3.93480414 16.8071955,4.93619285 C19.3793632,7.42752103 39.5525073,12.0824097 43.5062966,12.2808341 C47.4600858,12.4792585 60.1239699,12.6923809 60.1239699,12.6923809 C60.1239699,12.6923809 26.6372856,0.850120676 23.5448051,0.56791713 C20.4523246,0.285713584 8.80177027,2.3529573 8.80177027,2.3529573" id="Path-4"></path>
                                        <path d="M41.3721129,15.6246342 L43.1006458,31.8048233" id="Path-5"></path>
                                        <path d="M0.73078132,20.3444896 C1.96316132,12.5076731 17.3935192,3.81699342 23.1160837,44.6551857 C28.8386482,85.4933779 34.5804187,143.062329 23.3209469,142.427733 C12.0614751,141.793138 8.76605895,131.572386 0.73078132,20.3444896 Z" id="Path-6"></path>
                                        <path d="M60.2282896,35.520784 L42.6633935,34.7043772 C42.6633935,34.7043772 32.8485105,39.8593511 32.454259,50.2755127 C32.0600075,60.6916742 33.6568162,137.568023 33.6568162,137.568023 C33.6568162,137.568023 31.1430191,153.25595 26.0462543,154.035319 C23.4006433,154.439872 18.1740543,150.076051 14.9945808,137.568023" id="Path-7"></path>
                                        <path d="M59.1328464,35.7845184 C59.1328464,35.7845184 48.6887811,41.3877509 47.8876764,51.5194753 C47.0865717,61.6511997 47.5744355,135.053995 47.5744355,135.053995 C47.5744355,135.053995 50.2963913,153.901558 27.4523052,153.901558" id="Path-8"></path>
                                        <path d="M5.58215601,81.5098321 C5.58215601,81.5098321 14.7103036,142.006431 25.2987037,140.075702" id="Path-9"></path>
                                    </g>
                                </g>
                            </g>
                            <text id="SIZE-GUIDE" opacity="0.800000012" font-family="Arial-BoldMT, Arial" font-size="18" font-weight="bold" fill="#333333">
                                <tspan x="246" y="55"> SIZE GUIDE</tspan>
                            </text>
                        </g>
                    </g>
                </g>
            </svg>
        </div>
    </div>
</div>
