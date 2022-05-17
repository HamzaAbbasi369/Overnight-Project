<?php

/**
 * retargeting
 * Date: 27.04.18
 * vendor/bin/wp ong retargeting sendemail --path=wp
 */

if (defined('WP_CLI') && WP_CLI) {
    try {

        WP_CLI::add_command('ong retargeting', 'ONG_Addon_Retargeting_CLI_Commands');
    } catch (Exception $e) {
        //do nothing
    }
}


class ongRetargeting
{
    /**
     * @var int
     * number of letters sent at one time
     */
    private static $daysAgo = 365;
    private static $limit = 100;

    public static function addInRetargetingExistingCustomers()
    {
        $res = self::selectUsers(self::$daysAgo);
        foreach ($res as $item) {
            if (self::sendEmailOrder($item)) {
                self::entryDatabase($item);
            };
        }
    }

    private static function selectUsers($daysAgo)
    {
        global $wpdb;
        $limit = self::$limit;
        $res = $wpdb->get_results(<<<SQL
            SELECT
              pmf.meta_value AS firstName,
              pml.meta_value AS lastName,
              pm.meta_value AS email,
              pm1.meta_value AS idUser,
              p.post_date AS date,
              p.ID AS idPost
            FROM wp_posts p
              JOIN wp_postmeta pm ON ( p.ID = pm.post_id AND pm.meta_key = '_billing_email')
              JOIN wp_postmeta pmf ON ( p.ID = pmf.post_id AND pmf.meta_key = '_billing_first_name')
              JOIN wp_postmeta pml ON ( p.ID = pml.post_id AND pml.meta_key = '_billing_last_name')
              LEFT JOIN wp_postmeta pm1 ON ( p.ID = pm1.post_id AND pm1.meta_key = '_customer_user')
            WHERE p.post_type = 'shop_order'
                  AND p.post_status = 'wc-completed'
                  AND (TO_DAYS(NOW()) - TO_DAYS(p.post_date) = {$daysAgo})
                  AND (pm1.meta_value != 0)
            GROUP BY 1, 2, 3, 4, 5, 6
            HAVING
              email not in (SELECT cc.email FROM rx_retargeting cc)
            ORDER BY 5 DESC
            LIMIT {$limit};
SQL
            , ARRAY_A);
        return $res;
    }



    private static function sendEmailOrder($item)
    {
        $email = $item['email'];

        if (isset($item['firstName'])) {
            $name = 'Dear ' . $item['firstName'] . ' ' . $item['lastName'];
        } else {
            $name = 'Dear Customer';
        }

        include dirname(__FILE__) . '/template/email_retargeting.php';

        /** @var string $subject */
        /** @var string $message */
        /** @var string $headers */
      $result = wp_mail($email, $subject, $message, $headers);
        return $result;
    }

    private static function entryDatabase($item)
    {
        global $wpdb;

        $sql = $wpdb->prepare(
            "INSERT INTO rx_retargeting (id_user, id_post, email, date) VALUES (%d, %d, %s, %s)",
            $item['idUser'],
            $item['idPost'],
            $item['email'],
            date('Y-m-d H:i:s')
        );

        $wpdb->query($sql);
    }
}
