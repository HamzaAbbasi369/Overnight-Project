<?php

require_once WcRx::plugin_path() . '/includes/FuncParser.php';

include 'rx_functions_variables.php';

function to_price($amount, $pfactor = 1)
{
    $pstring = '';

    if ($pfactor > 0 && $pfactor < 1) {
        $pstring = "<span class='old-price'>\$ " . number_format(floatval($amount), 2) . "</span>";
        $pstring = $pstring . "\$ " . number_format($pfactor * floatval($amount), 2) . "";
    } elseif ($pfactor > 1 && $pfactor > $amount) {
        //$pfactor is regular_price
        $pstring = "<span class='old-price'>\$ " . number_format($pfactor, 2) . "</span> ";
        $pstring = $pstring . "<span class='new-price'>\$ " . number_format(floatval($amount), 2) . "</span>";
    } else {
        $pstring = $pstring . "<span>\$ " . number_format(floatval($amount), 2) . "</span>";
    }

    return $pstring;
}


function rx_get_template_html($template_name, $args = [], $template_path = '', $default_path = '')
{
    ob_start();
    rx_get_template($template_name, $args, $template_path, $default_path);

    return ob_get_clean();
}

function rx_get_template($template_name, $args = [], $template_path = '', $default_path = '')
{
    if (!empty($args) && is_array($args)) {
        extract($args);
    }

    $located = rx_locate_template($template_name, $template_path, $default_path);

    if (!file_exists($located)) {
        _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $located), '2.1');

        return;
    }

    // Allow 3rd party plugin filter template file from their plugin.
    $located = apply_filters('rx_get_template', $located, $template_name, $args, $template_path, $default_path);

    do_action('rx_before_template_part', $template_name, $template_path, $located, $args);

    include($located);

    do_action('rx_after_template_part', $template_name, $template_path, $located, $args);
}

function rx_locate_template($template_name, $template_path = '', $default_path = '')
{
    if (!$template_path) {
        $template_path = WcRx::template_path();
    }

    if (!$default_path) {
        $default_path = WcRx::plugin_path() . '/templates/';
    }

    // Look within passed path within the theme - this is priority.
    $template = locate_template(
        [
            trailingslashit($template_path) . $template_name,
            $template_name
        ]
    );

    // Get default template/
    if (!$template || WC_TEMPLATE_DEBUG_MODE) {
        $template = $default_path . $template_name;
    }

    // Return what we found.
    return apply_filters('rx_locate_template', $template, $template_name, $template_path);
}


function draw_checkbox($id, $action, $tooltip, $title, $html, $description,$group=false)
{
    $args = [];
    foreach (FuncParser::getFuncArgs(__FUNCTION__) as $param) {
        $args[$param->getName()] = ${$param->getName()};
    }
    rx_get_template(__FUNCTION__ . '.php', $args);

    return;
}

function draw_navbar($id, $back_label, $back_action, $next_label, $next_action)
{
    $args = [];
    foreach (FuncParser::getFuncArgs(__FUNCTION__) as $param) {
        $args[$param->getName()] = ${$param->getName()};
    }
    rx_get_template(__FUNCTION__ . '.php', $args);

    return;
}

function draw_progressive_lens_package()
{
    $args = [];
    foreach (FuncParser::getFuncArgs(__FUNCTION__) as $param) {
        $args[$param->getName()] = ${$param->getName()};
    }
    rx_get_template(__FUNCTION__ . '.php', $args);

    return;
}

function draw_lens_packages()
{
    $args = [];
    foreach (FuncParser::getFuncArgs(__FUNCTION__) as $param) {
        $args[$param->getName()] = ${$param->getName()};
    }
    rx_get_template(__FUNCTION__ . '.php', $args);

    return;
}


