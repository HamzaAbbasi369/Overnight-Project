<?php

/**
 * Enhanced Ecommerce for Woo-commerce stores
 * Allows tracking code to be inserted into store pages.
 *
 * @class       Class ONG_Addon_TrackingScripts_Enhanced_Ecommerce_Google_Analytics
 * @author      Jigar Navadiya <jigar@tatvic.com>
 * @author      Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 */
class ONG_Addon_TrackingScripts_Enhanced_Ecommerce_Google_Analytics /*extends WC_Integration*/
{

    /**
     * Init and hook in the integration.
     *
     * @access public
     * @return void
     */
    //set plugin version
    public $ong_ts_eeVer = '1.2.2';

    /*
     * Google Analytics ID
     */
    public $ga_id;

    /*
     * Set Domain Name
     */
    public $ga_Dname = 'auto';

    /*
     *
     */
    public $ga_LC;

    /*
     * Tracking code
     * Adds Universal Analytics Tracking Code (Optional)
     * This feature adds Universal Analytics Tracking Code to your Store.
     * You don't need to enable this if using a 3rd party analytics plugin.

     */
    public $ga_ST = false;

    public $ga_gCkout = false;

    /*
     * Add Code to Track the Login Step of Guest Users (Optional)
     *
     * If you have Guest Check out enable, we recommend you to add this code
     */
    public $ga_gUser;

    /*
     * Add Enhanced Ecommerce Tracking Code
     * This feature adds Enhanced Ecommerce Tracking Code to your Store
     */
    public $ga_eeT;                  //

    /*
     * Add Display Advertising Feature Code (Optional)
     *
     * This feature enables remarketing with Google Analytics & Demographic reports.
     * Adding the code is the first step in a 3 step process.
     * Follow https://support.google.com/analytics/answer/2819948?hl=en to Learn More
     * This feature can only be enabled if you have enabled UA Tracking from our Plugin.
     * If not, you can still manually add the display advertising code by following the instructions
     * from this https://developers.google.com/analytics/devguides/collection/analyticsjs/display-features link
     */
    public $ga_DF = false;
    public $ga_imTh = 6;
//    protected $ong_ts_aga;

