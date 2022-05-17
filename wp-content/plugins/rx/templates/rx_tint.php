<!-- step-3 -->
<?php
require WcRx::plugin_path() . '/includes/rx_fees.php';
global $wpdb;

?>
<div id='step3' style='display: none'>

    <div id="container_lens_tint" class="rotw" style="margin-bottom:10px;">

        <?php
		$myframe = WcRx::is_your_frame();
		if ($myframe == 'USEMYFRAMES') {
			$use_frameSQL = " AND your_frame = 1 ";
		} else {
			$use_frameSQL = " AND your_frame = 0 ";
		}


            $sql_add = "SELECT package_id, package_name, package_description, package_option_name, material,
            b.anti_glare coating,b.easy_clean, tint, tint_options, price, sale_price, b.uv_protection, b.scratch_protection, recommend, a.package_option_id "
                    ." FROM rx_packages a, rx_package_options b
            WHERE a.package_option_id=b.package_option_id AND a.rx_type='none-rx' AND a.rx_progressive_type='none-rx' $use_frameSQL";


        //$sql_add = "SELECT * FROM rx_packages WHERE rx_progressive_type='none-rx'";

        $result_add = $wpdb->get_results($sql_add, ARRAY_A);

        foreach ($result_add as $key => $res) {
            if (isset($tab_key) && $res['tabs'] !== $tab_key) {
                continue;
            }
            $style = "height:125px;";
            $package_price = $res['price'];
            $sale_price = (!empty($res['sale_price']) ? $res['sale_price']: null);
            $l2 = "#div_rx_ps" . $res['package_id'] . "_l2";
            $submenu = "<p id='" . "rx_ps" . $res['package_id'] . "_price'>+ " .
                (!empty($sale_price)
                    ? sprintf('<span class="old-price">%1$s</span> <span class="new-price">%2$s</span>', to_price($package_price, $pfactor), to_price($sale_price, $pfactor))
                    : to_price($package_price, $pfactor)) .
                "</p> ";
            $html = "";
            draw_text_selector(
                "rx_ps" . $res['package_id'],
                $res['package_description'],
                $res['package_name'],
                "material",
                null,
                $html,
                $res['tint'],
                $submenu,
                $style,
                null,
                print_color_select('', '', '', $res['tint']),
                [
                    'l2id' => $l2,
                    'rxtype' => 'none-rx',
                    'material' => $res['material'],
                    'price' =>  $package_price,
                    'sale_price' => $sale_price ,
                    'tint' => $res['tint'],
                    'coating' => $res['coating'] . ' Anti-Glare',
                    'easy_clean' => $res['easy_clean'],
                    'uv_protection' => $res['uv_protection'],
                    'scratch_protection' => $res['scratch_protection']
                ]
            );
        }

?>
<br>
<h3 class="bigtitle">EXTRA</h3>
        <?php
        $impact_resistant_fee = 24;
        $tooltip = carbon_get_theme_option('i_popup_for_rx_lens_type_impact_resistant');
        $title = "Impact Resistant";
        $html = "<b>" . to_price($impact_resistant_fee, $pfactor) . "</b>";
        $action = "rx.impactResistant.change();";
        $id = "impact_resistant";
        $description = 'For Children under 18, sport and active users';
        draw_checkbox($id, $action, $tooltip, $title, $html, $description);
