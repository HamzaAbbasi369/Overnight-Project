<?php



/**
 * Manage ONG from CLI.
 *
 * @class    ONG_CLI
 * @version  2.5.0
 * @package  Ong/CLI
 * @category CLI
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */
class ONG_Addon_Commands_Terms_CLI extends WP_CLI_Command {

    private $from_scratch;
    private $skip_empty;
    
    function __construct()
    {
        
        if (!function_exists('cm_import_media')) :
            function cm_import_media($file_url, $post_id)
            {
                if (!$post_id) {
                    return false;
                }

                $exists = !!get_term_meta($post_id, 'image', true);
                if ($exists) {
                    return true;
                }

                $filename    = basename($file_url);
                $upload_file = wp_upload_bits($filename, null, file_get_contents($file_url));

                if (!$upload_file['error']) {

                    $wp_filetype   = wp_check_filetype($filename, null);
                    $attachment    = [
                        'post_mime_type' => $wp_filetype['type'],
                        'post_parent'    => $post_id,
                        'post_title'     => preg_replace('/\.[^.]+$/', '', $filename),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    ];
                    $attachment_id = wp_insert_attachment($attachment, $upload_file['file'], $post_id);

                    if (!is_wp_error($attachment_id)) {
                        require_once(ABSPATH . 'wp-admin/includes/image.php');

                        $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
                        wp_update_attachment_metadata($attachment_id, $attachment_data);

                        update_term_meta($post_id, 'image', $attachment_id);
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }

                return true;
            }
        endif;
    }

    public function update_all_icons($args, $assoc_args) {
        $this->updateTermIcons('brands', 'pa_brands', $args, $assoc_args);
        $this->updateTermIcons('color', 'pa_color', $args, $assoc_args, 'field_58fde94964a9a');
    }
    
    public function update_brands($args, $assoc_args) {
        $from_scratch = WP_CLI\Utils\get_flag_value($assoc_args, 'from_scratch');
        $this->from_scratch = !!$from_scratch;
        $skip_empty   = WP_CLI\Utils\get_flag_value($assoc_args, 'skip_empty');
        if (!isset($skip_empty)) {
            $skip_empty = true;
        }
        $this->skip_empty = !!$skip_empty;
        
        $this->updateTermIcons('brands', 'pa_brands', $args, $assoc_args);
    }

    public function update_color($args, $assoc_args) {
        $from_scratch = WP_CLI\Utils\get_flag_value($assoc_args, 'from_scratch');
        $this->from_scratch = !!$from_scratch;
        $skip_empty   = WP_CLI\Utils\get_flag_value($assoc_args, 'skip_empty');
        if (!isset($skip_empty)) {
            $skip_empty = true;
        }
        $this->skip_empty = !!$skip_empty;
        var_dump($this->skip_empty);
        
        $this->updateTermIcons('color', 'pa_color', $args, $assoc_args, 'field_58fde94964a9a');
    }
    
    
    /**
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    private function updateTermIcons($term_name, $taxonomy, $args, $assoc_args, $field_key = null)
    {
        \cli\line( "Start updating of {$term_name}s: " );

        $this->handleAttributeTypeSet($term_name, $assoc_args);
        $raw_terms = $this->getRawTerms($taxonomy, $assoc_args);

        if (!sizeof($raw_terms)) {
            \cli\err( "Nothing to do, term skipped" );
            return;
        }
        
        $images = $this->getImages($term_name);
        $terms = $this->getAttachImageToTerms($term_name, $raw_terms, $images);

        if (isset($field_key)) {
            $this->updateTermsWithTheImage($field_key, $terms, $images);
        }

        $not_found = array_filter($images, function($item) {
            return empty($item['found']);
        });
        //print_r($not_found);

        \cli\line( "Done!" );
    }

    /**
     * @param $taxonomy
     * @param $assoc_args
     *
     * @return mixed
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    private function getRawTerms($taxonomy, $assoc_args)
    {
        global $wpdb;

        //2. get list of terms
        $sql       = $wpdb->prepare("SELECT t.term_id, t.name, t.slug, tt.count, tm.meta_value as image FROM $wpdb->terms t
          JOIN $wpdb->term_taxonomy tt on t.term_id=tt.term_id
          left join $wpdb->termmeta tm on (t.term_id=tm.term_id and tm.meta_key='image')
          WHERE tt.taxonomy = %s",
            $taxonomy
        );
        $raw_terms = $wpdb->get_results($sql);
        if (count($raw_terms)) {
            \cli\line( "Count:" . count($raw_terms) );    
        } else {
            \cli\err( "Count:" . count($raw_terms) );
        }

        return $raw_terms;
    }

    /**
     * @param $term_name
     * @param $assoc_args
     *
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     *
     * @return void
     */
    private function handleAttributeTypeSet($term_name, $assoc_args)
    {
        global $wpdb;
        $set_attribute_type = WP_CLI\Utils\get_flag_value($assoc_args, 'set_attribute_type');
        if ($set_attribute_type) {
            //1. set type of attribute - image
            $table  = $wpdb->prefix . 'woocommerce_attribute_taxonomies';
            $update = "UPDATE `$table` SET `attribute_type` = 'image' WHERE `attribute_type` != 'image'
              and `attribute_name`='{$term_name}'";
            $wpdb->query($update);
            \cli\line('Type has been set;');
        }
    }

    /**
     * @param $term_name
     *
     * @return array
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    private function getImages($term_name):array
    {
        $image_files  = glob(WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $term_name . '/*.*');
        $images       = [];
        $output_array = null;

        foreach ($image_files as $image) {
            if (is_file($image)) {
                $image      = realpath($image);
                $path_parts = pathinfo($image);
                if (!in_array($path_parts['extension'],['png','svg']))  {
                    continue;
                }
                $input_line = $path_parts['filename'];
//                    if (preg_match('/^[^\($]+([^\)]+)?$/',$path_parts['filename'],$matches)){
                if (preg_match('/^([^$(]+)(?:\(([^)]+)\))$/mis', $input_line, $output_array)) {
                    $index = sanitize_title(trim($output_array[1]));

                    $unified_colors = [];
                    if (!empty($output_array[2])) {
                        $unified_colors = preg_split('~\s*,\s*~', $output_array[2]);
                        $unified_colors = array_map('sanitize_title', $unified_colors);
                    }

                    $images[$index] = [
                        'file'           => $image,
                        'parts'          => $path_parts,
                        'unified_colors' => $unified_colors
                    ];
                    unset($output_array);
                } else {
                    $index = sanitize_title(trim($input_line));
                    $images[$index] = [
                        'file'  => $image,
                        'parts' => $path_parts
                    ];
                }
            }
        }

        if (count($images)) {
            \cli\line( "Images found:" . count($images) );
        } else {
            \cli\err( "Images found:" . count($images) );
        }

        return $images;
    }

    /**
     * @param $term_name
     * @param $raw_terms
     * @param $images
     *
     * @return array
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    private function getAttachImageToTerms($term_name, $raw_terms, &$images):array
    {
        $terms         = [];
        uasort ( $raw_terms , function ($a, $b) {
                return strnatcmp($a->name,$b->name); // or other function/code
            }
        );
        
        
        $no_image_found = [];
        foreach ($raw_terms as $key => $term) {
            $term_slug = $term->slug;
            //check if file exists
            if (!array_key_exists($term_slug, $images)) {
                 if (!empty($term->count)) {
                     \cli\err( "[usages:%d] There is no image found for the term %s (%s)", $term->count, $term->name, $term_slug);
                 }
                 if (!$this->skip_empty && empty($term->count)) {
                     \cli\err( "[empty] There is no image found for the term %s (%s)", $term->name, $term_slug);
                 } else {
		     continue;
		 }
		 $no_image_found[] = $term;
                 continue;
            } else {
                $images[$term_slug]['found'] = true;
                if ((empty($term->image) || $this->from_scratch) && !cm_import_media($images[$term_slug]['file'], $term->term_id)) {
                    \cli\err( "Unable to import media %s for term %s (%s)", $images[$term_slug]['file'], $term->name, $term_slug);
                }
            }
            //exists
            $terms[$term_slug] = $term;
        }
        if (count($terms)) {
            \cli\line( "Filtered terms count: %d",  count($terms) );
        } 

	if (count($no_image_found)) {
            \cli\err( "Terms count with no image found: %d",  count($no_image_found) );
        }

        return $terms;
    }

    /**
     * @param $images
     * @param $terms
     *
     * @return void
     *
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    private function processUnifiedColorsWithIds(&$images, $terms)
    {
        foreach ($images as &$im) {
            $unified_color_ids = [];
            if (!empty($im['unified_colors'])) {
                foreach ($im['unified_colors'] as $u_image) {
                    if (array_key_exists($u_image, $terms)) {
                        array_push($unified_color_ids, $terms[$u_image]->term_id);
                    } else {
                        \cli\err( "Unable to find Unified Color %s in terms", $u_image);
                    }
                }
            }
            $im['unified_color_ids'] = $unified_color_ids;
        }
    }

    /**
     * @param $term_name
     * @param $terms
     * @param $images
     * @return void
     *
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    private function updateTermsWithTheImage($field_key, $terms, &$images)
    {
        $this->processUnifiedColorsWithIds($images, $terms);
        //go though terms with the image
        foreach ($terms as $slug => $term) {
            if (!array_key_exists($slug, $images)) {
                if (!$this->skip_empty && !empty($term->count)) {
                    // \cli\err( "There is no image %s for term %s", $slug, $term->name);
                    \cli\err( "%s | %s", $slug, $term->name);
                }
                continue;
            }
            
            $image_data = $images[$slug];
            // var_dump($term->image);
            // var_dump($this->from_scratch);
            // if (
                // (empty($term->image) || $this->from_scratch)
                
            //    !empty($image_data['unified_color_ids'])
                
                // ) {
                $value = update_field(
                    $field_key,
                    $image_data['unified_color_ids'],
                    'pa_color_' . $term->term_id
                );
                if (!$value) {
                    \cli\err( "Unable to update field %s with data %s", $field_key, $image_data['unified_color_ids']);
                }
            // }
        }
    }
}