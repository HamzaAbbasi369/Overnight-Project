<?php

add_shortcode(apply_filters("ssi_inslude_shortcode_tag", 'ssi_include'), 'ssi_include_cb');
add_action( 'init', 'ssi_include_init'  );

function ssi_include_init () {
    add_action('wp_ajax_ssi_include', 'ssi_include_ajax');
    add_action('wp_ajax_nopriv_ssi_include', 'ssi_include_ajax');
}

/**
 * SSI include shortcode.
 *
 * @param      $atts
 * @param null $content
 *
 * @return string
 */
function ssi_include_cb($atts, $content = null)
{
    $atts = shortcode_atts([
        'class'  => '',
        'action' => 'include', // 'ssi_include'
        'do'     => 'shortcode',
        'slug'     => null,
        'value'  => null
    ], $atts, 'ssi_includes');

    $atts['value'] = ($atts['value'] ?: $content);

    if (!isset($atts['do'], $atts['value'])) {
        return '';
    }

    if ($atts['action']=='ssi_include') {
        $atts = array_map('urlencode', $atts);
        $url = add_query_arg($atts, admin_url('admin-ajax.php', 'relative'));

        $html = '<!--#include virtual="'.$url.'"-->';
    } else { //direct include
        $class = $atts['class'];
        unset($atts['class']);

        $html = ssi_do_action($atts['do'], $atts['value'], $atts['slug']);
    }

    return $html;
}

function ssi_do_action($do, $value, $slug = '') {
    $content = '';
    switch ($do) {
        case 'template-part':
            ob_start();
            if (function_exists('ong_get_template_part')) {
                ong_get_template_part($slug, $value);
            } else {
                get_template_part($slug, $value);
            }
            $content = ob_get_clean();
            break;
        case 'callback':
            if (is_callable($value)) {
                $content = call_user_func($value);
            }
            break;
        case 'shortcode':
        default:
            $content = do_shortcode($value, true);
            break;
    }
    return $content;
}

function ssi_include_ajax()
{
    global $wp_scripts;
    $do    = urldecode($_GET['do']);
    $value = urldecode($_GET['value']);
    $slug = urldecode($_GET['slug']);

    die(ssi_do_action($do, $value, $slug));
}
