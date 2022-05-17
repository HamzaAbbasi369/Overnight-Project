<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Controller\Admin;

use OngStore\Core\Core;
use OngStore\Core\Helper\Config;

class Sync
{

    public function __construct(
        Config $config,
        \OngStore\Core\Model\Sync $sync
    ) {
        $this->config = $config;
        $this->sync   = $sync;
    }

    /**
     * AJAX callback
     */
    public function run()
    {
        $nonce = $_REQUEST['step_nonce'];
        $step  = (int) $_REQUEST['step'];
        if (!wp_verify_nonce($nonce, 'ong-store-sync-nonce')) {
            echo '';
            wp_die();
        }

        $result_template = sprintf("%ssrc/Core/view/templates/syncResult.php", ONG_INSTANT_FILTER_PLUGIN_PATH);

        $results = $this->sync->run($step);

        $total = $results['grand_total'];
        $data  = [
            'finish' => !$results['grand_total'],
            'html'   => [],
        ];
        unset($results['grand_total']);
        if ($total) {
            foreach ($results as $label => $blogs) {
                foreach ($blogs as $id => $store) {
                    if (empty($data['html'][ $id ][ $label ])){
                        $data['html'][ $id ][ $label ] = '';
                    }
                    ob_start();
                    include($result_template);
                    $data['html'][ $id ][ $label ] .= trim(ob_get_contents());
                    ob_end_clean();
                    $data['html'][ $id ]['name'] = $store['name'];
                }
            }
        } else {
            ob_start();
            include($result_template);
            $data['html'] = trim(ob_get_contents());
            ob_end_clean();
            update_option(Config::IS_SYNCED, 1);
        }
        echo json_encode($data);

        wp_die();
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (!current_user_can(Core::ONG_STORE_CAPABILITY)) {
            wp_die(__('You do not have sufficient permissions to access this page.', Config::LANG_DOMAIN));
        }

        if (!empty($_REQUEST['action']) &&
            'update' == $this->config->prepareProductCode(sanitize_text_field($_REQUEST['action'])) &&
            wp_verify_nonce($_REQUEST['_wpnonce'], 'ong_search-group-options')
        ) {
            try {
                $this->sync->run();

                add_action('ong_search_sync_success', [$this, 'sampleAdminNoticeSuccess']);
            } catch (\Exception $e) {
                $error_message = $e->getMessage();
            }

        }
        // vars for template
        $loader = $this->getLoaderHtml();
        // Render the settings template
        include(sprintf("%ssrc/Core/view/templates/sync.php", ONG_INSTANT_FILTER_PLUGIN_PATH));
    }

    /**
     * @return string
     */
    public function getLoaderHtml()
    {
        return '<span class="spinner active is-active" style="float: none; margin-left: 15px;"></span>';
    }

    /**
     *
     */
    public function sampleAdminNoticeSuccess()
    {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Data has been synchronized!'); ?></p>
        </div>
        <?php
    }
}
