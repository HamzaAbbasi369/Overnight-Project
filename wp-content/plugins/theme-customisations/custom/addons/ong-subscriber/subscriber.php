<?php
/**
 * ONG Subscriber And UnSubscriber
 * configure SMTP. Such as plugin WP-Mail-SMTP
*/

add_action('init', 'add_ajax_rx_sub');
add_action('wp_enqueue_scripts', 'rx_sub_scripts');
register_activation_hook(__FILE__, 'sub_create_table');
add_shortcode('add_sub_rx_ong', 'add_sub_func_rx_ong');
add_shortcode('add_page_rx_ong_unsubscribe', 'add_page_rx_ong_unsubscribe');

function add_ajax_rx_sub()
{
    add_action('wp_ajax_sub_ajax_action', 'ajax_rx_sub');
    add_action('wp_ajax_nopriv_sub_ajax_action', 'ajax_rx_sub');

    add_action('wp_ajax_rx_sub_unsubscribe', 'rx_sub_unsub');
    add_action('wp_ajax_nopriv_rx_sub_unsubscribe', 'rx_sub_unsub');
}

function ajax_rx_sub()
{
    if (!wp_verify_nonce($_POST['ajax_rx_sub_nonce'], 'hfgdv')) {
        wp_die('Error security!');
    }

    if (empty($_POST['ajax_rx_sub_formName']) || empty($_POST['ajax_rx_sub_formEmail'])) {
        wp_die('Fill out the field!');
    }

    if (!is_email($_POST['ajax_rx_sub_formEmail'])) {
        wp_die('Email does not match the input format!');
    }

    $name = esc_html($_POST['ajax_rx_sub_formName']);
    $email = $_POST['ajax_rx_sub_formEmail'];

    global $wpdb;
    $nonce = esc_html($_POST['ajax_rx_sub_nonce']);
    if ($wpdb->get_var($wpdb->prepare("SELECT email FROM rx_subscribe WHERE email = %s", $email))) {
        $wpdb->update(
            'rx_subscribe',
            [
                'activity' => 2, //already subscribed!
                'data_change' => date('Y-m-d H:i:s')
            ],
            [
                'email' => $email
            ]
        );
        die('You are already subscribed!');
    } else {
        if ($wpdb->insert('rx_subscribe', [
            'name' => $name,
            'email' => $email,
            'nonce' => $nonce,
            'data' => date('Y-m-d H:i:s')])) {
            include dirname(__FILE__) . '/template/email_after_subscription.php';

            /** @var string $message */
            /** @var string $email */
            /** @var string $subject */
            /** @var string $headers */
            if (wp_mail($email, $subject, $message, $headers)) {
                die('Subscribed!');
            } else {
                wp_die('Error subscribed!');
            }
        } else {
            wp_die('Write error!');
        }
    }
    wp_die();
}

function rx_sub_scripts()
{
    wp_register_script('sub_scripts', plugins_url('sub.js', __FILE__), ['jquery'],false,true);
    wp_enqueue_script('sub_scripts');

    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $params_user = [
            'Email' => $current_user->user_email,
            'Name' => $current_user->display_name
        ];
    } else {
        $params_user = [
            'Email' => '',
            'Name' => ''
        ];
    }

    wp_localize_script('sub_scripts', 'sub_ajax', [
        'url'=>admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('hfgdv'),
        'user' => $params_user
    ]);
}

function rx_sub_unsub()
{
    global $wpdb;
    $text = esc_html($_POST['ajax_rx_un_sub_formText']);
    $email = $_POST['ajax_rx_un_sub_formEmail'];
    $nonce = $wpdb->get_var($wpdb->prepare("SELECT `nonce` FROM rx_subscribe WHERE email = %s", $email));

    // http://wordpress.local/wp-admin/admin-ajax.php?action=rx_sub_unsubscribe&email=xxxxx&data=xxxxx
    if ($_POST['ajax_rx_un_sub_formNonce'] != $nonce) {
        wp_die('Error security!');
    }

    if ($wpdb->get_var($wpdb->prepare("SELECT email FROM rx_subscribe WHERE email = %s AND activity = '1'", $email))) {
        $wpdb->update(
            'rx_subscribe',
            [
                'activity' => 0,
                'unsubscribe_reason' => $text,
                'data_change' => date('Y-m-d H:i:s')
            ],
            [
                'email' => $email
            ]
        );
        wp_die('You unsubscribe');
    } else {
        wp_die('Error unsubscribe!');
    }
}

function sub_create_table()
{
    global $wpdb;
    $query = "CREATE TABLE IF NOT EXISTS `rx_subscribe` (
		`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`name` varchar(50) NOT NULL,
		`email` varchar(50) NOT NULL,
		`data` varchar(50) NOT NULL,
		`data_change` varchar(50) NOT NULL,
		`unsubscribe_reason` text NOT NULL,
		`activity` enum('0','1','2') NOT NULL DEFAULT '1',
		`nonce` varchar(50) NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $wpdb->query($query);

//    create page
    $new_page_title = 'Unsubscribe';
    $new_page_content = '[add_page_rx_ong_unsubscribe]';
    $new_page_template = '';

    $page_check = get_page_by_title($new_page_title);
    $new_page = array(
        'post_type' => 'page',
        'post_title' => $new_page_title,
        'post_content' => $new_page_content,
        'post_status' => 'publish',
        'post_author' => 1,
    );
    if (!isset($page_check->ID)) {
        $new_page_id = wp_insert_post($new_page);
        if (!empty($new_page_template)) {
            update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
        }
    }
}

function add_sub_func_rx_ong()
{
    include dirname(__FILE__) . '/template/rx_sub_form.php';
}

function add_page_rx_ong_unsubscribe()
{
    include dirname(__FILE__) . '/template/rx_sub_unsubscribe_form.php';
}