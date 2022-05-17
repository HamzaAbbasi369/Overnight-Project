<?php

/**
 * Club:enroll existing customers
 * Date: 06.11.17
 * vendor/bin/wp ong club addoldcustomers --path=wp
 */

class Ong_Addon_Club_Add_In_Club_Existing_Customers
{
    /**
     * @var int
     * number of letters sent at one time
     */
    private static $limit = 10;

    public static function addInClubExistingCustomers()
    {
        $res = self::selectUsers(self::$limit);

        foreach ($res as $item) {
            $coupon_code = Ong_String_Helper::randGenerate(6);
            $idCoupon = self::addCouponOrder($coupon_code);
            self::sendEmailOrder($item, $coupon_code);
            self::entryDatabaseOrder($idCoupon, $item);
        }
    }

    private static function selectUsers($limit)
    {
        global $wpdb;
        $res = $wpdb->get_results(<<<SQL

            SELECT
              pmf.meta_value AS firstName,
              pml.meta_value AS lastName,
              pm.meta_value AS email,
              pm1.meta_value AS idUser,
              min(p.ID) AS id
                FROM wp_posts p
                  JOIN wp_postmeta pm ON ( p.ID = pm.post_id AND pm.meta_key = '_billing_email')
                  JOIN wp_postmeta pmf ON ( p.ID = pmf.post_id AND pmf.meta_key = '_billing_first_name')
                  JOIN wp_postmeta pml ON ( p.ID = pml.post_id AND pml.meta_key = '_billing_last_name')
                  LEFT JOIN wp_postmeta pm1 ON ( p.ID = pm1.post_id AND pm1.meta_key = '_customer_user')
            WHERE p.post_type = 'shop_order'
            AND p.post_status = 'wc-completed'
            GROUP BY 1, 2, 3, 4
            HAVING
              email not in (SELECT cc.email FROM coupon_customer cc)
            ORDER BY 5 DESC
            LIMIT {$limit};
SQL
            , ARRAY_A);
        return $res;
    }

    private static function addCouponOrder($coupon_code)
    {
        if (!function_exists('duplicate_post_create_duplicate')) {
            return;
        }

        $args = [
            'name' => ONG_CLUB_COUPON,
            'post_type' => 'shop_coupon',
        ];

        $wp_query = new WP_Query($args);

        if (empty($wp_query)) {
            return;
        }

        $new_coupon_id = duplicate_post_create_duplicate($wp_query->posts[0]);

        $coupon = [
            'ID' => $new_coupon_id,
            'post_title' => $coupon_code,
            'post_name' => $coupon_code,
            'post_status' => 'publish'
        ];

        $new_coupon_id = wp_update_post($coupon);

        return $new_coupon_id;
    }

    private static function sendEmailOrder($item, $coupon_code)
    {
        $email = $item['email'];

        if (isset($item['firstName'])) {
            $name = 'Dear ' . $item['firstName'] . ' ' . $item['lastName'];
        } else {
            $name = 'Dear Customer';
        }

        $coupon['post_title'] = $coupon_code;
        $read_online = [];
        $read_online['name'] = $name;
        $read_online['coupon'] = $coupon_code;
        $read_online = serialize($read_online);
        $read_online = base64_encode(Ong_String_Helper::sslEnc($read_online));

        include dirname(__FILE__) . '/template/email_after_first_coupon.php';

        /** @var string $subject */
        /** @var string $message */
        /** @var string $headers */

        wp_mail($email, $subject, $message, $headers);
    }

    private static function entryDatabaseOrder($coupon_id, $order)
    {
        global $wpdb;

        $sql = $wpdb->prepare(
            "INSERT INTO coupon_customer (order_id, coupon_id, email, type, data) VALUES (%d, %d, %s, %s, %s)",
            $order['id'],
            $coupon_id,
            $order['email'],
            'existing-customers',
            date('Y-m-d H:i:s')
        );

        $wpdb->query($sql);
    }
}
