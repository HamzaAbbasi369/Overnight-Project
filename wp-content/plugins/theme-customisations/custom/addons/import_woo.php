<?php
/**
 * Plugin Name: Import product
 * Description:Import product for WooCommerce
 *
 * File open wp/wp-content/var.csv
 *
 */

add_action('admin_menu', 'ImportRxWooCommerce');

function ImportRxWooCommerce()
{
    add_options_page('Import product', 'Import product', 'manage_options', 'import-rx', 'importRx');
}


function importRx()
{
    global $wp_query;
//    $urlImages =  'http://dev.overnightglasses.com/content/uploads/2017/01/';
//    $url =  'http://new-site.local/content/uploads/imp/test/';
//    $url =  'http://new-site.local/content/uploads/imp/';

    ?>
    <div class="wrap">
        <h2>Import product</h2>
        <p>Import product for WooCommerce</p>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="import">URL to photo of goods: <strong>(http://site.com/content/uploads/imp/)</strong></label>
            <input style="width: 350px" type="text" name="url_image" value="http://new-site.local/content/uploads/imp/"/>
            <br>

            <label for="import">Upload file for import in format - <strong>file_name.csv</strong></label>
            <input type="file" name="import" id="import"/>
            <?php submit_button('Start import'); ?>
        </form>
<!--        <p><a href="http://new-site.local/content/uploads/import_woo_log.txt">Open the log file to the browser</a>-->
<!--        <p><a href="http://new-site.local/content/uploads/import_woo_log.txt" download>Download log file</a>-->
    </div>

    <?php


    if (!isset($_FILES['import'])) {
        return;
    }

    $url = $_POST['url_image'];
    $log = "url =". $url."\n";

    $errors = array();
    $file_name = $_FILES['import']['name'];
    $file_size = $_FILES['import']['size'];
    $file_tmp = $_FILES['import']['tmp_name'];
    $file_type = $_FILES['import']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['import']['name'])));

    $expensions = array("csv");

    if (in_array($file_ext, $expensions) === false) {
        $errors = "Extension not allowed, please choose a csv file.";
    }

    if ($file_size > 2097152) {
        $errors = 'File size must be excately 2 MB';
    }

    if (!empty($errors)) {
        echo $errors;
        return;
    }

    $uploaddir = ABSPATH . 'wp-content/';
    $uploadfile = $uploaddir . basename($_FILES['import']['name']);

    try {
        move_uploaded_file($_FILES['import']['tmp_name'], $uploadfile);
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }

    $file_open = $uploaddir . $file_name;
    $log .= '- File open - '.$file_open . "\n";
    $records = getRecords($file_open);


    $i = 1;

    foreach ($records as $product_name => $variations) {
        $colors = [];
        $img = '';
        $k = 0;
        $kPos = 0;
        $kPosition = 0;
        $kWoo = 0;
        foreach ($variations as $variation) {
            $log .= implode(", ", $variation);
            $woosvi_slug = ongGetColorSlugByName($variation['color_description']);
            $product_name = str_replace(' ', '-', $variation['product_name']);
            $model = str_replace(' ', '', trim($variation['model']));
            $colors[] = $variation['color_description'];
            $img .= ' --images.'.$k++.".src='{$url}{$model}-{$variation['frame_color']}-45.jpg'    --images.".$kPos++.".position=".$kPosition++." --images.".$kWoo++.".woosvi_slug={$woosvi_slug}";
            $img .= ' --images.'.$k++.".src='{$url}{$model}-{$variation['frame_color']}-FRONT.jpg' --images.".$kPos++.".position=".$kPosition++." --images.".$kWoo++.".woosvi_slug={$woosvi_slug}";
        }

        $log .= $img . "\n";
        $colors = implode('|', $colors);
        $colors = trim($colors);
        $assoc = reset($variations);
        $cat = getIdsCat($assoc['categories']);
        $idProduct = ongGetIdByPageName($assoc['product_name']);

        if (!empty($idProduct)) {

            /** ************** Update product ************* */

            echo "<p>&bull;  &#8470;{$i} &rArr;  Update. Product name: {$assoc['product_name']}. ID product: {$idProduct}. Categories - ({$assoc['categories']}). Colors - {$colors}. Brands - {$assoc['brand']} </p>";
            $log .= "{$i}. --*-- Update. Product name: {$assoc['product_name']}. ID product: {$idProduct}. Categories - ({$assoc['categories']}). Colors - {$colors}. Brands - {$assoc['brand']}\n";


            $cliText = " ong product update {$idProduct} --title='{$assoc['product_name']}' --type=variable --categories={$cat}\\
        --tax_class='{$assoc['tax_class_id']}' --weight='{$assoc['package_weight']}'\\
        {$img}\\
        --default_attribute.0.name='Color' --default_attribute.0.slug=color --default_attribute.0.option='{$assoc['color_description']}'\\
        --dimensions.length='{$assoc['package_length']}' --dimensions.width='{$assoc['package_width']}'  --dimensions.height='{$assoc['package_height']}'\\
                --attributes.1.name='Brands'       --attributes.1.slug=brands       --attributes.1.visible=yes  --attributes.1.options='{$assoc['brand']}'\\
                --attributes.2.name='Size'         --attributes.2.slug=size         --attributes.2.visible=yes  --attributes.2.options='{$assoc['frame_size']}'\\
                --attributes.3.name='Lens Height'  --attributes.3.slug=lens-height  --attributes.3.visible=yes  --attributes.3.options='{$assoc['lens_height']}'\\
                --attributes.4.name='Lens Width'   --attributes.4.slug=lens-width   --attributes.4.visible=yes  --attributes.4.options='{$assoc['lens_width']}'\\
                --attributes.5.name='Bridge'       --attributes.5.slug=bridge       --attributes.5.visible=yes  --attributes.5.options='{$assoc['bridge_width']}'\\
                --attributes.6.name='Frame Width'  --attributes.6.slug=frame-width  --attributes.6.visible=yes  --attributes.6.options='{$assoc['frame_width']}'\\
                --attributes.7.name='Temple'       --attributes.7.slug=temple       --attributes.7.visible=yes  --attributes.7.options='{$assoc['temple_length']}'\\
                --attributes.8.name='Color Code'   --attributes.8.slug=color-code   --attributes.8.visible=yes  --attributes.8.options='{$assoc['frame_color']}'\\
                --attributes.9.name='Color'        --attributes.9.slug=color        --attributes.9.visible=yes  --attributes.9.options='{$colors}' --attributes.9.variation=yes\\
                --attributes.10.name='Gender'       --attributes.10.slug=gender       --attributes.10.visible=yes  --attributes.10.options='{$assoc['gender']}'\\
                --attributes.11.name='Material'    --attributes.11.slug=material    --attributes.11.visible=yes --attributes.11.options='{$assoc['material']}'\\
                --attributes.12.name='Shape'       --attributes.12.slug=shape       --attributes.12.visible=yes --attributes.12.options='{$assoc['shape']}'\\
                --attributes.13.name='Frame Style' --attributes.13.slug=frame-style --attributes.13.visible=yes --attributes.13.options='{$assoc['style']}' ";

            foreach ($variations as $key => $assoc) {
                d($assoc);

                $colorSlug = ongGetColorSlugByName($assoc['color_description']);
                $IdVariation = getIdVariationByIdProduct($idProduct, $colorSlug, $assoc['product_name']);
                $product_name = str_replace(' ', '-', $assoc['product_name']);
                $model = str_replace(' ', '', trim($variation['model']));

                $sku = ongGetSky($assoc, $product_name);

                $cliText .= " --variations.{$key}.id=$IdVariation\\
                        --variations.{$key}.attributes.color='{$assoc['color_description']}'\\
                        --variations.{$key}.attributes.size='{$assoc['frame_size']}'\\
                        --variations.{$key}.attributes.color_code='{$assoc['frame_color']}'\\
                        --variations.{$key}.regular_price='{$assoc['regular_price']}'\\
                        --variations.{$key}.stock_quantity='{$assoc['quantity']}'\\
                        --variations.{$key}.in_stock='yes'\\
                        --variations.{$key}.sku='{$sku}'\\
                        --variations.{$key}.weight='{$assoc['package_weight']}'\\
                        --variations.{$key}.managing_stock='{$assoc['manage_stock']}'\\
                        --variations.{$key}.images.0.src='{$url}{$model}-{$assoc['frame_color']}-45.jpg' --variations.{$key}.images.0.position=0\\
            ";
            }

        } else {

            /** ************** Create product ************* */

            echo "<p>&bull;  &#8470;{$i} &rArr;  Create. Product name: {$assoc['product_name']}. Categories - ({$assoc['categories']}). Colors - {$colors}. Brands - {$assoc['brand']} </p>";
            $cliText = " ong product create --title='{$assoc['product_name']}' --type=variable --categories={$cat}\\
        --tax_class='{$assoc['tax_class_id']}' --weight='{$assoc['package_weight']}'\\
        {$img}\\
        --default_attribute.0.name='Color' --default_attribute.0.slug=color --default_attribute.0.option='{$assoc['color_description']}'\\
        --dimensions.length='{$assoc['package_length']}' --dimensions.width='{$assoc['package_width']}'  --dimensions.height='{$assoc['package_height']}'\\
                --attributes.1.name='Brands'       --attributes.1.slug=brands       --attributes.1.visible=yes  --attributes.1.options='{$assoc['brand']}'\\
                --attributes.2.name='Size'         --attributes.2.slug=size         --attributes.2.visible=yes  --attributes.2.options='{$assoc['frame_size']}'\\
                --attributes.3.name='Lens Height'  --attributes.3.slug=lens-height  --attributes.3.visible=yes  --attributes.3.options='{$assoc['lens_height']}'\\
                --attributes.4.name='Lens Width'   --attributes.4.slug=lens-width   --attributes.4.visible=yes  --attributes.4.options='{$assoc['lens_width']}'\\
                --attributes.5.name='Bridge'       --attributes.5.slug=bridge       --attributes.5.visible=yes  --attributes.5.options='{$assoc['bridge_width']}'\\
                --attributes.6.name='Frame Width'  --attributes.6.slug=frame-width  --attributes.6.visible=yes  --attributes.6.options='{$assoc['frame_width']}'\\
                --attributes.7.name='Temple'       --attributes.7.slug=temple       --attributes.7.visible=yes  --attributes.7.options='{$assoc['temple_length']}'\\
                --attributes.8.name='Color Code'   --attributes.8.slug=color-code   --attributes.8.visible=yes  --attributes.8.options='{$assoc['frame_color']}'\\
                --attributes.9.name='Color'        --attributes.9.slug=color        --attributes.9.visible=yes  --attributes.9.options='{$colors}' --attributes.9.variation=yes\\
                --attributes.10.name='Gender'       --attributes.10.slug=gender       --attributes.10.visible=yes  --attributes.10.options='{$assoc['gender']}'\\
                --attributes.11.name='Material'    --attributes.11.slug=material    --attributes.11.visible=yes --attributes.11.options='{$assoc['material']}'\\
                --attributes.12.name='Shape'       --attributes.12.slug=shape       --attributes.12.visible=yes --attributes.12.options='{$assoc['shape']}'\\
                --attributes.13.name='Frame Style' --attributes.13.slug=frame-style --attributes.13.visible=yes --attributes.13.options='{$assoc['style']}' ";

            foreach ($variations as $key => $assoc) {
                d($assoc);
                $product_name = str_replace(' ', '-', $assoc['product_name']);

                $sku = ongGetSky($assoc, $product_name);
                $model = str_replace(' ', '', trim($variation['model']));

                $cliText .= " --variations.{$key}.attributes.color='{$assoc['color_description']}'\\
                        --variations.{$key}.attributes.size='{$assoc['frame_size']}'\\
                        --variations.{$key}.attributes.color_code='{$assoc['frame_color']}'\\
                        --variations.{$key}.regular_price='{$assoc['regular_price']}'\\
                        --variations.{$key}.stock_quantity='{$assoc['quantity']}'\\
                        --variations.{$key}.in_stock='yes'\\
                        --variations.{$key}.sku='{$sku}'\\
                        --variations.{$key}.weight='{$assoc['package_weight']}'\\
                        --variations.{$key}.managing_stock='{$assoc['manage_stock']}'\\
                        --variations.{$key}.images.0.src='{$url}{$model}-{$assoc['frame_color']}-45.jpg' --variations.{$key}.images.0.position=0\\
            ";
            }
        }
        $log .= runConsoleImport($cliText);
        $i++;
    }

    $num = count($records);
    echo "<h3>&#9745; As a result: {$num} - items processed.</h3>";
    $log .=  "As a result: {$num} - items processed. \n";
    dump($log);
}


