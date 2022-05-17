<?php
use OngStore\Core\Helper\Config;
$is_synced = get_option(Config::IS_SYNCED, 0);
?>
<div class="notice-<?=($is_synced) ? 'info' : 'warning'?> notice">
    <?php if ($is_synced) : ?>
        <p><b>ONG instant Filter</b> already synced, but you always can
        <a href="<?php echo esc_url(menu_page_url('ong_store_sync', false)); ?>">Run Data
             Synchronization</a> one more time.
        </p>
    <?php else :?>
        <p>To start using <b>ONG instant Filter</b> plugin, please
            <a href="<?php echo esc_url(menu_page_url('ong_store_sync', false)); ?>">Run Data
        Synchronization</a></p>
    <?php endif; ?>
</div>