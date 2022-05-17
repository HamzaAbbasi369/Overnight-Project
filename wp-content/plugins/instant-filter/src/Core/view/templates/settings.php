<div class="wrap">

    <?php
    /**
     * ONG Store
     *
     * Licence: MIT https://opensource.org/licenses/MIT
     * Copyright: odokienko
     */
    if (isset($error_message)) : ?>
        <div class="notice notice-error">
            <p><?php echo __($error_message); ?></p>
        </div>
    <?php endif; ?>
    <?php if (get_option('ong_api_access_iuid')): ?>
        <h2>Data Synchronization
            <?php do_action('ong_search_sync_success'); ?>
            <a href="<?php menu_page_url('ong_store_sync', true) ?>" target="_blank"
               class="add-new-h2 page-title-action">
                <?php echo __('Run Data Synchronization', Config::LANG_DOMAIN) ?>
            </a>
        </h2>
    <?php endif; ?>
    <h2>API Access Settings</h2>
    <form method="post" action="options.php">
        <?php @settings_fields('ong_search-group'); ?>
        <?php @do_settings_fields('ong_search-group',''); ?>

        <table class="form-table">
            <?php if (get_option('ong_api_access_iuid')): ?>
                <tr valign="top">
                    <th scope="row"><label for="ong_api_access_iuid">Instance ID</label></th>
                    <td>
                        <b><?php echo get_option('ong_api_access_iuid'); ?></b>
                        <br><br>
                        <a href='#' onclick="disconnect_account(this); return false;">
                            <?php echo __('Disconnect Account', Config::LANG_DOMAIN) ?>
                        </a>
                    </td>
                </tr>
            <?php endif; ?>
            <tr valign="top">
                <th scope="row"><label for="ong_api_access_base_url">Connection String</label></th>
                <td>
                    <input type="text" name="ong_api_access_base_url" id="ong_api_access_base_url"
                           value="<?php echo get_option('ong_api_access_base_url'); ?>" size="50"/>
                    <p class="description">Used for debugging. Don't change this if you not sure</p>
                </td>
            </tr>
            <?php echo apply_filters('ong_store_core_setting_form', '') ?>
        </table>

        <?php @submit_button(); ?>
    </form>


</div>