function ongGetSky($assoc)
{
//    if (empty($assoc['sku'])) {
//    $product_name = str_replace(' ', '-', $assoc['product_name']);
//        $sku = $product_name . '-' . $assoc['frame_color'];
//        return array($assoc, $sku);
//    } else {
//        $sku = $assoc['sku'];
//        return array($assoc, $sku);
//    }
    return $assoc['sku'];
}

/**
 * @param $file_open
 * @return array
 */
function getRecords($file_open)
{
    $handle = fopen($file_open, "r");

    $row = 0;
    $titles = [];
    $records = [];

    while (false !== ($data = fgetcsv($handle, 1000, ","))) {

        if ($row++ === 0) {
            $titles = $data;
            continue;
        }

        $assoc = [];
        foreach (array_map(function ($d, $t) {
            return [
                'name' => $t,
                'value' => $d
            ];
        }, $data, $titles) as $val) {
            $assoc[sanitize_key($val['name'])] = $val['value'];
        }

        if (array_key_exists('manage_stock', $assoc)) {
            $assoc['manage_stock'] = strtolower($assoc['manage_stock']);
        }

        $records[$assoc['product_name']][] = $assoc;

    }
    fclose($handle);
    unset ($assoc);
    return $records;
}


function runConsoleImport($cliText)
{
    d($cliText);

    $console = '/../vendor/bin/wp';
    $console = realpath(WP_CONTENT_DIR . $console);
    $output = [];

    $wp = realpath(WP_CONTENT_DIR . '/../wp');
    $path = ' 2>&1 --path=' . $wp;
    $console = $console . $cliText . $path;
//    $log =  "- Console- ". $console . "\n";
    $log = '';
    exec($console, $output, $return_var);

    if ($return_var === 0) {
        echo "<h4>&#10155; Return - OK</h4>";
        $log .=  "Return - OK. \n";

    } else {
        echo "<h4>&#10155; Return Error - {$return_var}</h4>";
        $log .=  "Return Error - {$return_var}. \n";

    }

    $output = implode("<br>", $output);
    echo "<h4 style='color: red'>&#10004; {$output}</h4>";
    $log .=  "{$output}. \n\n";
    return $log;
}


