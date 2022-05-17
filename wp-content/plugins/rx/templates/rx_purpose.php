<?php
/** @var float $usage_reading */
/** @var float $usage_distance */
/** @var float $usage_bifocal */
/** @var float $usage_progressive */
/** @var float $impact_resistant_fee */
/** @var float $tint_sun_tint */
/** @var float $tint_polarized */
/** @var float $tint_lr_p */
/** @var float $tint_lr_t */
/** @var float $tint_lr_xtr_a */
/** @var float $rush_service_fee */
/** @var float $rush_service_ge150_fee */
/** @var float $easy_clean_fee */
/** @var float $anti_glare_fee */
/** @var float $premium_anti_glare_fee */
/** @var float $computer_anti_glare_fee */
include WcRx::plugin_path() . '/includes/rx_fees.php';
?>

<!-- PURPOSE -->
<div id='step150' style='display: none'>
    <div id="purpose">
        <?php

        echo '<h1 class="rx-product-header-name" id="progress-step-name" style="text-align: center">Main Glasses Or The Only Pair</h1>';

        $id = "rdo_purpose_general";
        $tooltip = "Fully personalized design with a balance between distance and near vision. Highly recommended for experienced and demanding progressive wearers who are looking for an all-purpose, comfortable progressive lens with wider visual fields at all distances.";
        $title = "General daily usage";
        $description = "Recommended as Your only or general use pair for all daily activities.";
        $action = "rx.purpose.set('general')";
        $image = "purpose_general_220px.jpg";
        $style = "background-image: url('/content/plugins/rx/assets/image/" . $image . "');";
        draw_image_selector(
            $id, //id
            $tooltip,
            $title, //title
            "purpose", //name
            $action,
            null,  //html
            $description,
            $image,
            null, //submenu
            null, // size
            null, //checked
            null, //extra
            null, //data
            $style,
            $extraclass
        );

        echo '<h1 class="rx-product-header-name" id="progress-step-name" style="text-align: center">Special Purpose Glasses</h1>';

        $submenu = "";
        $style = "height:125px;";
        $extraclass = "selector_purpose";

        $id = "rdo_purpose_driving";
        $tooltip = "Fully personalized progressive lens specially designed for driving. It has a wide clear area of binocular vision in far distance combined with a wide corridor and soft transitions to offer the best comfort while driving.";
        $title = "For driving";
        $description = "Recommended for maximum comfort when driving.";
        $action = "rx.purpose.set('driving')";
        $image = "purpose_driving_220px.jpg";
        $style = "background-image: url('/content/plugins/rx/assets/image/" . $image . "');";

        draw_image_selector(
            $id, //id
            $tooltip,
            $title, //title
            "purpose", //name
            $action,
            null,  //html
            $description,
            $image,
            null, //submenu
            null, // size
            null, //checked
            null, //extra
            null, //data
            $style,
            $extraclass
        );

        $id = "rdo_purpose_office";
        $tooltip = "Occupational lenses that offer wide intermediate and near visual fields to provide the wearer clear vision at short distances.";
        $title = "Office/Computer";
        $description = "Recommended as a second pair for maximum comfort at your office and any computer work.";
        $action = "rx.purpose.set('office')";
        $image = "purpose_office_220px.jpg";
        $style = "background-image: url('/content/plugins/rx/assets/image/" . $image . "');";
        draw_image_selector(
            $id, //id
            $tooltip,
            $title, //title
            "purpose", //name
            $action,
            null,  //html
            $description,
            $image,
            null, //submenu
            null, // size
            null, //checked
            null, //extra
            null, //data
            $style,
            $extraclass
        );

        $id = "rdo_purpose_sports";
        $tooltip = "Fully personalized progressive lens exclusively for outdoor activities. This design offers a clear area of binocular vision in far distance and it is the ideal lens for dynamic outdoor conditions.";
        $title = "Sports/Activities";
        $description = "Recommended for sport and outdoor activities that require increased distance vision performance.";
        $action = "rx.purpose.set('sports')";
        $image = "purpose_sports_220px.jpg";
        $style = "background-image: url('/content/plugins/rx/assets/image/" . $image . "');";
        draw_image_selector(
            $id, //id
            $tooltip,
            $title, //title
            "purpose", //name
            $action,
            null,  //html
            $description,
            $image,
            null, //submenu
            null, // size
            null, //checked
            null, //extra
            null, //data
            $style,
            $extraclass
        );

        ?>
    </div>
</div>