/*
        $description = '&nbsp';
        $tooltip = 'I need my lenses to be impact resistant: Impact resistant lenses are lenses made from specific materials that are strong enough to sustain a direct impact of the Drop Ball test. Overnight Glasses recommends its active customer to check this option if he requires a lens that is stronger and not likely to break on impact. If you require SAFETY eyewear, please review our safety frames section.';
        $submenu = "<b>+&nbsp $24.00</b>";
        draw_image_selector(
            "rdo_t_clear",
            $tooltip,
            $title = "Impact Resistant",
            "rdo_tint",
            "rx.lensColor.changeValue('Clear Lens')",
            null,
            $description,
            "Clear_Lens.jpg",
            $submenu,
            null,
            null,
            print_color_select('', '', '', $title, false)
        );
*/


    /*
    $description = '&nbsp';
    $tooltip = carbon_get_theme_option('i_popup_for_rx_lens_tint_opt_lear_lenses');
    $submenu = "<b>+&nbsp $0.00</b>";
    draw_image_selector(
        "rdo_t_clear",
        $tooltip,
        $title = "Clear Lenses",
        "rdo_tint",
        "rx.lensColor.changeValue('Clear Lens')",
        null,
        $description,
        "Clear_Lens.jpg",
        $submenu,
        null,
        null,
        print_color_select('', '', '', $title, false)
    );

    $tooltip = carbon_get_theme_option('i_popup_for_rx_lens_tint_opt_tinted_lenses');
    $submenu = "<b>+&nbsp " . to_price($tint_sun_tint[$opt_ind], $pfactor) . "</b>";
    draw_image_selector(
        "rdo_t_tinted",
        $tooltip,
        $title = "Tinted Lenses",
        "rdo_tint",
        "rx.lensColor.changeValue('Sun Lens Tint')",
        null,
        $description,
        "Tinted_Grey.jpg",
        $submenu,
        null,
        null,
        print_color_select('', '', '', $title, false)
    );

    $tooltip = carbon_get_theme_option('i_popup_for_rx_lens_tint_opt_light_responsive');
    $submenu = "<b>+&nbsp \$Extra Fee</b>";
    draw_image_selector(
        "rdo_t_lres",
        $tooltip,
        $title = "Light Responsive",
        "rdo_tint",
        "rx.lensColor.changeValue('Light Responsive')",
        null,
        $description,
        "Transitions.jpg",
        $submenu,
        null,
        null,
        print_color_select('', '', '', $title, false)
    );

    $tooltip = carbon_get_theme_option('i_popup_for_rx_lens_tint_opt_polarized');
    $submenu = "<b>+&nbsp " . to_price($tint_polarized[$opt_ind], $pfactor) . "</b>";
    draw_image_selector(
        "rdo_t_polarized",
        $tooltip,
        $title = "Polarized",
        "rdo_tint",
        "rx.lensColor.changeValue('Polarized')",
        null,
        $description,
        "Polarized_Brown_hiking.jpg",
        $submenu,
        null,
        null,
        print_color_select('', '', '', $title, false)
    );
        */
        ?>
    </div>

    <div id='container_lens_tint_color' class='rorw' style='display:none'>
    </div>


    <script>
/*
        var __ret = this.getVal();
        var rxtype = __ret.rxtype;
        var lens = __ret.lens;
        var od_sphere = __ret.od_sphere;
        var od_cylinder = __ret.od_cylinder;
        var os_sphere = __ret.os_sphere;
        var os_cylinder = __ret.os_cylinder;
        var impact = __ret.impact;
        var trackingdis = __ret.trackingdis;
        var premium = __ret.premium;
        var pricetoremove = __ret.pricetoremove;
        var rx_progressive_type = 'non-rx';

        var urlAction = 'rx_get_preset_packages';
        window.loader.start();
        jQuery.ajax({
            url: rx_params.url,
            type: 'GET',
            dataType: 'text',
            data: {
                action: urlAction,
                rxtype: rxtype,
                lens: lens,
                od_sphere: od_sphere,
                od_cylinder: od_cylinder,
                os_sphere: os_sphere,
                os_cylinder: os_cylinder,
                impact_resistant: impact,
                tracking: trackingdis,
                material: material,
                premium: premium,
                pricetoremove: pricetoremove,
                rx_progressive_type: rx_progressive_type,
                strength: __ret.strength
            },
            success: function (data) {
                jQuery(targetDiv).html(data);
                jQuery(targetDiv).foundation();
            },
            complete: function (data) {
                if (typeof cb === "function") {
                    cb();
                }
                window.loader.stop();
            }
        })
*/
    </script>
</div>