function getIdsCat($ids)
{
    $catId = '';
    $chars = preg_split('/,/', $ids, -1, PREG_SPLIT_NO_EMPTY);
    foreach ($chars as $n => $k) {
        $cat = get_term_by('name', $k, 'product_cat');
        $catId .= $cat->term_id . ',';
    }
    if ($catId{strlen($catId) - 1} == ',') {
        $catId = substr($catId, 0, -1);
    }
    return $catId;
}


function ongGetIdByPageName($page_name)
{
    global $wpdb;
    $page_name = strip_tags($page_name);
    $page_name = addslashes($page_name);
    $page_name_id = $wpdb->get_results("SELECT ID FROM {$wpdb->posts} WHERE (post_title ='".$page_name."') AND (post_status = 'publish')");

    $countIds = count($page_name_id);
    if ($countIds > 1) { //todo ($countIds > 1)
        echo "<h2 style='color: red'>Error! There are {$countIds} duplicate items: {$page_name}. Please correct this.</h2>";
        return false;
    } else {
        return $page_name_id[0]->ID;
    }

}


function ongGetColorSlugByName($color)
{
    $color = trim($color);
    static $termsNameSlag = [];

    if (empty($termsNameSlag)) {
        $args = [
            'taxonomy' => 'pa_color',
            'orderby' => 'count',
            'hide_empty' => 0,
            'order' => 'DESC',
        ];

        $terms = get_terms($args);
        foreach ($terms as $term) {
            $termsNameSlag[$term->name] .= $term->slug;
        }
    }
    return $termsNameSlag[$color];
}


function getIdVariationByIdProduct($idProduct, $colorSlug, $ProductName)
{
    $args = [
        'post_parent' => $idProduct,
        'post_type' => 'product_variation',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => 'attribute_pa_color',
                'value' => $colorSlug,
                'compare' => '='
            ]
        ]
    ];

    $wp_query = new WP_Query($args);

    if($wp_query->posts[0]->ID === null){
        echo "<h2 style='color: red'>Error! Product: {$ProductName} id:{$idProduct}, does not have the color (slug): {$colorSlug}. Please correct this.</h2>";
        return false;
    } else {
        return $wp_query->posts[0]->ID;
    }
}


function dump(){
    $arArgs = func_get_args();
    $sResult = '';
    foreach ($arArgs as $arArg) {
        $sResult .= "\n\n---------------". date('Y-m-d H:i:s'). "----------------\n";
        $sResult .= print_r($arArg , true);
    }
    error_log($sResult, 3, dirname(__FILE__) . '/import_woo_log.txt');
//    file_put_contents(dirname(__FILE__) . '/import_woo_log.txt', $sResult, FILE_APPEND );

}

function trimDateLoad($val) {
    return trim($val);
}
