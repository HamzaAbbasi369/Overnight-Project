<?php

$tooltips = [
    "SELECT USAGE" => "<b>Single Vision:</b> A single vision lens provides vision correction for one viewing area. The correction area can be for distance, intermediate such as computer work, or only for reading in the near area.<br/></br>Overnight Glassess Recommends Enhanced Accuracy Freedom HD Lenses for all Single Vision prescriptions.<br/><b>Bifocal:</b> Bifocal lenses are designed with two different viewing areas, one for distance (same as single vision), and one for reading. The reading area is designed as a D shaped magnification segment on the bottom part of the lens.<br/><b>Progressive:</b> Progressive is a multifocal lens designed without lines. It has both the advantages of the single vision aesthetics plus the multifocal magnifications. Therefore, it allows the patient to see distance, intermediate and near. To avoid peripheral distortion caused by ALL conventional progressive lenses, Overnight Glasses includes Enhanced Accuracy Freedom HD Lenses for all progressive lens orders.",
    "SPHERE"                    => carbon_get_theme_option('i_popup_for_rx_eyp_sphere'),
    "CYLINDER"                  => carbon_get_theme_option('i_popup_for_rx_eyp_cylinder'),
    "AXIS"                      => carbon_get_theme_option('i_popup_for_rx_eyp_axis'),
    "ADD"                       => carbon_get_theme_option('i_popup_for_rx_eyp_add'),
    "Pupillary Distance (PD)"   => carbon_get_theme_option('i_popup_for_rx_eyp_pupillary_distance_pd'),
    "Pupillary Distance 2" => "Second PD Value provided by your doctor.",
    "Add Prism"                 => carbon_get_theme_option('i_popup_for_rx_eyp_add_prism'),
    "Vertical"                  => carbon_get_theme_option('i_popup_for_rx_eyp_vertical'),
    "Base Direction"            => carbon_get_theme_option('i_popup_for_rx_eyp_base_direction'),
    "Horizontal"                => carbon_get_theme_option('i_popup_for_rx_eyp_horizontal'),
    "Prism Value"               => carbon_get_theme_option('i_popup_for_rx_eyp_prism_value'),
    "Prescription Image (recommended)"	=> "Overnight Glasses recommends all its customers to attach their prescription image. Our optical team will match your prescription image with your entered prescription for enhanced accuracy."
];


echo $label . "<a href='#' class='popup-rx-tooltip' data-open='" . strtolower(preg_replace('~[^\w]~','',$label)) . "'></a>
                <div class='reveal' id='" . strtolower(preg_replace('~[^\w]~','',$label)) . "' data-reveal>
                    <button class='close-button' data-close='' aria-label='Close modal' type='button'>
                        <span aria-hidden='true'>×</span>
                    </button>
                <p class='popup-rx-title'>" . $label . "</p>
                <hr class='gold-line'>
                <div class='popup-rx-text'>" . $tooltips[$label] . "</div>
                </div>";
