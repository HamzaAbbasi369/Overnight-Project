<?php
/**
 * Created by PhpStorm.
 * Date: 31.10.17
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define("ONG_CLUB_COUPON", "ongclub");

if (defined('WP_CLI') && WP_CLI) {
    try {
        WP_CLI::add_command('ong club', 'ONG_Addon_Club_CLI_Commands');
    } catch (Exception $e) {
        //do nothing
    }
}

class ongClub
{
    public $lengthGeneratedCouponName = 6;

    public function __construct()
    {
        $this->initHooks();
    }

    private function initHooks()
    {
        add_action('init', function () {
            add_rewrite_endpoint('club', EP_PAGES);
        });
        add_action('init', function () {
            add_rewrite_rule(
                '^ong-club/([^/]*)/?',
                'index.php?pagename=ong-club&coupon=$matches[1]',
                'top'
            );
            add_filter('query_vars', function ($vars) {
                $vars[] = 'coupon';
                return $vars;
            });
        });
        add_action('woocommerce_account_menu_items', [$this, 'iconicAccountMenuItems'], 10, 1);
        add_action('woocommerce_account_club_endpoint', [$this, 'clubEndpointContent']);
        add_action('woocommerce_order_status_completed', [$this, 'firstOrderStatusCompleted']);
        add_action('user_register', function ($user_id) {
            wc_update_new_customer_past_orders($user_id);
        });
        add_action('first_order_completed', [$this, 'addCouponFirstOrder'], 10);
        add_action('first_order_coupon_created', [$this, 'sendEmailFirstOrder'], 20, 3);
        add_action('first_order_coupon_created', [$this, 'entryDatabaseFirstOrder'], 10, 3);
    }

    public function iconicAccountMenuItems($items)
    {
        $items['club'] = __('Overnight Glasses Club');
        return $items;
    }

    public function clubEndpointContent()
    {
        global $wpdb;

        $cur_user_id = get_current_user_id();

        $result = $wpdb->get_row(
            "SELECT c.coupon_id
                    FROM coupon_customer c
                      JOIN `billing_emails` b ON ( b.id_user = $cur_user_id)
                    WHERE c.email = b.email"
        );

        if (is_null($result)) {
            echo "<h2 class=\"address-title-column\">You do not yet participate in the program Overnight Glasses Club.</h2>";
        } else {
            $post = $wpdb->get_row("
            SELECT post_title FROM wp_posts 
            WHERE ID = $result->coupon_id
            AND post_type = 'shop_coupon'
            ");

            $count = $wpdb->get_row("
            SELECT meta_value from wp_postmeta
            WHERE post_id = {$result->coupon_id} 
            AND  meta_key = 'usage_count'
            ");

            $CountNumberCompleted = $this->countNumberCompleted($post->post_title);
            $RewardsAccrued = round($this->rewardsAccrued($post->post_title), 2);

            if ($CountNumberCompleted !== 0) {
                $PendingOrders = $count->meta_value - $CountNumberCompleted;
            } else {
                $PendingOrders = 0;
            }

            if ($result->coupon_id) {
                include dirname(__FILE__) . '/template/ong_club_content.php';
            }
        }
    }

    public function firstOrderStatusCompleted($order_id)
    {
        $order = new WC_Order($order_id);
        $email = $order->billing_email;
        $user_id = get_current_user_id();
        if ($this->isFirstOrder($email)) {
            do_action('first_order_completed', $order);
            $this->addTableCouponCustomer($user_id, $email);
        }
    }

    public function addTableCouponCustomer($user_id, $email){
        global $wpdb;

        $sql = $wpdb->prepare(
            "INSERT INTO `billing_emails` (email, id_user, date) VALUES (%s, %d, now()) ON DUPLICATE KEY UPDATE date = VALUES(date)",
            $email,
            $user_id
        );

        $wpdb->query($sql);
    }

    public function isFirstOrder($email)
    {
        $orders = wc_get_orders(
            [
                'status' => ['wc-completed'],
                'customer' => $email,
            ]
        );

        if (count($orders) !== 1) {
            return false;
        } else {
            return true;
        }
    }

    public function countNumberCompleted($coupon_name)
    {
        global $wpdb;
        $count_completed = $wpdb->get_col("
            SELECT order_id from wp_woocommerce_order_items cc
            JOIN wp_posts p on (cc.order_id  = p.ID and p.post_type = 'shop_order' and p.post_status = 'wc-completed')
            WHERE order_item_name = '{$coupon_name}'
            AND  order_item_type = 'coupon'
    ");
        return count($count_completed);
    }

    public function rewardsAccrued($couponName)
    {
        global $wpdb;
        $count = $wpdb->get_col("
            SELECT order_id from wp_woocommerce_order_items o
            JOIN wp_posts p on (o.order_id  = p.ID and p.post_type = 'shop_order' and p.post_status = 'wc-completed')
            WHERE order_item_name = '{$couponName}'
            AND  order_item_type = 'coupon'
    ");

        $sum = '';
        foreach ($count as $val) {
            $sum += get_post_meta($val, '_order_total')[0];
        }
        return $sum * 0.05; //5%
    }

    public function addCouponFirstOrder($order)
    {

        $args = [
            'name' => ONG_CLUB_COUPON,
            'post_type' => 'shop_coupon',
        ];

        $wp_query = new WP_Query($args);

        if (!function_exists('duplicate_post_create_duplicate')) {
            return;
        }

        $new_coupon_id = duplicate_post_create_duplicate($wp_query->posts[0]);
        $coupon_code = Ong_String_Helper::randGenerate($this->lengthGeneratedCouponName);

        $coupon = [
            'ID' => $new_coupon_id,
            'post_title' => $coupon_code,
            'post_name' => $coupon_code
        ];

        $new_coupon_id = wp_update_post($coupon);

        do_action('first_order_coupon_created', $new_coupon_id, $coupon, $order);

        return $new_coupon_id;
    }

    public function sendEmailFirstOrder($coupon_id, $coupon, $order)
    {
        $email = $order->billing_email;

        if (isset($order->billing_last_name)) {
            $name = 'Dear ' . $order->billing_first_name . ' ' . $order->billing_last_name;
        } else {
            $name = 'Dear Customer';
        }

        $read_online = [];
        $read_online['name'] = $name;
        $read_online['coupon'] = $coupon['post_title'];
        $read_online = serialize($read_online);
        $read_online = base64_encode(Ong_String_Helper::sslEnc($read_online));

        include dirname(__FILE__) . '/template/email_after_first_coupon.php';

        /** @var string $email */
        /** @var string $subject */
        /** @var string $message */
        /** @var string $headers */
        /** @var string $coupon_id */

        wp_mail($email, $subject, $message, $headers);
    }

    public function entryDatabaseFirstOrder($coupon_id, $coupon, $order)
    {
        global $wpdb;

        $sql = $wpdb->prepare(
            "INSERT INTO coupon_customer (order_id, coupon_id, coupon, email, type, data) VALUES (%d, %d, %s, %s, %s, %s)",
            $order->get_id(),
            $coupon_id,
            $coupon['post_title'],
            $order->billing_email,
            'first-order',
            date('Y-m-d H:i:s')
        );

        $wpdb->query($sql);
    }
}


add_action('admin_init', function () {
    $args = [
        'name' => ONG_CLUB_COUPON,
        'post_type' => 'shop_coupon',
    ];
    $wp_query = new WP_Query($args);

    if (empty($wp_query->posts)) {
        add_action('admin_notices', function () {
            echo '<div class="error">
                <p>To work <b>Ong Club</b>, you need a coupon with the name <b>ongclub</b></p>
             </div>';
        });
        update_option('ong_club_coupon_exists', 'false');
    } else {
        update_option('ong_club_coupon_exists', 'true');
    }
});

add_action('admin_init', function () {

    if (!function_exists('duplicate_post_create_duplicate')) {
        add_action('admin_notices', function () {
            echo '<div class="error">
                <p>To work <b>Ong Club</b>, you need plugin <b>Duplicate post</b></p>
             </div>';
        });
        update_option('ong_club_plugin_duplicate_post_exists', 'false');
    } else {
        update_option('ong_club_plugin_duplicate_post_exists', 'true');
    }
});

if (get_option('ong_club_coupon_exists') !== 'false' and
    get_option('ong_club_plugin_duplicate_post_exists') !== 'false') :
    new ongClub();
endif;
