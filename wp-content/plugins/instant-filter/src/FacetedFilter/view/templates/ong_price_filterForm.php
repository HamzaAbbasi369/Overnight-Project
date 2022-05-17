<li>
    <div class="ong_price_filter">
        <div class="price_slider_wrapper">
            <div class="price_slider" style="display:none;"></div>
            <div class="price_slider_amount">
                <input
                   type="text"
                   id="min_price"
                   name="min_price"
                   value="<?=esc_attr($min_price)?>"
                   data-min="<?=esc_attr(apply_filters('woocommerce_price_filter_widget_min_amount', $min))?>"
                   placeholder="<?=esc_attr__('Min price', 'woocommerce')?>" />
                <input
                   type="text"
                   id="max_price"
                   name="max_price"
                   value="<?=esc_attr($max_price)?>"
                   data-max="<?=esc_attr(apply_filters('woocommerce_price_filter_widget_max_amount', $max))?>"
                   placeholder="<?=esc_attr__('Max price', 'woocommerce')?>" />
                <div class="price_label" style="display:none;">
                    <?= __('Price:', 'woocommerce') ?> <span class="from"></span> &mdash; <span class="to"></span>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</li>
