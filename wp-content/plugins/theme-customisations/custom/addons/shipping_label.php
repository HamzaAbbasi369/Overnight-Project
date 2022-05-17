<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


class viewLabel
{
    public function __construct()
    {
        $this->initHooks();
    }

    private function initHooks()
    {
        add_action('woocommerce_thankyou', [$this, 'display_your_frame_text'], 5);
	add_action('woocommerce_thankyou', [$this, 'send_frame_instruction_email'], 5);
        add_action('woocommerce_thankyou', [$this, 'draw_button'], 5);
        add_action('add_meta_boxes', [$this, 'show_meta_box'], 5);
        add_action('woocommerce_my_account_my_orders_actions', [$this, 'show_meta_box_my_account_my_orders'], 5, 2);

        add_action('init', function () {
            add_rewrite_rule(
                'shipping-label-template.php$',
                'index.php?shipping-label',
                'top'
            );
        });

        add_filter('need_display_shipping_label', function (bool $result, $order_id) {
            if ($this->has_your_frames_item($order_id)) {
                $result = true;
            }
            return $result;
        }, 10, 2);

        add_filter('query_vars', function ($query_vars) {
            $query_vars[] = 'shipping-label';
            return $query_vars;
        });

        add_action('parse_request', [$this, 'drow_shiping_lable']);
    }

    function drow_shiping_lable(&$wp) {
        if (array_key_exists('shipping-label', $wp->query_vars)) {
            $label_data = $this->show_template();
            include 'shipping-label-template.php';
            exit();
        }
        return;
    }


    public function show_template(){
        global $wp;

        $order = wc_get_order(wc_get_order_id_by_order_key(wc_clean($wp->query_vars['shipping-label'])));
        $label_data = [];
        $_label = get_post_meta($order->get_id(), '_label', true);

        if(empty($wp->query_vars['shipping-label'])){
            return;
        }

        if(empty($_label)){
            viewLabel::ong_add_label_rocket_ship_it($order->get_id());
        }

        $_label_errors = get_post_meta($order->get_id(), '_label_errors', true);

        if(empty($_label_errors)){

            $_label = get_post_meta($order->get_id(), '_label', true);
            $_tracking_number = get_post_meta($order->get_id(), '_tracking_number', true);
            $_label_format = get_post_meta($order->get_id(), '_label_format', true);
            $_carrier= get_post_meta($order->get_id(), '_carrier', true);

            if ($_tracking_number !== false &&  $_label !== false) {
                if($_label_format === 'Png' ){

                    $label_data['_label_message'] = "<button style='width: 35%; display: inline-block; float: left; margin-right: 25px; margin-bottom: 20px'class='btn-shop-frames' id='printBut'>Print shipping label</button>
                    <img src='data:image/$_label_format;base64, $_label' alt='shipping-label'/>

                    <script>
                        window.onload = function () {
                            var printBut = document.getElementById('printBut');
                            printBut.onclick = function () {
                                var win = window.open();
                                win.document.write('<img src=\'data:image/$_label_format;base64, $_label\' alt=\'shipping-label\' />');
                                win.print();
                                win.close()
                            }
                        }
                    </script>";

                } else {
                    $label_data['_label_message'] = "<iframe src='data:application/pdf;base64, $_label' frameborder='1' width='800px' height='500px'></iframe>";
                }
                $label_data['_carrier']=$_carrier;
            } else {
                $label_data['_label_message'] = "<h2>Shipping label was not generated for this order. Please contact <a href='mailto:support@overnightglasses.com'>support@overnightglasses.com</a></h2>";
            }

        } else {
            $label_data['_label_message'] = "<h3>Shipping label was not generated for this order. Please contact <a href='mailto:support@overnightglasses.com'>support@overnightglasses.com</a></h3>";

            $this->send_email_support($order->get_id(), $_label_errors);
        }

        return $label_data;
    }

    public function send_email_support($order, $_label_errors){
        $headers = 'From: Error while creating Shipping Label' . "\r\n";
            $message = '<ul>';
            foreach ($_label_errors as $key => $value) {
                $key++;
                $message .= "<h3>List Error $key. Order <a href='http://overnightglasses.com/my-account/view-order/$order'>$order</a></h3>";
                foreach ($value as $val) {
                    if($val === ''){
                        $val = 'Code Error'. $val;
                    }
                $message .= '<li>'.$val.'</li>';
                }
            }
        $message .= '</ul>';
        $email = get_option('admin_email');
        wp_mail($email, 'Error while creating Shipping Label', $message, $headers);
    }

    public function show_meta_box_my_account_my_orders($actions, $order)
    {
        if (apply_filters('need_display_shipping_label', false, $order->get_id())) {
            $actions['label'] = [
                'url' => "/index.php?shipping-label=" . $order->get_order_key(),
                'name' => __('Shipping label', 'woocommerce')
            ];
        }

        return $actions;
    }

