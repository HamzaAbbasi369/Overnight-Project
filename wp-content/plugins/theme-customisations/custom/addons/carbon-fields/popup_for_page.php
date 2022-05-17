<?php
if (!function_exists('carbon_get_comment_meta')) {
	return;
}
use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('theme_options', 'Popup options')
    ->set_page_parent('themes.php')
    ->add_fields([
        Field::make('complex', 'ong_popup_options', 'Show popup')
            ->add_fields(
                [Field::make("checkbox", "ong_popup_show", "Show popup")
                    ->set_option_value('yes')
                    ->set_width(10),
                    Field::make('text', 'ong_popup_url', 'Url page')
                        ->set_width(90),
                    Field::make('textarea', 'ong_popup_text_html', 'Popup HTML')
                        ->set_width(70),
                    Field::make('textarea', 'ong_popup_text_css', 'Popup CSS')
                        ->set_width(30)
                ]
            )
    ]);


if (!isset($_COOKIE['ongShowPopup'])) {
    $currentUrl[] = $_SERVER['REQUEST_URI'];
    $currentUrl[] = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));

    $data = carbon_get_theme_option('ong_popup_options', 'complex');

    usort($data, function ($a, $b) {
        $a = strlen($a['ong_popup_url']);
        $b = strlen($b['ong_popup_url']);
        return ($a === $b) ? 0 : (
        ($a < $b) ? 1 : -1
        );
    });

    foreach ($data as $k => $val) {

        if (in_array($val['ong_popup_url'], $currentUrl) && $val['ong_popup_show'] === "yes") {
            $ong_popup_text_html = $val['ong_popup_text_html'];
            $ong_popup_text_css = $val['ong_popup_text_css'];

            add_action('wp_footer', function () use ($ong_popup_text_html) {
                echo do_shortcode($ong_popup_text_html);
            });

            add_action('wp_enqueue_scripts', function () use ($ong_popup_text_css) {
                wp_add_inline_style('custom-css', $ong_popup_text_css);
            }, 1000);
            break;
        }
    }
}
