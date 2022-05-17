<?php

/**
 * Manage Retargeting.
 *
 * @package  ONG/CLI
 * @category CLI
 */
class ONG_Addon_Retargeting_CLI_Commands extends WP_CLI_Command
{
    public function sendemail()
    {
        ongRetargeting::addInRetargetingExistingCustomers();
    }
}
