<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core;

use OngStore\Core\Controller\Admin\Sync;
use OngStore\Core\Controller\Admin\Application;
use OngStore\Core\Helper\Template;
use OngStore\Core\Helper\Config;


class Core
{
    const ONG_STORE_CAPABILITY = 'manage_options';

    /**
     * Construct the plugin object
     */
    public function __construct(
        Config $config,
        \OngStore\Core\Api\Config $apiConfig,
        Sync $syc,
        Application $application,
        Template $templateHelper
    ) {
        $this->config         = $config;
        $this->apiConfig      = $apiConfig;
        $this->sync           = $syc;
        $this->application    = $application;
        $this->templateHelper = $templateHelper;

        add_action('init', [&$this, 'cancel_search']);
        add_action('admin_init', [&$this, 'adminInit']);
        add_action('admin_menu', [&$this, 'add_menu']);
        add_action('admin_head', [&$this, 'add_custom_css']);
        add_action('admin_enqueue_scripts', [&$this, 'ong_enqueue']);

        add_action('wp_ajax_ong_data_sync', [&$this->sync, 'run']);

        add_action('update_option_ong_api_access_roles', [&$this, 'addCaps']);

        add_action('admin_footer', [&$this, 'disconnect_account_js']);
        add_action('wp_ajax_ong_disconnect_account', [&$this, 'disconnectAccount']);

        // added WP wrapper to ong admin page
        add_filter('contextual_help_list', [&$this, 'startWrapper']);
        add_action('toplevel_page_ong_store', [&$this, 'endWrapper']);

//        add_filter('template_include', array($this->templateHelper, 'set_search_template'), 1000);

        add_action('init', function(){
            remove_filter( 'wp_get_attachment_image_attributes', 'wc_get_attachment_image_attributes' );
        });
    }

    /**
     * Start WP wrapper for ong admin page
     */
    public function startWrapper($help)
    {
        echo '<div class="wrap">';

        return $help;
    }

    /**
     * End WP wrapper for ong admin page
     */
    public function endWrapper()
    {
        echo '</div>';
    }

    /**
     * Disable standard search for quickest display ONG results
     */
    public function cancel_search()
    {
//        if (!empty($_REQUEST['ong']) && $_REQUEST['ong'] == $this->templateHelper->getONGSearchValue()) {
//            unset($_REQUEST['s']);
//        }
//        if (!empty($_REQUEST['ong']) && $_REQUEST['ong'] == $this->templateHelper->getONGSearchValue()) {
//            unset($_REQUEST['s']);
//        }
    }

    /**
     * Add js on ONG settings page
     *
     * @param string $hook
     */
    public function ong_enqueue($hook)
    {
        if ('ong-search_page_ong_store_settings' == $hook) {
            wp_enqueue_script('ong_settings_script', sprintf("%ssrc/Core/view/js/settings.js", ONG_INSTANT_FILTER_PLUGIN_URL));

            return;
        }

        if ('admin_page_ong_store_sync' == $hook) {
            wp_enqueue_script('ong_sync_script', sprintf("%ssrc/Core/view/js/sync.js", ONG_INSTANT_FILTER_PLUGIN_URL), [], false, true);

            return;
        }
    }

    /**
     * Custom css
     */
    public function add_custom_css()
    {
        echo '
            <style>
                .toplevel_page_ong_store img {
                    width: 20px;
                    height: 20px;
                }
            </style>';
    }

    /**
     * Custom Js
     */
    public function disconnect_account_js()
    { ?>
        <script type="text/javascript">
            var ongSyncAjaxUrl = ajaxurl;

            if (ongSyncAjaxUrl.search(/\?/) == -1) {
                ongSyncAjaxUrl += "?";
            } else {
                ongSyncAjaxUrl += "&";
            }
            ongSyncAjaxUrl += "action=ong_data_sync&step_nonce=<?php echo wp_create_nonce('ong-store-sync-nonce') ?>";

            function disconnect_account(el) {
                var data = {
                    'action': 'ong_disconnect_account',
                    'disconnect': 1
                };

                jQuery.post(ajaxurl, data, function (response) {
                    window.location.reload();
                });
            }
        </script> <?php
    }

