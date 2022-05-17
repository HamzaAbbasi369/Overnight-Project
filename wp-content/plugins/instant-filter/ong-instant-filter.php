<?php
/*
Plugin Name: ONG instant Filter For WooCommerce
Description: ONG instant Filter delivers the right search results for your customers, thus, driving more sales!
Version: 20180621
Author: Vision Care Services LLC
Author URI: https://overnightglasses.com
License: MIT

The MIT License (MIT)

Copyright (c) odokienko

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

spl_autoload_register('ongstore_autoloader');
function ongstore_autoloader($class_name)
{
    if (false !== strpos($class_name, 'OngStore')) {
        $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
        $class_name  = str_replace('OngStore\\', '', $class_name);
        $class_file  = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
        if (file_exists($classes_dir . $class_file)) {
            require_once $classes_dir . $class_file;
        }
    }
}
if ( defined( 'WP_CLI' ) && WP_CLI ) {
    WP_CLI::add_command('instant_filter', '\OngStore\Core\Controller\CLI\Sync');
}

define('ONG_INSTANT_FILTER_PLUGIN_VERSION', '2018021');
define('ONG_INSTANT_FILTER_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('ONG_INSTANT_FILTER_PLUGIN_URL', plugin_dir_url(__FILE__));

add_action('init', function() {
    if(function_exists("register_field_group")) {
        register_field_group(array (
            'id' => 'acf_basic-colors',
            'title' => 'Basic colors',
            'fields' => array (
                array (
                    'key' => 'field_58fde94964a9a',
                    'label' => 'One or more basic colors',
                    'name' => 'basic_colors',
                    'type' => 'taxonomy',
                    'taxonomy' => 'pa_color',
                    'field_type' => 'checkbox',
                    'allow_null' => 0,
                    'load_save_terms' => 0,
                    'return_format' => 'object',
                    'multiple' => 0,
                ),
            ),
            'location' => array (
                array (
                    array (
                        'param' => 'ef_taxonomy',
                        'operator' => '==',
                        'value' => 'pa_color',
                        'order_no' => 0,
                        'group_no' => 0,
                    ),
                ),
            ),
            'options' => array (
                'position' => 'normal',
                'layout' => 'no_box',
                'hide_on_screen' => array (
                ),
            ),
            'menu_order' => 0,
        ));
    }
});

$config           = new \OngStore\Core\Helper\Config();

$templateHelper   = new \OngStore\Core\Helper\Template();
$apiConfig        = new \OngStore\Core\Api\Config($config);
$client           = new \OngStore\Core\Api\Client($apiConfig);
$apiFactory       = new \OngStore\Core\Api\ApiFactory($config);
$sync             = new \OngStore\Core\Model\Sync($config, $templateHelper, $apiFactory);
$syncController   = new \OngStore\Core\Controller\Admin\Sync($config, $sync);
$application      = new \OngStore\Core\Controller\Admin\Application($config);
new \OngStore\Core\Core($config, $apiConfig, $syncController, $application, $templateHelper);

$searchApiConfig  = new \OngStore\FacetedFilter\Api\Config($apiConfig);
//$autocomplete     = new \OngStore\FacetedFilter\Api\Autocomplete($searchApiConfig);
$searchApiFactory = new \OngStore\FacetedFilter\Api\ApiFactory($apiFactory);
$SearchHelper = new \OngStore\FacetedFilter\Helper\Data();
$Search = new \OngStore\FacetedFilter\OngFilter($searchApiFactory, $SearchHelper);

register_activation_hook(__FILE__, array($Search, 'activate'));
register_deactivation_hook(__FILE__, array($Search, 'deactivate'));
