<?php
/**
 * theme-customisations
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2018 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

if (defined('WP_CLI') && WP_CLI) {
    try {
        WP_CLI::add_command('ong product', 'ONG_Addon_Product_CLI');
    } catch (Exception $e) {
        //do nothing
    }
}