    /**
     * Disconnect ong account
     */
    public function disconnectAccount()
    {
        if ((int) $_REQUEST['disconnect']) {
            $api_config = new \OngStore\Core\Api\Config($this->config);

            $api_config->disconnect();
            update_option('ong_api_access_iuid', '');
            update_option('ong_api_access_secret_key', '');
        }
    }

    /**
     * Activate the plugin
     */
    public static function activate()
    {
    }

    /**
     * Deactivate the plugin
     */
    public static function deactivate()
    {
    }

    /**
     * hook into WP's admin_init action hook
     */
    public function adminInit()
    {
        $this->init_settings();
        $this->isSynchronized();
        if ($this->config->isWooCommerceActive() ) {
            WC()->frontend_includes();
        }
    }

    /**
     * Added synchronized notice
     */
    public function isSynchronized()
    {
        global $pagenow, $plugin_page;

        $ongPages = [
            'ong_store',
            'ong_store_settings'
        ];

        if ( (!get_option(Config::IS_SYNCED, 0) && $pagenow == 'plugins.php' )
            || in_array($plugin_page, $ongPages)
        ) {
            add_action('admin_notices', [$this, 'syncedNoticeHtml']);
        }
    }

    /**
     * Display synchronized notice
     */
    public function syncedNoticeHtml()
    {
        $isLoggedIn = $this->apiConfig->getAUid(\OngStore\FacetedFilter\Api\Config::PRODUCT_CODE) != "";
        require_once(ONG_INSTANT_FILTER_PLUGIN_PATH . "src/Core/view/templates/syncNotice.php");
    }

    /**
     * ACL
     */
    public function addCaps()
    {
        $role_class = wp_roles();
        // get role names
        $roles         = $role_class->get_names();
        $allowed_roles = get_option('ong_api_access_roles', []);
        foreach ($roles as $name => $v) {
            $role = get_role($name);
            if (in_array($name, $allowed_roles)) {
                $role->add_cap(self::ONG_STORE_CAPABILITY);
            } else {
                $role->remove_cap(self::ONG_STORE_CAPABILITY);
            }
        }
    }

    /**
     * Initialize some custom settings
     */
    public function init_settings()
    {
        register_setting('ong_core-group', 'ong_api_access_iuid');
        register_setting('ong_core-group', 'ong_api_access_secret_key');
        register_setting('ong_search-group', 'ong_api_access_base_url');
        add_option('ong_api_access_base_url', "mongodb://localhost:27017");
    }

    /**
     * Add a menu
     */
    public function add_menu()
    {
        add_menu_page(
            __('Search Dashboard', Config::LANG_DOMAIN),
            __('ONG Filter', Config::LANG_DOMAIN),
            'read',
            'ong_store',
            [&$this->application, 'execute'],
            sprintf("%sassets/images/logo.png", ONG_INSTANT_FILTER_PLUGIN_URL)
        );
        add_submenu_page(
            'ong_store',
            __('Settings', Config::LANG_DOMAIN),
            __('Settings', Config::LANG_DOMAIN),
            self::ONG_STORE_CAPABILITY,
            'ong_store_settings',
            [&$this, 'plugin_settings_page']
        );

        add_submenu_page(
            null, //we don't want to add it to menu
            __('ONG Store Sync', Config::LANG_DOMAIN),
            __('Sync', Config::LANG_DOMAIN),
            self::ONG_STORE_CAPABILITY,
            'ong_store_sync',
            [&$this->sync, 'execute']
        );
    }

    /**
     * Menu Callback
     */
    public function plugin_settings_page()
    {
        if (!current_user_can(self::ONG_STORE_CAPABILITY)) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        // Render the settings template
        require_once(sprintf("%ssrc/Core/view/templates/settings.php", ONG_INSTANT_FILTER_PLUGIN_PATH));
    }
}
