<?php
use OngStore\Core\Helper\Config;
?>
<style>
    #sync-process ul {
        display: block;
        list-style: none outside none;
        padding: 0;
    }

    #sync-process ul li {
        display: list-item;
        list-style: none outside none;
        margin-bottom: 6px;
        text-align: left;
        z-index: 0;
    }

    #sync-process ul div.sync-loader {
        z-index: -1;
        background-color: #DDF;
        position: absolute;
        height: 100%;
        display: block;
        list-style: none outside none;
        outline: rgb(68, 68, 68) none 0;
        text-align: left;
    }
</style>
<div class="wrap">
    <div id="prepare-sync">
        <div class="notice notice-info"><p><?php
                /**
                 * ONG Store
                 *
                 * Licence: MIT https://opensource.org/licenses/MIT
                 * Copyright: odokienko
                 */

                echo __("Preparing for synchronization ...", Config::LANG_DOMAIN); ?></p></div>
    </div>
    <div id="sync-process">
        <ul>
            <li id="sync-message-block">
                <div class="notice notice-info"><p>
                        <?php echo __("Starting execution, please wait "); ?><?php echo $loader; ?><br>
                        <?php echo __("Please do not close the window during synchronization. "); ?>
                    </p></div>
            </li>
        </ul>
        <ul id="sync-rows"></ul>
    </div>
</div>