    /**
     * ONG_Addon_TrackingScripts_Enhanced_Ecommerce_Google_Analytics constructor.
     */
    public function __construct($ga_id)
    {

        //Set Global Variables
        global $homepage_json_fp, $homepage_json_ATC_link, $homepage_json_rp, $prodpage_json_relProd, $catpage_json,
               $prodpage_json_ATC_link, $catpage_json_ATC_link, $woocommerce;

        //session for product position count
        //session_start removed bcoz it gives warning
        $_SESSION['t_npcnt'] = 0;
        $_SESSION['t_fpcnt'] = 0;

        // Define user set variables -- Always use short names
//        $this->ong_ts_aga  = $this->get_option("ong_ts_aga");

        $this->ga_id = $ga_id;
        $this->ga_LC = get_woocommerce_currency(); //Local Currency yuppi! Got from Back end

        //set local currency variable on all page
        $this->wc_version_compare("
            ong_ts_lc=" . json_encode($this->ga_LC) . ";");


        //Save Changes action for admin settings

        // API Call to LS with e-mail
        // Tracking code
        add_action("wp_head", [$this, "ee_settings"]);

        add_action("woocommerce_thankyou", [$this, "ecommerce_tracking_code"]);
//        add_action("woocommerce_thankyou", [$this, "checkout_step_3_tracking"]);

        // Enhanced Ecommerce product impression hook
//        add_action("wp_footer", [$this, "t_products_impre_clicks"]);

        add_action("woocommerce_after_shop_loop_item", [
            $this,
            "bind_product_metadata"
        ]); //for cat, shop, prod(related),search and home page

        add_action("woocommerce_after_single_product", [$this, "product_detail_view"]);
        add_action("woocommerce_after_cart", [$this, "remove_cart_tracking"]);

        add_action("woocommerce_before_checkout_billing_form", [$this, "checkout_step_1_tracking"]);
        add_action("woocommerce_after_checkout_billing_form", [$this, "checkout_step_2_tracking"]);
        add_action("woocommerce_after_checkout_billing_form", [$this, "checkout_step_3_tracking"]);

        // Event tracking code
        add_action("woocommerce_after_add_to_cart_button", [$this, "add_to_cart"]);

        //Enable display feature code checkbox 
        //add_action("admin_footer", [$this, "admin_check_UA_enabled"]);

        //add version details in footer
//        add_action("wp_footer", [$this, "add_plugin_details"]);

        //Add Dev ID
//        add_action("wp_head", [$this, "add_dev_id"], 1);

        //Advanced Store data Tracking
//        add_action("wp_footer", [$this, "ong_ts_store_meta_data"]);
    }

    /**
     * woocommerce version compare
     *
     * @access public
     * @return void
     */
    function wc_version_compare($codeSnippet)
    {
        global $woocommerce;
        if (version_compare($woocommerce->version, "2.1", ">=")) {
            wc_enqueue_js($codeSnippet);
        } else {
            $woocommerce->add_inline_js($codeSnippet);
        }
    }

//    /**
//     * Get store meta data for trouble shoot
//     * @access public
//     * @return void
//     */
//    function ong_ts_store_meta_data()
//    {
//        //only on home page
//        global $woocommerce;
//        $ong_ts_sMetaData = [];
//
//        $ong_ts_sMetaData = [
//            'ong_ts_wcv' => $woocommerce->version,
//            'ong_ts_wpv' => get_bloginfo('version'),
//            'ong_ts_eev' => $this->ong_ts_eeVer,
//            'ong_ts_cnf' => [
//                't_ee'    => $this->ga_eeT,
//                't_df'    => $this->ga_DF,
//                't_gUser' => $this->ga_gUser,
//                't_UAen'  => $this->ga_ST,
//                't_thr'   => $this->ga_imTh,
//            ]
//        ];
//        $this->wc_version_compare("ong_ts_smd=" . json_encode($ong_ts_sMetaData) . ";");
//    }

    /**
     * Enhanced Ecommerce GA plugin Settings
     *
     * @access public
     * @return void
     */
    function ee_settings()
    {
        global $woocommerce;

        //common validation----start
        if (is_admin() || current_user_can("manage_options") || $this->ga_ST == "no") {
            return;
        }
        $tracking_id = $this->ga_id;

        if (!$tracking_id) {
            return;
        }
        //common validation----end

        if (!empty($this->ga_Dname)) {
            $set_domain_name = esc_js($this->ga_Dname);
        } else {
            $set_domain_name = "auto";
        }

        //add display features
        if ($this->ga_DF) {
            $ga_display_feature_code = 'ga("require", "displayfeatures");';
        } else {
            $ga_display_feature_code = "";
        }

        $tracking_id_escaped = esc_js($tracking_id);
        $code                = <<<JS
        
        var checkoutUrlDefault = "checkout/order-received",
            checkoutUrl = window.location.pathname.substring(1, 24),
            checkoutSearch = window.location.search.slice(1).substring(4),
            siteHostName = window.location.hostname;

        function setCookie(c_name, value, exdays) {
            var exdate = new Date();
            // exdate.setDate(exdate.getDate() + exdays);
            exdate.setDate(exdate.getDate() + 360*10);
            var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
            document.cookie = c_name + "=" + c_value;
            console.log(exdate);
        }
        function getCookie(c_name) {
            var i, x, y, ARRcookies = document.cookie.split(";");
            for (i = 0; i < ARRcookies.length; i++) {
                x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
                y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
                x = x.replace(/^\s+|\s+$/g, "");
                if (x === c_name) {
                    return unescape(y);
                }
            }
        }
        function DeleteCookie(name) {
            document.cookie = name + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
        }
        function gaSet(){
            (function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,"script","//www.google-analytics.com/analytics.js","ga");
            ga("create", "{$tracking_id_escaped}", "$set_domain_name");
            $ga_display_feature_code
            ga("send", "pageview");
        }
        
        if(checkoutUrl === checkoutUrlDefault){
            var IsRefresh = getCookie("userKey");
            if (IsRefresh === checkoutSearch && IsRefresh !== "") {
                // DeleteCookie("IsRefresh");
            }
            else {
                setCookie("userKey", checkoutSearch, 1);
                gaSet();
            }
        }else if(checkoutUrl !== checkoutUrlDefault){
            gaSet();
        }
JS;

        echo "<script>" . $code . "</script>";
    }

    /**
     * Google Analytics eCommerce tracking
     *
     * @access public
     *
     * @param mixed $order_id
     *
     * @return void
     */
    function ecommerce_tracking_code($order_id)
    {

        global $woocommerce;
        if ($this->disable_tracking($this->ga_eeT) || current_user_can("manage_options") || get_post_meta($order_id, "_tracked", true) == 1) {
            return;
        }

        $tracking_id = $this->ga_id;
        if (!$tracking_id) {
            return;
        }
        // Doing eCommerce tracking so unhook standard tracking from the footer
        remove_action("wp_footer", [$this, "ee_settings"]);

        // Get the order and output tracking code
        $order = new WC_Order($order_id);
        //Get Applied Coupon Codes
        $coupons_list = '';
        if ($order->get_used_coupons()) {
            $coupons_count = count($order->get_used_coupons());
            $i             = 1;
            foreach ($order->get_used_coupons() as $coupon) {
                $coupons_list .= $coupon;
                if ($i < $coupons_count) {
                    $coupons_list .= ', ';
                }
                $i ++;
            }
        }

        //get domain name if value is set
        if (!empty($this->ga_Dname)) {
            $set_domain_name = esc_js($this->ga_Dname);
        } else {
            $set_domain_name = "auto";
        }

        //add display features
        if ($this->ga_DF) {
            $ga_display_feature_code = 'ga("require", "displayfeatures");';
        } else {
            $ga_display_feature_code = "";
        }

        // Order items
        if ($order->get_items()) {
            foreach ($order->get_items() as $item) {
                $_product    = $order->get_product_from_item($item);
                $ong_ts_prnm = get_the_title($item['product_id']);
                if (isset($_product->variation_data)) {
                    $categories = esc_js(wc_get_formatted_variation($_product->get_variation_attributes(), true));

                } else {
                    $out = [];

                    $categories = get_the_terms($_product->get_id(), "product_cat");


                    if ($categories) {
                        foreach ($categories as $category) {
                            $out[] = $category->name;
                        }
                    }
                    $categories = esc_js(join(",", $out));
                }
                //orderpage Prod json

                $orderpage_prod_Array[get_permalink($_product->get_id())] = [
                    "ong_ts_id" => esc_html($_product->get_id()),
                    "ong_ts_i"  => esc_js($_product->get_sku() ? $_product->get_sku() : $_product->get_id()),
                    "ong_ts_n"  => $ong_ts_prnm,
                    "ong_ts_p"  => esc_js($order->get_item_total($item)),
                    "ong_ts_c"  => $categories,
                    "ong_ts_q"  => esc_js($item["qty"])
                ];
            }
            //make json for prod meta data on order page
            $this->wc_version_compare("ong_ts_oc=" . json_encode($orderpage_prod_Array) . ";");
        }


        //get shipping cost based on version >2.1 get_total_shipping() < get_shipping
        if (version_compare($woocommerce->version, "2.1", ">=")) {
            $ong_ts_sc = $order->get_total_shipping();
        } else {
            $ong_ts_sc = $order->get_shipping();
        }
        //orderpage transcation data json
        $orderpage_trans_Array = [
            "id"          => esc_js($order->get_order_number()),      // Transaction ID. Required
            "affiliation" => esc_js(get_bloginfo('name')), // Affiliation or store name
            "revenue"     => esc_js($order->get_total()),        // Grand Total
            "tax"         => esc_js($order->get_total_tax()),        // Tax
            "shipping"    => esc_js($ong_ts_sc),    // Shipping
            "coupon"      => $coupons_list
        ];
        //make json for trans data on order page
        $this->wc_version_compare("ong_ts_td=" . json_encode($orderpage_trans_Array) . ";");

        $code = <<<'JS'
            ga("require", "ec", "ec.js");
            //set local currencies
            ga("set", "&cu", ong_ts_lc);  
            for(var t_item in ong_ts_oc){
                ga("ec:addProduct", { 
                    "id": ong_ts_oc[t_item].ong_ts_i,
                    "name": ong_ts_oc[t_item].ong_ts_n, 
                    "category": ong_ts_oc[t_item].ong_ts_c,
                    "price": ong_ts_oc[t_item].ong_ts_p,
                    "quantity": ong_ts_oc[t_item].ong_ts_q
                });
            }
            ga("ec:setAction","purchase", {
                "id": ong_ts_td.id,
                "affiliation": ong_ts_td.affiliation,
                "revenue": ong_ts_td.revenue,
                "tax": ong_ts_td.tax,
                "shipping": ong_ts_td.shipping,
                "coupon": ong_ts_td.coupon
            });
            ga("send", "event", "Enhanced-Ecommerce","load", "order_confirmation", {"nonInteraction": 1});      
    
JS;

        //check woocommerce version
        $this->wc_version_compare($code);
        update_post_meta($order_id, "_tracked", 1);
    }

    /**
     * Check if tracking is disabled
     *
     * @access private
     *
     * @param mixed $type
     *
     * @return bool
     */
    private function disable_tracking($type)
    {
        if (is_admin() || current_user_can("manage_options") || (!$this->ga_id) || "no" == $type) {
            return true;
        }
    }

    /**
     * Enhanced E-commerce tracking for single product add to cart
     *
     * @access public
     * @return void
     */
    function add_to_cart()
    {
        if ($this->disable_tracking($this->ga_eeT)) {
            return;
        }
        //return if not product page       
        if (!is_single()) {
            return;
        }
        global $product, $woocommerce;

        $category = get_the_terms($product->get_id(), "product_cat");
        $categories = $this->getCategoryComaSeparatedList($category);

        $code = <<<'JS'
            ga("require", "ec", "ec.js");
            ga("set", "&cu", ong_ts_lc);
            jQuery("[class*=single_add_to_cart_button]").click(function() {
                // Enhanced E-commerce Add to cart clicks 
                ga("ec:addProduct", {
                    "id" : ong_ts_po.ong_ts_i,
                    "name": ong_ts_po.ong_ts_n,
                    "category" :ong_ts_po.ong_ts_c,
                    "price": ong_ts_po.ong_ts_p,
                    "quantity" :jQuery(this).parent().find("input[name=quantity]").val()
                });
                ga("ec:setAction", "add");
                ga("send", "event", "Enhanced-Ecommerce","click", "add_to_cart_click", {"nonInteraction": 1});                              
            });
JS;
        //check woocommerce version
        $this->wc_version_compare($code);
    }

    /**
     * Enhanced E-commerce tracking for product detail view
     *
     * @access public
     * @return void
     */
    public function product_detail_view()
    {
        if ($this->disable_tracking($this->ga_eeT)) {
            return;
        }

        global $product, $woocommerce;

        $category = get_the_terms($product->get_id(), "product_cat");

        $categories = $this->getCategoryComaSeparatedList($category);
        //product detail view json

        $prodpage_detail_json = [
            "ong_ts_id" => esc_html($product->get_id()),
            "ong_ts_i"  => $product->get_sku() ? $product->get_sku() : $product->get_id(),
            "ong_ts_n"  => $product->get_title(),
            "ong_ts_c"  => $categories,
            "ong_ts_p"  => $product->get_price(),
        ];

        if (empty($prodpage_detail_json)) { //prod page array
            $prodpage_detail_json = [];
        }
        //prod page detail view json
        $this->wc_version_compare("
            ong_ts_po=" . json_encode($prodpage_detail_json) . ";");
        $code = <<<'JS'
            ga("require", "ec", "ec.js");    
            ga("ec:addProduct", {
                "id": ong_ts_po.ong_ts_i,                   // Product details are provided in an impressionFieldObject.
                "name": ong_ts_po.ong_ts_n,
                "category":ong_ts_po.ong_ts_c
            });
            ga("ec:setAction", "detail");
            ga("send", "event", "Enhanced-Ecommerce", "load","product_impression_pp", {"nonInteraction": 1});
        
JS;
        //check woocommerce version
        if (is_product()) {
            $this->wc_version_compare($code);
        }
    }

    /**
     * Enhanced E-commerce tracking for product impressions on category pages (hidden fields) , product page (related
     * section) home page (featured section and recent section)
     *
     * @access public
     * @return void
     */
    public function bind_product_metadata()
    {
        if ($this->disable_tracking($this->ga_eeT)) {
            return;
        }

        global $product, $woocommerce;

        $category = get_the_terms($product->get_id(), "product_cat");

        $categories = $this->getCategoryComaSeparatedList($category);
        //declare all variable as a global which will used for make json
        global $homepage_json_fp, $homepage_json_ATC_link, $homepage_json_rp, $prodpage_json_relProd, $catpage_json, $prodpage_json_ATC_link, $catpage_json_ATC_link;
        //is home page then make all necessory json
        if (is_home() || is_front_page()) {
            if (!is_array($homepage_json_fp) && !is_array($homepage_json_rp) && !is_array($homepage_json_ATC_link)) {
                $homepage_json_fp       = [];
                $homepage_json_rp       = [];
                $homepage_json_ATC_link = [];
            }

            // ATC link Array

            $homepage_json_ATC_link[$product->add_to_cart_url()] = ["ATC-link" => get_permalink($product->get_id())];
            //check if product is featured product or not
            if ($product->is_featured()) {
                //check if product is already exists in homepage featured json

                if (!array_key_exists(get_permalink($product->get_id()), $homepage_json_fp)) {
                    $homepage_json_fp[get_permalink($product->get_id())] = [
                        "ong_ts_id" => esc_html($product->get_id()),
                        "ong_ts_i"  => esc_html($product->get_sku() ? $product->get_sku() : $product->get_id()),
                        "ong_ts_n"  => esc_html($product->get_title()),
                        "ong_ts_p"  => esc_html($product->get_price()),
                        "ong_ts_c"  => esc_html($categories),
                        "ong_ts_po" => ++ $_SESSION['t_fpcnt'],
                        "ATC-link"  => $product->add_to_cart_url(),
                    ];
                    //else add product in homepage recent product json
                } else {
                    $homepage_json_rp[get_permalink($product->get_id())] = [
                        "ong_ts_id" => esc_html($product->get_id()),
                        "ong_ts_i"  => esc_html($product->get_sku() ? $product->get_sku() : $product->get_id()),
                        "ong_ts_n"  => esc_html($product->get_title()),
                        "ong_ts_p"  => esc_html($product->get_price()),
                        "ong_ts_po" => ++ $_SESSION['t_npcnt'],
                        "ong_ts_c"  => esc_html($categories),
                    ];
                }


            } else {
                //else prod add in homepage recent json
                $homepage_json_rp[get_permalink($product->get_id())] = [
                    "ong_ts_id" => esc_html($product->get_id()),
                    "ong_ts_i"  => esc_html($product->get_sku() ? $product->get_sku() : $product->get_id()),
                    "ong_ts_n"  => esc_html($product->get_title()),
                    "ong_ts_p"  => esc_html($product->get_price()),
                    "ong_ts_po" => ++ $_SESSION['t_npcnt'],
                    "ong_ts_c"  => esc_html($categories),
                ];
            }
        } //if product page then related product page array
        else if (is_product()) {
            if (!is_array($prodpage_json_relProd) && !is_array($prodpage_json_ATC_link)) {
                $prodpage_json_relProd  = [];
                $prodpage_json_ATC_link = [];
            }
            // ATC link Array

            $prodpage_json_ATC_link[$product->add_to_cart_url()] = ["ATC-link" => get_permalink($product->get_id())];

            $prodpage_json_relProd[get_permalink($product->get_id())] = [
                "ong_ts_id" => esc_html($product->get_id()),
                "ong_ts_i"  => esc_html($product->get_sku() ? $product->get_sku() : $product->get_id()),
                "ong_ts_n"  => esc_html($product->get_title()),
                "ong_ts_p"  => esc_html($product->get_price()),
                "ong_ts_po" => ++ $_SESSION['t_npcnt'],
                "ong_ts_c"  => esc_html($categories),
            ];

        } //category page, search page and shop page json
        else if (is_product_category() || is_search() || is_shop()) {
            if (!is_array($catpage_json) && !is_array($catpage_json_ATC_link)) {
                $catpage_json          = [];
                $catpage_json_ATC_link = [];
            }
            //cat page ATC array
            $catpage_json_ATC_link[$product->add_to_cart_url()] = ["ATC-link" => get_permalink($product->get_id())];

            $catpage_json[get_permalink($product->get_id())] = [
                "ong_ts_id" => esc_html($product->get_id()),
                "ong_ts_i"  => esc_html($product->get_sku() ? $product->get_sku() : $product->get_id()),
                "ong_ts_n"  => esc_html($product->get_title()),
                "ong_ts_p"  => esc_html($product->get_price()),
                "ong_ts_po" => ++ $_SESSION['t_npcnt'],
                "ong_ts_c"  => esc_html($categories),
            ];
            }
    }

    /**
     * Enhanced E-commerce tracking for product impressions,clicks on Home pages
     *
     * @access public
     * @return void
     */
    function t_products_impre_clicks()
    {
        if ($this->disable_tracking($this->ga_eeT)) {
            return;
        }

        //get impression threshold
        $impression_threshold = $this->ga_imTh;

        //Product impression on Home Page
        global $homepage_json_fp, $homepage_json_ATC_link, $homepage_json_rp, $prodpage_json_relProd, $catpage_json, $prodpage_json_ATC_link, $catpage_json_ATC_link;
        //home page json for featured products and recent product sections
        //check if php array is empty
        if (empty($homepage_json_ATC_link)) {
            $homepage_json_ATC_link = []; //define empty array so if empty then in json will be []
        }
        if (empty($homepage_json_fp)) {
            $homepage_json_fp = []; //define empty array so if empty then in json will be []
        }
        if (empty($homepage_json_rp)) { //home page recent product array
            $homepage_json_rp = [];
        }
        if (empty($prodpage_json_relProd)) { //prod page related section array
            $prodpage_json_relProd = [];
        }
        if (empty($prodpage_json_ATC_link)) {
            $prodpage_json_ATC_link = []; //prod page ATC link json
        }
        if (empty($catpage_json)) { //category page array
            $catpage_json = [];
        }
        if (empty($catpage_json_ATC_link)) { //category page array
            $catpage_json_ATC_link = [];
        }
        //home page json
        $this->wc_version_compare("homepage_json_ATC_link=" . json_encode($homepage_json_ATC_link) . ";");
        $this->wc_version_compare("ong_ts_fp=" . json_encode($homepage_json_fp) . ";");
        $this->wc_version_compare("ong_ts_rcp=" . json_encode($homepage_json_rp) . ";");
        //product page json
        $this->wc_version_compare("ong_ts_rdp=" . json_encode($prodpage_json_relProd) . ";");
        $this->wc_version_compare("prodpage_json_ATC_link=" . json_encode($prodpage_json_ATC_link) . ";");
        //category page json
        $this->wc_version_compare("ong_ts_pgc=" . json_encode($catpage_json) . ";");
        $this->wc_version_compare("catpage_json_ATC_link=" . json_encode($catpage_json_ATC_link) . ";");


        $impression_threshold_escaped = esc_js($impression_threshold);

        $hmpg_impressions_jQ = <<<JS
            ga("require", "ec", "ec.js");
            ga("set", "&cu", ong_ts_lc);
            function t_products_impre_clicks(t_json_name,t_action){
                t_send_threshold=0;
                t_prod_pos=0;
                t_json_length=Object.keys(t_json_name).length;
                        
                for(var t_item in t_json_name) {
                    t_send_threshold++;
                    t_prod_pos++;
                           
                    ga("ec:addImpression", {   
                        "id": t_json_name[t_item].ong_ts_i,
                        "name": t_json_name[t_item].ong_ts_n,
                        "category": t_json_name[t_item].ong_ts_c,
                        "price": t_json_name[t_item].ong_ts_p,
                        "position": t_json_name[t_item].ong_ts_po
                    });
                        
                    if(t_json_length > {$impression_threshold_escaped} ){
                        if((t_send_threshold%{$impression_threshold_escaped})==0){
                            t_json_length=t_json_length-{$impression_threshold_escaped};
                            ga("send", "event", "Enhanced-Ecommerce","load","product_impression_"+t_action , {"nonInteraction": 1});  
                        }
                    }else{
                        t_json_length--;
                        if(t_json_length==0){
                            ga("send", "event", "Enhanced-Ecommerce","load", "product_impression_"+t_action, {"nonInteraction": 1});  
                        }
                    }   
                }
            }
                
            //function for comparing urls in json object
            function prod_exists_in_JSON(t_url,t_json_name,t_action){
                if(t_json_name.hasOwnProperty(t_url)){
                    t_call_fired=true;
                    ga("ec:addProduct", {              
                        "id": t_json_name[t_url].ong_ts_i,
                        "name": t_json_name[t_url].ong_ts_n,
                        "category": t_json_name[t_url].ong_ts_c,
                        "price": t_json_name[t_url].ong_ts_p,
                        "position": t_json_name[t_url].ong_ts_po
                    });
                    ga("send", "event", "Enhanced-Ecommerce","click", "product_click_"+t_action, {"nonInteraction": 1});  
                }else{
                   t_call_fired=false;
                }
                return t_call_fired;
            }
            function prod_ATC_link_exists(t_url,t_ATC_json_name,t_prod_data_json,t_qty){
                t_prod_url_key=t_ATC_json_name[t_url]["ATC-link"];
                    
                if(t_prod_data_json.hasOwnProperty(t_prod_url_key)){
                    t_call_fired=true;
                    // Enhanced E-commerce Add to cart clicks 
                    ga("ec:addProduct", {
                        "id": t_prod_data_json[t_prod_url_key].ong_ts_i,
                        "name": t_prod_data_json[t_prod_url_key].ong_ts_n,
                        "category": t_prod_data_json[t_prod_url_key].ong_ts_c,
                        "price": t_prod_data_json[t_prod_url_key].ong_ts_p,
                        "quantity" : t_qty
                    });
                    ga("ec:setAction", "add");
                    ga("send", "event", "Enhanced-Ecommerce","click", "add_to_cart_click",{"nonInteraction": 1});     
                }else{
                    t_call_fired=false;
                }
                return t_call_fired;
            }
JS;

        if (is_home() || is_front_page()) {
            $hmpg_impressions_jQ .= <<<'JS'
                if(ong_ts_fp.length !== 0){
                    t_products_impre_clicks(ong_ts_fp,"fp");       
                }
                if(ong_ts_rcp.length !== 0){
                    t_products_impre_clicks(ong_ts_rcp,"rp");    
                }
                jQuery("a:not([href*=add-to-cart],.product_type_variable, .product_type_grouped)").on("click",function(){
                    t_url=jQuery(this).attr("href");
                    //home page call for click
                    t_call_fired=prod_exists_in_JSON(t_url,ong_ts_fp,"fp");
                    if(!t_call_fired){
                        prod_exists_in_JSON(t_url,ong_ts_rcp,"rp");
                    }    
                });
                //ATC click
                jQuery("a[href*=add-to-cart]").on("click",function(){
                    t_url=jQuery(this).attr("href");
                    t_qty=$(this).parent().find("input[name=quantity]").val();
                     //default quantity 1 if quantity box is not there             
                    if(t_qty=="" || t_qty===undefined){
                        t_qty="1";
                    }
                    t_call_fired=prod_ATC_link_exists(t_url,homepage_json_ATC_link,ong_ts_fp,t_qty);
                    if(!t_call_fired){
                        prod_ATC_link_exists(t_url,homepage_json_ATC_link,ong_ts_rcp,t_qty);
                    }
                });   
JS;
        } else if (is_search()) {
            $hmpg_impressions_jQ .= <<<'JS'
                //search page json
                if(ong_ts_pgc.length !== 0){
                    t_products_impre_clicks(ong_ts_pgc,"srch");   
                }
                //search page prod click
                jQuery("a:not(.product_type_variable, .product_type_grouped)").on("click",function(){
                    t_url=jQuery(this).attr("href");
                     //cat page prod call for click
                     prod_exists_in_JSON(t_url,ong_ts_pgc,"srch");
                });
JS;
        } else if (is_product()) {
            //product page releted products
            $hmpg_impressions_jQ .= <<<'JS'

                if(ong_ts_rdp.length !== 0){
                    t_products_impre_clicks(ong_ts_rdp,"rdp");  
                }          
                //product click - image and product name
                jQuery("a:not(.product_type_variable, .product_type_grouped)").on("click",function(){
                    t_url=jQuery(this).attr("href");
                    //prod page related call for click
                    prod_exists_in_JSON(t_url,ong_ts_rdp,"rdp");
                });  
                //Prod ATC link click in related product section
                jQuery("a[href*=add-to-cart]").on("click",function(){
                    t_url=jQuery(this).attr("href");
                    t_qty=$(this).parent().find("input[name=quantity]").val();
                    //default quantity 1 if quantity box is not there             
                    if(t_qty=="" || t_qty===undefined){
                        t_qty="1";
                    }
                    prod_ATC_link_exists(t_url,prodpage_json_ATC_link,ong_ts_rdp,t_qty);
                });   
JS;
        } else if (is_product_category()) {
            $hmpg_impressions_jQ .= <<<'JS'
                //category page json
                if(ong_ts_pgc.length !== 0){
                    t_products_impre_clicks(ong_ts_pgc,"cp");  
                }
               //Prod category ATC link click in related product section
                jQuery("a:not(.product_type_variable, .product_type_grouped)").on("click",function(){
                    t_url=jQuery(this).attr("href");
                    //cat page prod call for click
                    prod_exists_in_JSON(t_url,ong_ts_pgc,"cp");
                });
JS;
        } else if (is_shop()) {
            $hmpg_impressions_jQ .= <<<'JS'
                //shop page json
                if(ong_ts_pgc.length !== 0){
                    t_products_impre_clicks(ong_ts_pgc,"sp");  
                }
                //shop page prod click
                jQuery("a:not(.product_type_variable, .product_type_grouped)").on("click",function(){
                    t_url=jQuery(this).attr("href");
                     //cat page prod call for click
                     prod_exists_in_JSON(t_url,ong_ts_pgc,"sp");
                });
JS;
        }
        //common ATC link for Category page , Shop Page and Search Page
        if (is_product_category() || is_shop() || is_search()) {
            $hmpg_impressions_jQ .= <<<'JS'
                //ATC link click
                jQuery("a[href*=add-to-cart]").on("click",function(){
                    t_url=jQuery(this).attr("href");
                    t_qty=$(this).parent().find("input[name=quantity]").val();
                    //default quantity 1 if quantity box is not there             
                    if(t_qty=="" || t_qty===undefined){
                        t_qty="1";
                    }
                    prod_ATC_link_exists(t_url,catpage_json_ATC_link,ong_ts_pgc,t_qty);
                });
JS;
        }

        //on home page, product page , category page
        if (is_home() || is_front_page() || is_product() || is_product_category() || is_search() || is_shop()) {
            $this->wc_version_compare($hmpg_impressions_jQ);
        }
    }

    /**
     * Enhanced E-commerce tracking for remove from cart
     *
     * @access public
     * @return void
     */
    public function remove_cart_tracking()
    {
        if ($this->disable_tracking($this->ga_eeT)) {
            return;
        }
        global $woocommerce;
        $cartpage_prod_array_main = [];
        //echo "<pre>".print_r($woocommerce->cart->cart_contents,TRUE)."</pre>";
        foreach ($woocommerce->cart->cart_contents as $key => $item) {
            //Version compare
                $prod_meta = wc_get_product($item["product_id"]);
            if (version_compare($woocommerce->version, "3.3", "<")) {
                $cart_remove_link = html_entity_decode($woocommerce->cart->get_remove_url($key));
            } else {
                $cart_remove_link = html_entity_decode(wc_get_cart_remove_url($key));
            }
            $category   = get_the_terms($item["product_id"], "product_cat");
            $categories = $this->getCategoryComaSeparatedList($category);
                $cartpage_prod_array_main[$cart_remove_link] = [
                    "ong_ts_id" => esc_html($prod_meta->get_id()),
                    "ong_ts_i"  => esc_html($prod_meta->get_sku() ? $prod_meta->get_sku() : $prod_meta->get_id()),
                    "ong_ts_n"  => esc_html($prod_meta->get_title()),
                    "ong_ts_p"  => esc_html($prod_meta->get_price()),
                    "ong_ts_c"  => esc_html($categories),
                    "ong_ts_q"  => $woocommerce->cart->cart_contents[$key]["quantity"]
                ];
            }

        //Cart Page item Array to Json
        $this->wc_version_compare("ong_ts_cc=" . json_encode($cartpage_prod_array_main) . ";");

        $code = <<<'JS'

            ga("require", "ec", "ec.js");
            ga("set", "&cu", ong_ts_lc);
            $("a[href*=\"?remove_item\"]").click(function(){
                var t_url=jQuery(this).attr("href");
                ga("ec:addProduct", {                
                    "id":ong_ts_cc[t_url].ong_ts_i,
                    "name": ong_ts_cc[t_url].ong_ts_n,
                    "category":ong_ts_cc[t_url].ong_ts_c,
                    "price": ong_ts_cc[t_url].ong_ts_p,
                    "quantity": ong_ts_cc[t_url].ong_ts_q
                });         
                ga("ec:setAction", "remove");
                ga("send", "event", "Enhanced-Ecommerce", "click", "remove_from_cart_click",{"nonInteraction": 1});
            });
JS;
        //check woocommerce version
        $this->wc_version_compare($code);
    }

    /**
     * Enhanced E-commerce tracking checkout step 1
     *
     * @access public
     * @return void
     */
    public function checkout_step_1_tracking()
    {
        if ($this->disable_tracking($this->ga_eeT)) {
            return;
        }
        //call fn to make json
        $this->get_ordered_items();
        $code = <<<'JS'
            ga("require", "ec", "ec.js");
            ga("set", "&cu", ong_ts_lc);
            for(var t_item in ong_ts_ch){
                ga("ec:addProduct", {
                    "id": ong_ts_ch[t_item].ong_ts_i,
                    "name": ong_ts_ch[t_item].ong_ts_n,
                    "category": ong_ts_ch[t_item].ong_ts_c,
                    "price": ong_ts_ch[t_item].ong_ts_p,
                    "quantity": ong_ts_ch[t_item].ong_ts_q
                });
            }
JS;

        $code_step_1 = $code . 'ga("ec:setAction","checkout",{"step": 1});';
        $code_step_1 .= 'ga("send", "event", "Enhanced-Ecommerce","load","checkout_step_1",{"nonInteraction": 1});';

        //check woocommerce version and add code
        $this->wc_version_compare($code_step_1);
    }

    /**
     * Get oredered Items for check out page.
     *
     * @access public
     * @return void
     */
    public function get_ordered_items()
    {
        global $woocommerce;
        $code = "";
        //get all items added into the cart
        foreach ($woocommerce->cart->cart_contents as $item) {
            //Version Compare
                $p = wc_get_product($item["product_id"]);

            $category   = get_the_terms($item["product_id"], "product_cat");
            $categories = $this->getCategoryComaSeparatedList($category);
                $chkout_json[get_permalink($p->get_id())] = [
                    "ong_ts_id"  => esc_html($p->get_id()),
                    "ong_ts_i"   => esc_js($p->get_sku() ? $p->get_sku() : $p->get_id()),
                    "ong_ts_n"   => esc_js($p->get_title()),
                    "ong_ts_p"   => esc_js($p->get_price()),
                    "ong_ts_c"   => $categories,
                    "ong_ts_q"   => esc_js($item["quantity"]),
                    "isfeatured" => $p->is_featured()
                ];
            }

        //return $code;
        //make product data json on check out page
        $this->wc_version_compare("ong_ts_ch=" . json_encode($chkout_json) . ";");
    }

    /**
     * Enhanced E-commerce tracking checkout step 2
     *
     * @access public
     * @return void
     */
    public function checkout_step_2_tracking()
    {
        if ($this->disable_tracking($this->ga_eeT)) {
            return;
        }
        $code = <<<'JS'
            for(var t_item in ong_ts_ch){
                ga("ec:addProduct", {
                    "id": ong_ts_ch[t_item].ong_ts_i,
                    "name": ong_ts_ch[t_item].ong_ts_n,
                    "category": ong_ts_ch[t_item].ong_ts_c,
                    "price": ong_ts_ch[t_item].ong_ts_p,
                    "quantity": ong_ts_ch[t_item].ong_ts_q
                });
            }
JS;

        $code_step_2 = $code . 'ga("ec:setAction","checkout",{"step": 2});';
        $code_step_2 .= 'ga("send", "event", "Enhanced-Ecommerce","load","checkout_step_2",{"nonInteraction": 1});';

        //if logged in and first name is filled - Guest Check out
        if (is_user_logged_in()) {
            $step2_onFocus = 't_tracked_focus=0;  if(t_tracked_focus===0){' . $code_step_2 . ' t_tracked_focus++;}';
        } else {
            //first name on focus call fire
            $step2_onFocus = 't_tracked_focus=0; jQuery("input[name=billing_first_name]").on("focus",function(){ if(t_tracked_focus===0){' . $code_step_2 . ' t_tracked_focus++;}});';
        }
        //check woocommerce version and add code
        $this->wc_version_compare($step2_onFocus);
    }

    /**
     * Enhanced E-commerce tracking checkout step 3
     *
     * @access public
     * @return void
     */
    public function checkout_step_3_tracking()
    {
        if ($this->disable_tracking($this->ga_eeT)) {
            return;
        }
        $code = <<<'JS'
            for(var t_item in ong_ts_ch){
                ga("ec:addProduct", {
                    "id": ong_ts_ch[t_item].ong_ts_i,
                    "name": ong_ts_ch[t_item].ong_ts_n,
                    "category": ong_ts_ch[t_item].ong_ts_c,
                    "price": ong_ts_ch[t_item].ong_ts_p,
                    "quantity": ong_ts_ch[t_item].ong_ts_q
                });
            }
JS;

        //check if guest check out is enabled or not
        $step_2_on_proceed_to_pay = (!is_user_logged_in() && !$this->ga_gCkout) || (!is_user_logged_in() && $this->ga_gCkout && $this->ga_gUser);

        $code_step_3 = $code . 'ga("ec:setAction","checkout",{"step": 3});';
        $code_step_3 .= 'ga("send", "event", "Enhanced-Ecommerce","load", "checkout_step_3",{"nonInteraction": 1});';

        $inline_js = 't_track_clk=0; jQuery(document).on("click","#place_order",function(e){ if(t_track_clk===0){';
        if ($step_2_on_proceed_to_pay) {
            if (isset($code_step_2)) {
                $inline_js .= $code_step_2;
            }
        }
        $inline_js .= $code_step_3;
        $inline_js .= "t_track_clk++; }});";

        //check woocommerce version and add code
        $this->wc_version_compare($inline_js);
    }

    /**
     * @param $category
     *
     * @return string
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    private function getCategoryComaSeparatedList($category):string
    {
        $categories = "";
        if ($category) {
            foreach ($category as $term) {
                $categories .= $term->name . ",";
            }
        }
        //remove last comma(,) if multiple categories are there
        $categories = rtrim($categories, ",");

        return $categories;
    }
}
