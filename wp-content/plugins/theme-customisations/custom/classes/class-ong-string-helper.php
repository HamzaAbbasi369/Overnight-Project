<?php
/**
 * Class TemplateHelper
 */
class Ong_String_Helper
{
    /**
     * @param $str
     *
     * @return mixed|string
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public static function underscore($str)
    {
        //non-alpha and non-numeric characters become spaces
//        $str = sanitize_title($str);
        $str = str_replace("-", "_", $str);

        return $str;
    }

    /**
     * Convert underscore_strings to camelCase.
     *
     * @param {string} $str
     *
     * @return mixed
     */
    public static function underscoreToCamel($str)
    {
        // Remove underscores, capitalize words, squash, lowercase first.
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));
    }

    public static function camelToSnake($input) {
        return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $input));
    }

    private static function sslPrm()
    {
//        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-128-cbc"));
//        $iv = 'n9WP\pYC[BnD$m5_';
        $iv = '';
        return ["5ae1b8a17bad4da4fdac796f64c16ecd", $iv, "aes-128-cbc"];
    }

    public static function sslEnc($msg)
    {
        list ($pass, $iv, $method) = self::sslPrm();
        if (function_exists('openssl_encrypt')) {
            return urlencode(@openssl_encrypt(urlencode($msg), $method, $pass, false, $iv));
        } else {
            return urlencode(exec("echo \"" . urlencode($msg) . "\" | openssl enc -" . urlencode($method)
                                  . " -base64 -nosalt -K " . bin2hex($pass) . " -iv " . bin2hex($iv)));
        }
    }

    public static function sslDec($msg)
    {
        list ($pass, $iv, $method) = self::sslPrm();
        if (function_exists('openssl_decrypt')) {
            return trim(urldecode(@openssl_decrypt(urldecode($msg), $method, $pass, false, $iv)));
        } else {
            return trim(urldecode(exec("echo \"" . urldecode($msg) . "\" | openssl enc -" . $method
                                       . " -d -base64 -nosalt -K " . bin2hex($pass) . " -iv " . bin2hex($iv))));
        }
    }

    public static function randGenerate($length)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';
        for ($i = 0; $i <= $length; $i++) {
            $result .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $result;
    }

    public static function ong_create_csv_file($create_data, $file = null, $col_delimiter = ',', $row_delimiter = "\r\n")
    {

        if (!is_array($create_data)) {
            return false;
        }

        if ($file && !is_dir(dirname($file))) {
            return false;
        }

        $collected_rows = [];

        foreach ($create_data as $row) {
            $cols = [];

            foreach ($row as $col_val) {
                if ($col_val && preg_match('/[",;\r\n]/', $col_val)) {
                    if ($row_delimiter === "\r\n") {
                        $col_val = str_replace("\r\n", '\n', $col_val);
                        $col_val = str_replace("\r", '', $col_val);
                    } elseif ($row_delimiter === "\n") {
                        $col_val = str_replace("\n", '\r', $col_val);
                        $col_val = str_replace("\r\r", '\r', $col_val);
                    }

                    $col_val = str_replace('"', '""', $col_val);
//                    $col_val = '"' . $col_val . '"';
                }
                $cols[] = $col_val;
            }

            $collected_rows[] = implode($col_delimiter, $cols);
        }

        $CSV_str = implode($row_delimiter, $collected_rows);

        if ($file) {
            $CSV_str = iconv("UTF-8", "UTF-8", $CSV_str);
            $done = file_put_contents($file, $CSV_str);

            if ($done) {
                return $CSV_str;
            }
            return false;
        }

        return $CSV_str;
    }

    public static function ong_datepicker_js()
    {
        wp_enqueue_script('jquery-ui-datepicker');

        wp_enqueue_style('jquery-ui-datepicker');

        if (is_admin()) {
            add_action('admin_footer', 'init_datepicker', 99);
        } else {
            add_action('wp_footer', 'init_datepicker', 99);
        }

        function init_datepicker()
        {
            ?>
            <script type="application/javascript">
                jQuery(document).ready(function($) {

                    $.datepicker.setDefaults({
                        dateFormat: 'dd-mm-yy',
                        firstDay: 1,
                        showAnim: 'slideDown',
                        isRTL: false,
                        showMonthAfterYear: false,
                        yearSuffix: ''
                    });

                    var dateFormat = "mm/dd/yy",
                        from = $( "#from" )
                            .datepicker({
                                defaultDate: "+1w",
                                changeMonth: true,
                                numberOfMonths: 1
                            })
                            .on( "change", function() {
                                to.datepicker( "option", "minDate", getDate( this ) );
                            }),
                        to = $( "#to" ).datepicker({
                            defaultDate: "+1w",
                            changeMonth: true,
                            numberOfMonths: 1
                        })
                            .on( "change", function() {
                                from.datepicker( "option", "maxDate", getDate( this ) );
                            });

                    function getDate( element ) {
                        var date;
                        try {
                            date = $.datepicker.parseDate( dateFormat, element.value );
                        } catch( error ) {
                            date = null;
                        }
                        return date;

                    }

                    $('#submit_datepicker').on( "click", function() {

                            var from_date = $( "#from" ).val();
                            var to_date = $( "#to" ).val();
                            console.log(from_date);
                            console.log(to_date);
                            console.log('<?=admin_url('admin-ajax.php')?>');


                        jQuery.ajax({
                            url: '<?=admin_url('admin-ajax.php')?>',
                            type: 'GET',
                            dataType: 'text',
                            data: {
                                action: 'ong_picker',
                                from_date: from_date,
                                to_date: to_date
                            },

                            success: function (data) {
                                console.log(arguments);
                                alert("Data Loaded: " + arguments);
//                                jQuery().html(data);
                            },
                            complete: function (data) {
                                alert("Data complete: " + data);
//                                console.log('complete');
                            }
                         });


                    });


//                    $('#datepicker').datetimepicker();
                });
            </script>
            <?php
        }
    }
}
