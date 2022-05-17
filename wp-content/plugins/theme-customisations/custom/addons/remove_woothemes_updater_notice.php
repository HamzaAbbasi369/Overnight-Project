<?php

if ( is_admin() ) {
    remove_action('admin_notices', 'woothemes_updater_notice', 10);
}
