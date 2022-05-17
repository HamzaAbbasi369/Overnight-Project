<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
if ($total):
    if ($store['synced']):
        $percents = round($store['synced'] / $store['total'] * 100);
        //echo str_repeat("\x08", strlen($percents));
        if ($percents >= 100) :
            echo "Finished ";
        endif;

        echo "Synchronizing {$label} {$store['synced']} / {$store['total']} ({$percents}%)";

    endif;
else:
    echo "Finished execution.";
endif;