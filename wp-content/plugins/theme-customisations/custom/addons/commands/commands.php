<?php

try {
    WP_CLI::add_command('ong', 'ONG_Addon_Commands_CLI');
    WP_CLI::add_command('ong terms', 'ONG_Addon_Commands_Terms_CLI');
} catch (Exception $e) {
    //do nothing
}