    public function display_your_frame_text($order_id)
    {
        if ($this->has_your_frames_item($order_id)) {
            ?>
            <h2 class="thankyou-order-wrap">You've ordered "Use Your Frame" option.</h2>
            <br>
            <?php
        }
    }

    public function send_frame_instruction_email($order_id)
    {
	if ($this->has_your_frames_item($order_id)) {
		$order = new WC_Order($order_id);

		// check shipment name on the order
		$shipping_name = getShippingName($order);
		

		$subject = "Overnight Glasses, Send Your Frame Shipping Instructions Order #".$order->ID;

		if (strpos($shipping_name, 'UPS') !== false) {
			$message = "Thank you for selecting Overnight Glasses Lens Replacement Service.<br/><br/>Please print your <a href='".get_site_url()."/index.php?shipping-label=".$order->get_order_key()."'>Shipping Label</a><br/>Additional instructions for mailing your package:<br/><br/>Ensure that there are no other tracking labels attached to your package.<br/><br/>Affix the mailing label squarely onto the address side of the parcel, covering up any previous delivery address and barcode without overlapping any adjacent side.<br/><br/>Make sure to pack your frame in hard glasses case and with enough cushion to avoid any damages during shipping.<br/><br/>Take this package to a UPS location. To find your closest UPS location, visit the <a href='https://www.ups.com/dropoff'>UPS Drop Off Locator</a> or go to <a href='https://www.ups.com'>www.ups.com</a>.<br/><br/>An email notification will be sent when your frame entered production and coating.<br/><br/><br/>Thank you for being a great customer and giving us the chance to make your new lenses.<br/>The Overnight Glasses Team.";
		} else {
			$message = "Thank you for selecting Overnight Glasses Lens Replacement Service.<br/><br/>Please print your <a href='".get_site_url()."/index.php?shipping-label=".$order->get_order_key()."'>Shipping Label</a><br/>Additional instructions for mailing your package:<br/><br/>Ensure that there are no other tracking labels attached to your package.<br/><br/>Affix the mailing label squarely onto the address side of the parcel, covering up any previous delivery address and barcode without overlapping any adjacent side.<br/><br/>Make sure to pack your frame in hard glasses case and with enough cushion to avoid any damages during shipping.<br/><br/>Take this package to a USPS location. to find your closest USPS location, <a href='https://tools.usps.com/go/POLocatorAction!input.action'>click here</a><br/><br/>An email notification will be sent when your frame entered production and coating.<br/><br/><br/>Thank you for being a great customer and giving us the chance to make your new lenses.<br/>The Overnight Glasses Team.";
                }

		//send email
		$heading = 'heading';
		$mailer = WC()->mailer();
		$wrapped_message = $mailer->wrap_message($heading, $message);
		$wc_email = new WC_Email;
		$html_message = $wc_email->style_inline($wrapped_message);
		// Send the email using wordpress mail function
		wp_mail( $order->get_billing_email(), $subject, $html_message,  array('Content-Type: text/html; charset=UTF-8'));
	}

    }


    public function has_your_frames_item($order_id)
    {
        $order = new WC_Order($order_id);
        $result = false;
        foreach ($order->get_items() as $item) {
            if ($item['name'] == "Your Frames") {
                $result = true;
            }
        }
        return $result;
    }


    public function show_meta_box()
    {
        global $post;

        if (!$post) {
            return false;
        }

        if (in_array($post->post_type, ['shop_order'])) {
            if (apply_filters('need_display_shipping_label', false, $post->ID)) {
                add_action('add_meta_boxes', function () {
                    $screens = ['shop_order', 'side', 'default'];

                    add_meta_box('view_shipping_label', 'View shipping label', function ($post) {
                        $order = new WC_Order($post->ID);
                        $order_key = $order->get_order_key();
                        ?>

                        <a href="/index.php?shipping-label=<?= $order_key ?>"
                           class="button button-primary tips ">View shipping label</a>

                        <?php
                    }, $screens);
                });
            }
        }
    }

    /**
     * @param $order_id
     */
    public function draw_button($order_id)
    {
        if (apply_filters('need_display_shipping_label', false, $order_id)) {
            ?>
            <h2 class="thankyou-order-wrap">Please print shipping label<br><br>
            </h2>
            <a href="/index.php?shipping-label=<?= $_GET['key'] ?>" style="width: 15%; display: inline-block;"
               class="button btn-shop-frames">View and Print shipping label</a>
            <?php
        }
    }


