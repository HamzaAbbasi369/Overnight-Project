<!-- step-5 -->
<div id='step5' style='display: none' class="medium-12 small-12">
    <div id="container_coating" class="">

        <?php

        $style = "height:125px;";
        $tooltip = carbon_get_theme_option('i_popup_for_rx_three_one_three_in_one');
        $html = "Hard, Anti Scratch And UV Protection";
        $submenu = "<p id='mp_hard_price'>" . to_price(0.0, $pfactor) . "</p> ";
        $description = $html;
        $html = '';
        draw_text_selector(
            "coat_hard",
            $tooltip,
            "Three in One",
            "rdo_coating_treatment",
            "rx.coatingOptions.setCoating('Three in One Coating#0')",
            $html,
            $description,
            $submenu,
            $style
        );

        $tooltip = carbon_get_theme_option('i_popup_for_rx_three_one_regular_anti_glare');
        $html = "Recommended for outdoors!";
        $submenu = "<p id='coat_rag_price'>" . to_price($anti_glare_fee, $pfactor) . "</p> ";
        $description = $html;
        $html = '';
        draw_text_selector(
            "coat_rag",
            $tooltip,
            "Regular Anti-Glare",
            "rdo_coating_treatment",
            "rx.coatingOptions.setCoating('Regular Anti-Glare#" . $anti_glare_fee . "')",
            $html,
            $description,
            $submenu,
            $style
        );

        $tooltip = carbon_get_theme_option('i_popup_for_rx_three_one_premium_anti_glare');
        $html = "Best all around!";
        $submenu = "<p id='coat_pag_price'>" . to_price($premium_anti_glare_fee, $pfactor) . "</p> ";
        $description = $html;
        $html = '';
        draw_text_selector(
            "coat_pag",
            $tooltip,
            "Premium Anti-Glare",
            "rdo_coating_treatment",
            "rx.coatingOptions.setCoating('Premium Anti-Glare#" . $premium_anti_glare_fee . "')",
            $html,
            $description,
            $submenu,
            $style
        );

        $tooltip = carbon_get_theme_option('i_popup_for_rx_three_one_computer_anti_glare');
        $submenu = "<p id='coat_c_price'>" . to_price($computer_anti_glare_fee, $pfactor) . "</p> ";
        $description = "Best for Computer work!";
        draw_text_selector(
            "coat_c",
            $tooltip,
            "Computer Anti-Glare",
            "rdo_coating_treatment",
            "rx.coatingOptions.setCoating('Computer Anti-Glare#" . $computer_anti_glare_fee . "')",
            '',
            $description,
            $submenu,
            $style
        );
        ?>

    </div>

    <div>
        <p class="rx-product-delimiter">EXTRAS</p>
    </div>

    <div class="small-12 medium-12">
        <?php

        $tooltip = carbon_get_theme_option('i_popup_for_rx_three_one_easy_clean_lenses');
        $title = "Easy Clean Lenses";
        $html = "<b>" . to_price($easy_clean_fee, $pfactor) . "</b>";
        $action = "rx.coatingOptions.easyClean();";
        $id = "easy_clean";
        draw_checkbox($id, $action, $tooltip, $title, $html, "<br/>");

        ?>
    </div>
</div>