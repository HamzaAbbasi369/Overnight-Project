<?php

/**
 * Manage Club Users.
 *
 * @package  ONG/CLI
 * @category CLI
 */
class ONG_Addon_Club_CLI_Commands extends WP_CLI_Command
{
    public function addoldcustomers()
    {
        if (function_exists('duplicate_post_init')) {
            require_once( WP_PLUGIN_DIR . '/duplicate-post/duplicate-post-admin.php' );
            duplicate_post_admin_init();
        }
        Ong_Addon_Club_Add_In_Club_Existing_Customers::addInClubExistingCustomers();
    }
}