    public static function ong_add_label_rocket_ship_it($order_id)
    {
        if (get_post_meta($order_id, '_label', true)) {
            return false;
        }


        $order = new WC_Order($order_id);
        require_once WP_CONTENT_DIR . '/../scripts/rocketshipit2-linux-amd64/clients/php/RocketShipIt.php';
        $rs = new RocketShipIt();

        $rs->apiKey = 'FcuE96o4lX1W2WdBY8Oht1xvePibeEMP9trRZ6VS';
//        $rs->options = ['http_endpoint' => 'http://localhost:8080/api/v1/'];
        $rs->options = ['http_endpoint' => 'https://api.rocketship.it/v1/'];
       $ship_rush=false;
        foreach( $order->get_items( 'shipping' ) as $item_id => $shipping_item_obj ){
            $shipping_method_title     = $shipping_item_obj->get_method_title();
            if ($shipping_method_title=='UPS Next Day Air (1 Day)') {
                $ship_rush=true;
            }
        }
        if($ship_rush) {
            $shipping_service='14';
            $carrier='UPS';
            $packages=[
                [
                    "weight" => 0.5,
                    "length" => 7,
                ]
            ];
            $service_account=[
                "account_number"=>"A00V53",
                "key" => "ED49A49B6CD9EE7C",
                "username" => "gidon",
                "password" => "4IGlasse$"
            ];
        } else {
            $shipping_service = 'US-FC';
            $carrier='stamps';
            $packages=[
                [
                    "weight_unit" => 'LBS',
                    "weight" => 0.5,
                    "packaging_type" => "Package",
                ]
            ];
            $service_account=[
                "username" => "gidon@overnigh",
                "password" => ",.Ong23885"
            ];
        }

        $shipment=[
            "packages" => $packages,
            "shipper" => $order->get_shipping_first_name() .' '.$order->get_shipping_last_name(),
            "ship_addr1" => $order->get_shipping_address_1(),
            "ship_city" => $order->get_shipping_city(),
            "ship_state" => $order->get_shipping_state(),
            "ship_code" => substr($order->get_shipping_postcode(),0,5),
            "ship_phone" => $order->get_billing_phone(),
            "ship_country" => "US",
            "to_name" => "Overnight Glasses",
            "to_addr1" => "16430 Vanowen st",
            "to_state" => "CA",
            "to_city" => "Van Nuys",
            "to_code" => "91406",
            "to_country" => "US",
            "to_phone"=>"18558303339",
            "to_attention_name"=> "Rush Processing #".$order->get_id(),
            'image_type' => 'PDF',
            "test" => false,
            "service" => $shipping_service, //US-FC:First Class. US-XM Express Mail US-PM priority mail 01 UPS 1 Day Air
            "reference_value" => "Your Frames. #".$order->get_id()
        ];
        $params=array_merge($service_account,$shipment);
        $resp = $rs->request([
            "carrier" => $carrier,
            "action" => "SubmitShipment",
            "params" => $params
        ]);

//        "ship_code" => '91502',

        list($label_errors, $label_format, $tracking_number, $label) = self::cheсkData($resp);
        self::addPostMetaLabel($label_errors, $order->get_id(), $tracking_number, $label_format, $label,$carrier);
        return $order_id;
    }

    /**
     * @param $label_errors
     * @param $orderId
     * @param $tracking_number
     * @param $label_format
     * @param $label
     */
    public static function addPostMetaLabel($label_errors, $orderId, $tracking_number, $label_format, $label,$carrier)
    {
        add_post_meta($orderId, '_label_errors', $label_errors, true)
        or
        update_post_meta($orderId, '_label_errors', $label_errors);

        add_post_meta($orderId, '_tracking_number', $tracking_number, true)
        or
        update_post_meta($orderId, '_tracking_number', $tracking_number);

        add_post_meta($orderId, '_label_format', $label_format, true)
        or
        update_post_meta($orderId, '_label_format', $label_format);

        add_post_meta($orderId, '_label', $label, true)
        or
        update_post_meta($orderId, '_label', $label);

        add_post_meta($orderId, '_carrier', $carrier, true)
        or
        update_post_meta($orderId, '_carrier', $carrier);
    }

    /**
     * @param $resp
     * @return array
     */
    public static function cheсkData($resp): array
    {
        $label_errors = !empty($resp['data']['errors']) ? $resp['data']['errors'] : null;

        $label_format = !empty($resp['data']['packages'][0]['label_format'])
            ?
            $resp['data']['packages'][0]['label_format'] : null;

        $tracking_number = !empty($resp['data']['packages'][0]['tracking_number'])
            ?
            $resp['data']['packages'][0]['tracking_number'] : null;

        $label = !empty($resp['data']['packages'][0]['label'])
            ?
            $resp['data']['packages'][0]['label'] : null;

        return array($label_errors, $label_format, $tracking_number, $label);
    }
}

new viewLabel();