function draw_text_selector(
    $id,
    $tooltip,
    $title,
    $name,
    $action,
    $html,
    $description = null,
    $submenu = null,
    $size = null,
    $checked = null,
    $extra = null,
    $data = null
)
{

    draw_selector(
        $id,
        $tooltip,
        $title,
        $name,
        $action,
        $html,
        $description,
        $submenu,
        $size,
        $checked,
        null,
        $extra,
        $data
    );
}


function draw_image_selector(
    $id,
    $tooltip,
    $title,
    $name,
    $action,
    $html,
    $description = null,
    $image,
    $submenu = null,
    $size = null,
    $checked = null,
    $extra = null,
    $data = null,
    $style = null,
    $extraclass = null
)
{

    draw_selector(
        $id,
        $tooltip,
        $title,
        $name,
        $action,
        $html,
        $description,
        $submenu,
        $size,
        $checked,
        $image,
        $extra,
        $data,
        $style,
        $extraclass
    );
}

function draw_selector($id, $tooltip, $title, $name,
                       $action = null, $icon = null, $description = null, $submenu = null, $size = null, $checked = null, $image = null, $extra = null, $data = null, $style = null, $extraclass = null)
{
    if (null === $icon) {
        $icon = "";  //default value
    }
    if (null === $description) {
        $description = "";  //default value
    }
    if (null === $submenu) {
        $submenu = "";  //default value
    }
    if (null === $size) {
        $size = "height:125px"; //default value
    }
    $checked = !!$checked;
    if (null === $image) {
        $image = ""; //default value
    }
    if (null === $extra) {
        $extra = ""; //default value
    }
    if (null === $data || !is_array($data)) {
        $data = []; //default value
    }

    $args = [];
    foreach (FuncParser::getFuncArgs(__FUNCTION__) as $param) {
        $args[$param->getName()] = ${$param->getName()};
    }
    rx_get_template(__FUNCTION__ . '.php', $args);

    return;
}


//function draw_color_selector(
//    $id,
//    $tooltip,
//    $action,
//    $title,
//    $name,
//    $html,
//    $description,
//    $color,
//    $submenu = null,
//    $size = null,
//    $checked = null,
//    $extra = null
//    )
//{
//
//    draw_selector($id,
//        $tooltip,
//        $action,
//        $title,
//        $name,
//        $html,
//        $description,
//        $submenu,
//        $size,
//        $checked,
//        null,
//        $extra);
//}

function draw_feature_icon($desc, $image_file)
{
    $args = [];
    foreach (FuncParser::getFuncArgs(__FUNCTION__) as $param) {
        $args[$param->getName()] = ${$param->getName()};
    }
    rx_get_template(__FUNCTION__ . '.php', $args);
    return;
}

function get_feature_icon($desc, $image_file)
{
    ob_start();
    draw_feature_icon($desc, $image_file);

    return ob_get_clean();
}

function print_rx_select($label, $name, $id, $onchange, $min, $max, $step, $initial_value = null, $default_value = null)
{
    if (null === $initial_value) {
        $initial_value = "";  //default value
    }

    if (null === $default_value) {
        $default_value = "";  //default value
    }

    $args = [];
    foreach (FuncParser::getFuncArgs(__FUNCTION__) as $param) {
        $args[$param->getName()] = ${$param->getName()};
    }
    rx_get_template(__FUNCTION__ . '.php', $args);

    return;
}

function print_rx_select_plus($label, $name, $id, $onchange, $min, $max, $step, $intial_value = null, $default_value = null)
{
    if (null === $intial_value) {
        $intial_value = "";  //default value
    }

    if (null === $default_value) {
        $default_value = "";  //default value
    }

    $args = [];
    foreach (FuncParser::getFuncArgs(__FUNCTION__) as $param) {
        $args[$param->getName()] = ${$param->getName()};
    }
    rx_get_template(__FUNCTION__ . '.php', $args);

    return;
}

function draw_tint_selection()
{
    $args = [];
    foreach (FuncParser::getFuncArgs(__FUNCTION__) as $param) {
        $args[$param->getName()] = ${$param->getName()};
    }
    rx_get_template(__FUNCTION__ . '.php', $args);

    return;
}

