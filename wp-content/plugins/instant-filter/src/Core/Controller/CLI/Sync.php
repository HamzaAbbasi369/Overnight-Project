<?php
namespace OngStore\Core\Controller\CLI;

use WP_CLI_Command;
use OngStore\Core\Helper\Config;

/**
 * Manage Products.
 *
 * @package  ONG/CLI
 * @category CLI
 */
class Sync extends WP_CLI_Command {

    protected $config;
    protected $sync;

    public function __construct() {
        add_filter('max_srcset_image_width', function($max_width, $size_array){
            return $size_array[0];
        }, 10, 2);

        $this->config       = new \OngStore\Core\Helper\Config();
        $templateHelper     = new \OngStore\Core\Helper\Template();
        $apiFactory         = new \OngStore\Core\Api\ApiFactory($this->config);
        $this->sync         = new \OngStore\Core\Model\Sync($this->config, $templateHelper, $apiFactory);;
    }

    /**
     *
     * ## EXAMPLES
     *
     *     vendor/bin/wp instant-filter run
     *
     * @subcommand run
     * @since      2.5.0
     */
    public function run($args, $assoc_args)
    {
        $result_template = sprintf("%ssrc/Core/view/templates/CliSyncResult.php", ONG_INSTANT_FILTER_PLUGIN_PATH);
        $step = 1;
        $lines = 0;
	do {
            if ($args[0] != "") {
                    echo "Single product sync ".$args[0];
                    $this->sync->sync_product($args[0]);
                    exit(0);
            }	    	
	    $results = $this->sync->run($step++);
            $total = $results['grand_total'];
            $finish = !!$results['grand_total'];
            unset($results['grand_total']);
            unset($results['']);
            if ($total) {
                if($lines) {
                    \cli\out("\033[{$lines}A"); //Restore Cursor Position
                }
                $lines = 0;
                foreach ($results as $label => $blogs) {
                    foreach ($blogs as $id => $store) {
                        $lines++;
                        ob_start();
                        include($result_template);
                        \cli\line( trim(ob_get_clean()) );
                    }
                }

            } else {
                ob_start();
                include($result_template);
                \cli\line( trim(ob_get_clean()) );
                update_option(Config::IS_SYNCED, 1);
            }
        } while ($finish);
        exit(0);
    }
}
