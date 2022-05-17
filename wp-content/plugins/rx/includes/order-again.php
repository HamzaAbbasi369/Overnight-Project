<?php
/**
 * rx
 *
 * @author     Eugene Odokiienko <eugene@overnightglasses.com>
 * @copyright  Copyright (c) 2018 Vision Care Services LLC. (http://www.overnightglasses.com)
 */

add_filter( 'woocommerce_order_again_cart_item_data', 'rx_order_again_cart_item_data', 10, 3 );
/**
 * @param $cart_item_data
 * @param $item
 * @param WC_Order $order
 *
 * @return mixed
 * @author Eugene Odokiienko <eugene@overnightglasses.com>
 */
function rx_order_again_cart_item_data($cart_item_data, $item, $order) {
    
    if (!defined('WC_RX_CART_ITEM_DATA_ORDER_ITEM_ATTRIBUTES')) {
        return $cart_item_data;
    }
    

    foreach (WC_RX_CART_ITEM_DATA_ORDER_ITEM_ATTRIBUTES as $cart_item_data_key => $order_item_meta_key) {

        
        if (isset($item['item_meta'][$order_item_meta_key] )) {
            $value = is_array( $item['item_meta'][$order_item_meta_key] )  ? $item['item_meta'][$order_item_meta_key][0] : $item['item_meta'][$order_item_meta_key];
            $value =  maybe_unserialize( $value );
            if ($cart_item_data_key=="_all_lens_data") {
                $total_price=$value["total_price"];
               
            }
           
            if ($cart_item_data_key=="wdm_package_price_value") {
                $cart_item_data[$cart_item_data_key]=isset($total_price)? $total_price : $value ;
            } else {
                $cart_item_data[$cart_item_data_key] = $value;
            }
        }
    }

    $cart_item_data['_old_order_number'] =  $order->get_order_number();
    if(isset($_GET['order_again']) && $_GET['order_again'] != ''){
        foreach ($item->get_data() as $key => $value){ 
            $cart_item_data[$value[6]->key] = $value[6]->value;
        }
    }
    
    return $cart_item_data;
}
if (array_key_exists('order_again', $_GET)) {
    add_filter( 'woocommerce_add_to_cart_validation', 'rx_order_again_add_to_cart_validation', 10, 6 );
    function rx_order_again_add_to_cart_validation ($result, $product_id, $quantity, $variation_id, $variations, $cart_item_data = null) {
        

        if (!defined('WC_RX_CART_ITEM_DATA_ORDER_ITEM_ATTRIBUTES') || !isset($cart_item_data)) {
            return $result;
        }
        
        if (array_key_exists('_old_order_number', $cart_item_data)) {
            $olderOrderNumber=  $cart_item_data['_old_order_number'];
            $order = new WC_Order($olderOrderNumber);
            $order_date = $order->order_date;
            $todayDate=date('Y-m-d');
            $d1 = new DateTime($todayDate);
            $d2 = new DateTime($order_date);
            $interval = $d1->diff($d2);
            $preOrderDays = $interval->days;
            foreach (WC_RX_CART_ITEM_DATA_ORDER_ITEM_ATTRIBUTES as $cart_item_data_key => $order_item_meta_key) {
                //if (!isset($cart_item_data[$cart_item_data_key] )) {
                if($preOrderDays > 730){    
                    $product = wc_get_product($product_id);
                    wc_add_notice( sprintf(__( 'Item %s is too old and cannot be reordered. Please place a new order to assure Rx accuracy.', 'woocommerce' ), $product->get_title()), 'error' );
                    $result = false;
                    break;
                }
            }
        }

        return $result;
    }
}