function print_rx_label($label, $class = null)
{
    if (null === $class) {
        $class = "";  //default value
    }

    $args = [];
    foreach (FuncParser::getFuncArgs(__FUNCTION__) as $param) {
        $args[$param->getName()] = ${$param->getName()};
    }
    rx_get_template(__FUNCTION__ . '.php', $args);

    return;
}

function draw_navigation_control($d_mode)
{
    $args = [];
    foreach (FuncParser::getFuncArgs(__FUNCTION__) as $param) {
        $args[$param->getName()] = ${$param->getName()};
    }
    rx_get_template(__FUNCTION__ . '.php', $args);

    return;
}

function draw_tag($tag, $content)
{
    return '<' . $tag . '>' . $content . '</' . $tag . '>';
}

function print_color_select($name, $id, $action, $title, $is_package_mode = true)
{
    ob_start();
    draw_color_select($name, $id, $action, $title, $is_package_mode);

    return ob_get_clean();
}

function draw_color_select($name, $id, $action, $title, $is_package_mode)
{
    require_once WcRx::plugin_path() . '/includes/rx_fees.php';
    $d_mode = (isset($_REQUEST['d_mode'])) ? $_REQUEST['d_mode'] : '';
    $type = $title;

    $output = '';
    ob_start();

    /** If the variable is defined $is_package_mode -> package*/

    switch (true):
        case $is_package_mode && $type == 'Tinted Lenses': ?>
            <div class="row clear-padding lens-tint-color tint_options_template">
                <p class="text-center rx-description-packages">Select a lens tint color</p>
                <?php draw_color_selector('Green', 'green', 'tint_color', 'rgba(66,68,31,0.89)', 'tint_color_green', "rx.lensColor.changeTintColor('Green', 'package')", 'package'); ?>
                <?php draw_color_selector('Gray', 'gray', 'tint_color', '#727272', 'tint_color_gray', "rx.lensColor.changeTintColor('Gray', 'package')", 'package'); ?>
                <?php draw_color_selector('Brown', 'brown', 'tint_color', '#7a5836', 'tint_color_brown', "rx.lensColor.changeTintColor('Brown', 'package')", 'package'); ?>
            </div>
            <?php break;
        case !$is_package_mode && $type == 'Tinted Lenses': ?>
            <div class="row clear-padding lens-tint-color tint_options_template">
                <p class="text-center rx-description-packages">Select a lens tint color</p>
                <?php draw_color_selector('Green', 'green', 'tint_color', 'rgba(66,68,31,0.89)', 'tint_color_green', "rx.lensColor.changeTintColor('Green', 'custom')", 'custom'); ?>
                <?php draw_color_selector('Gray', 'gray', 'tint_color', '#727272', 'tint_color_gray', "rx.lensColor.changeTintColor('Gray', 'custom')", 'custom'); ?>
                <?php draw_color_selector('Brown', 'brown', 'tint_color', '#7a5836', 'tint_color_brown', "rx.lensColor.changeTintColor('Brown', 'custom')", 'custom'); ?>
            </div>
            <?php break;
        case $is_package_mode && $type == 'Light Responsive':
        case $is_package_mode && ($type == 'Transitions®' || $type == 'Transitions &reg;' || $type == 'Transitions ®'):
            ?>
            <div id="tint_options_template" class="row clear-padding lens-tint-color roundclor">
                <p class="text-center rx-description-packages">Select a lens tint color</p>
                <?php draw_color_selector('Gray', 'gray', 'tint_color', '#727272', 'tint_color_gray1', "rx.lensColor.changeTintColor('Gray', 'package')", 'package'); ?>
                <?php draw_color_selector('Brown', 'brown', 'tint_color', '#7a5836', 'tint_color_brown1', "rx.lensColor.changeTintColor('Brown', 'package')", 'package'); ?>
                <?php draw_color_selector('Gray Xtra Active', 'xtra', 'tint_color', '#727272', 'tint_color_extra_active', "rx.lensColor.changeTintColor('Gray Xtra Active', 'package')", 'package'); ?>
                <?php draw_color_selector('Brown Xtra Active', 'xtra', 'tint_color', '#7a5836', 'tint_color_extra_active', "rx.lensColor.changeTintColor('Brown Xtra Active', 'package')", 'package'); ?>
            </div>

            <?php break;
        case !$is_package_mode && $type == 'Light Responsive':
        case !$is_package_mode && ($type == 'Transitions®' || $type == 'Transitions &reg;' || $type == 'Transitions ®'):
            ?>
            <div id="tint_options_template" class="row clear-padding lens-tint-color roundclor">
                <p class="text-center rx-description-packages">Select a lens tint color</p>
                <?php draw_color_selector('Gray', 'gray', 'tint_color', '#727272', 'lr_t_gray', "rx.lensColor.setLR('Transitions®','Gray', 'custom')", 'custom'); ?>
                <?php draw_color_selector('Brown', 'brown', 'tint_color', '#7a5836', 'lr_t_brown', "rx.lensColor.setLR('Transitions®','Brown', 'custom')", 'custom'); ?>
                <?php draw_color_selector('Gray Xtra Active', 'green', 'tint_color', '#485052', 'lr_t_xa', "rx.lensColor.setLR('Transitions®','Gray Xtra Active', 'custom')", 'custom'); ?>
                <?php draw_color_selector('Brown Xtra Active', 'green', 'tint_color', '#7a5836', 'lr_t_xa', "rx.lensColor.setLR('Transitions®','Brown Xtra Active', 'custom')", 'custom'); ?>


            </div>

            <?php break;
        case $is_package_mode && $type == 'Polarized': ?>
            <div class="roundclor">
                <div class="tinttitle">POLARIZED - $0</div>
                <div id="tint_options_template" class="row clear-padding lens-tint-color">
                    <p class="text-center rx-description-packages">Tint color:</p>
                    <?php draw_color_selector('Gray', 'gray', 'tint_color', '#727272', 'tint_color_gray', "rx.lensColor.changeTintColor('Gray', 'package')", 'package'); ?>
                    <?php draw_color_selector('Brown', 'brown', 'tint_color', '#7a5836', 'tint_color_brown', "rx.lensColor.changeTintColor('Brown', 'package')", 'package'); ?>
                    <?php draw_color_selector('Gradient Gray', 'gradient-gray', 'tint-color', 'linear-gradient(90deg, rgba(100,99,99,1) 0%, rgba(137,137,137,1) 49%, rgba(199,199,200,1) 100%)', 'tint_gradient_color_gray', "rx.lensColor.changeTintColor('Gradient Gray', 'package')", 'package'); ?>
                    <?php draw_color_selector('Gradient Brown', 'gradient-brown', 'tint-color', 'linear-gradient(90deg, rgba(138,105,26,1) 25%, rgba(129,102,39,1) 51%, rgba(193,169,113,1) 100%)', 'tint_gradient_color_brown', "rx.lensColor.changeTintColor('Gradient Brown', 'package')", 'package'); ?>
                </div>
	    </div>
            <div  class="roundclor mirrorcoating">
		<div class="tinttitle newbox hide-for-small-only">ADD MIRROR COATING + $49</div>
		<div class="tinttitle show-for-small-only">ADD MIRROR COATING + $49</div>
                <div id="tint_options_template" class="row clear-padding lens-tint-color">
                    <p class="text-center rx-description-packages">Mirror color:</p>
                    <?php draw_color_selector('Silver Mirror', 'gradient-silver', 'tint-color', 'linear-gradient(90deg, rgba(100,99,99,1) 0%, rgba(137,137,137,1) 49%, rgba(199,199,200,1) 100%)', 'tint_gradient_color_gray', "rx.lensColor.changeTintColor('Silver Mirror', 'package')", 'package'); ?>
                    <?php draw_color_selector('Gold Mirror', 'gradient-gold', 'tint-color', 'linear-gradient(90deg, rgba(138,105,26,1) 25%, rgba(129,102,39,1) 51%, rgba(193,169,113,1) 100%)', 'tint_gradient_color_gold', "rx.lensColor.changeTintColor('Gold Mirror', 'package')", 'package'); ?>
                    <?php draw_color_selector('Blue Mirror', 'gradient-blue', 'tint-color', 'linear-gradient(90deg, rgba(30,68,158,1) 25%, rgba(32,94,229,1) 51%, rgba(125,185,232,1) 100%)', 'tint_gradient_color_blue', "rx.lensColor.changeTintColor('Blue Mirror', 'package')", 'package'); ?>
                </div>
            </div>

            <?php break;
        case !$is_package_mode && $type == 'Polarized': ?>
            <div id="tint_options_template" class="row clear-padding lens-tint-color">
                <p class="text-center rx-description-packages">Select a lens tint color</p>
                <?php draw_color_selector('Green', 'green', 'tint_color', 'rgba(66,68,31,0.89)', 'tint_color_green', "rx.lensColor.changeTintColor('Green', 'custom')", 'custom'); ?>
                <?php draw_color_selector('Gray', 'gray', 'tint_color', '#727272', 'tint_color_gray', "rx.lensColor.changeTintColor('Gray', 'custom')", 'custom'); ?>
                <?php draw_color_selector('Brown', 'brown', 'tint_color', '#7a5836', 'tint_color_brown', "rx.lensColor.changeTintColor('Brown', 'custom')", 'custom'); ?>
            </div>

            <?php break;
        case $type == 'Polarized Mirror Coated': ?>
            <div id="tint_options_template" class="row clear-padding lens-tint-color mirror-color text-center" style="background: white;">
                <p class="text-center rx-description-packages">Select a lens mirror color</p>
                <?php draw_color_selector('Blue', 'blue', 'tint_color', '', 'mirror_color_blue', "rx.lensColor.changeTintColor('Blue', 'custom')", 'custom'); ?>
                <?php draw_color_selector('Gold', 'gold', 'tint_color', '', 'mirror_color_gold', "rx.lensColor.changeTintColor('Gold', 'custom')", 'custom'); ?>
                <?php draw_color_selector('Platinum', 'platinum', 'tint_color', '', 'mirror_color_platinum', "rx.lensColor.changeTintColor('Platinum', 'custom')", 'custom'); ?>
                <?php draw_color_selector('Green', 'green', 'tint_color', '', 'mirror_color_green', "rx.lensColor.changeTintColor('Green', 'custom')", 'custom'); ?>
                <?php draw_color_selector('Purple', 'purple', 'tint_color', '', 'mirror_color_purple', "rx.lensColor.changeTintColor('Purple', 'custom')", 'custom'); ?>
                <?php draw_color_selector('Fire Red', 'firered', 'tint_color', '', 'mirror_color_firered', "rx.lensColor.changeTintColor('Fire Red', 'custom')", 'custom'); ?>
            </div>
            <?php break;

        default:
    endswitch;
        ?>

    <script>
        $(document).ready(function(){
        //    $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
        <?php
    $output = ob_get_clean();

    if ($output) {
        ?>
        <div style="width:100%; display: table; position: relative;">
            <?= $output ?>
        </div>
        <?php
    }
    return;
}

function draw_color_selector($color, $color_data, $tint, $color_hash, $id, $onclick, $is_package = null)
{
    $args = [];
    foreach (FuncParser::getFuncArgs(__FUNCTION__) as $param) {
        $args[$param->getName()] = ${$param->getName()};
    }
    rx_get_template(__FUNCTION__ . '.php', $args);

    return;
}
