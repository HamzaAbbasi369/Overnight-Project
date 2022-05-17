<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
if ($total): ?>
    <?php if ($store['synced']): ?>
        <?php
        $percents = round($store['synced'] / $store['total'] * 100);
        ?>
        <li style="position: relative; ">
            <div class="sync-loader" style="width: <?php echo $percents; ?>%;"></div>
            <?php if ($percents >= 100) :?>
            Finished
            <?php endif; ?>
            Synchronizing <?php echo $label; ?> <?php echo $store['synced'] ?> / <?php echo $store['total'] ?>
            (<?php echo $percents; ?>%)
        </li>
    <?php endif; ?>
<?php else: ?>
    <div class="notice notice-success"><p><?php echo __("Finished execution."); ?></p></div>
<?php endif; ?>