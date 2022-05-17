<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Model\Indexer;

class Pages extends AbstractPostIndexer
{
    /**
     * {@inheritdoc}
     */
    public function getData($id = 0)
    {
        $data_for_sync = [
            'pages' => []
        ];

        $sites = $this->getSitesData();
        foreach ($sites as $site) {
            $this->switchToBlog($site['blog_id']);
            if ($id) {
                $post_ids = [$id];
            } else {
                $post_ids = $this->get_posts('page');
            }

            add_filter('wxr_export_skip_postmeta', [&$this, 'wxr_filter_postmeta'], 10, 2);

            if ($post_ids) {
                $data_for_sync['pages'] = array_merge($data_for_sync['pages'], $this->get_page_data($post_ids));
            }
            $this->restoreCurrentBlog();
        }

        return $data_for_sync;
    }

    /**
     * @param \WP_Post $post
     *
     * @return array
     */
    public function prepareToSync($post)
    {
        /**
         * @global \WP_Query $wp_query
         */
        global $wpdb;

        $data = [];

        $blog_id = get_current_blog_id();

        $is_sticky              = is_sticky($post->ID) ? 1 : 0;
        $data['post_id']        = $post->ID;
        $data['blog_id']        = $blog_id;
        $data['post_title']     = apply_filters('the_title_rss', $post->post_title);
        $data['pub_date']       = mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false);
        $data['creator']        = get_the_author_meta('login');
        $data['guid']           = get_the_guid($post->ID);
        $data['url']            = get_permalink($post);
        $data['content']        = apply_filters('the_content', $this->removeWidgets($post->post_content));
        $data['excerpt']        = apply_filters('the_excerpt', $this->removeWidgets($post->post_excerpt));
        $data['post_date']      = $post->post_date;
        $data['post_date_gmt']  = $post->post_date_gmt;
        $data['comment_status'] = $post->comment_status;
        $data['ping_status']    = $post->ping_status;
        $data['post_name']      = $post->post_name;
        $data['post_status']    = $post->post_status;
        $data['post_parent']    = $post->post_parent;
        $data['menu_order']     = $post->menu_order;
        $data['post_type']      = $post->post_type;
        $data['post_password']  = $post->post_password;
        $data['is_sticky']      = $is_sticky;
        $data['taxonomy']       = $this->get_wxr_post_taxonomy($post);
        $data['active']         = \OngStore\Core\Helper\Post::is_active($post);

        if ($post->post_type == 'attachment') {
            $data['attachment_url'] = wp_get_attachment_url($post->ID);
        }

        $postmeta = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE post_id = %d", $post->ID));
        foreach ($postmeta as $meta) {
            if (apply_filters('wxr_export_skip_postmeta', false, $meta->meta_key, $meta)) {
                continue;
            }
            $data['postmeta']['meta_key']   = $meta->meta_key;
            $data['postmeta']['meta_value'] = $meta->meta_value;
        }

        $_comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved <> 'spam'", $post->ID));
        $comments  = array_map('get_comment', $_comments);
        foreach ($comments as $c) {
            $data['comments']['comment_id']           = $c->comment_ID;
            $data['comments']['comment_author']       = $c->comment_author;
            $data['comments']['comment_author_email'] = $c->comment_author_email;
            $data['comments']['comment_author_url']   = esc_url_raw($c->comment_author_url);
            $data['comments']['comment_author_IP']    = $c->comment_author_IP;
            $data['comments']['comment_date']         = $c->comment_date;
            $data['comments']['comment_date_gmt']     = $c->comment_date_gmt;
            $data['comments']['comment_content']      = $c->comment_content;
            $data['comments']['comment_approved']     = $c->comment_approved;
            $data['comments']['comment_type']         = $c->comment_type;
            $data['comments']['comment_parent']       = $c->comment_parent;
            $c_meta                                   = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->commentmeta WHERE comment_id = %d", $c->comment_ID));
            foreach ($c_meta as $meta) {
                if (apply_filters('wxr_export_skip_commentmeta', false, $meta->meta_key, $meta)) {
                    continue;
                }
                $data['comments']['commentmeta']['meta_key']   = $meta->meta_key;
                $data['comments']['commentmeta']['meta_value'] = $meta->meta_value;
            }
        }

        return $data;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function removeWidgets($content)
    {
        return (string) preg_replace('/' . get_shortcode_regex() . '/s', '', $content);
    }

    /**
     * @param array $post_ids
     *
     * @return array
     */
    private function get_page_data($post_ids)
    {
        global $wpdb, $wp_query;

        // Fake being in the loop.
        $wp_query->in_the_loop = true;

        $pages   = [];
        $blog_id = get_current_blog_id();

        // Fetch 20 posts at a time rather than loading the entire table into memory.
        while ($next_posts = array_splice($post_ids, 0)) {
            $where = 'WHERE ID IN (' . join(',', $next_posts) . ')';
            $posts = $wpdb->get_results("SELECT * FROM {$wpdb->posts} $where");

            // Begin Loop.
            foreach ($posts as $post) {
                setup_postdata($post);
                $page_key                             = $blog_id . '_' . $post->ID;
                $is_sticky                            = is_sticky($post->ID) ? 1 : 0;
                $pages[ $page_key ]['post_id']        = $post->ID;
                $pages[ $page_key ]['blog_id']        = $blog_id;
                $pages[ $page_key ]['post_title']     = apply_filters('the_title_rss', $post->post_title);
                $pages[ $page_key ]['pub_date']       = mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false);
                $pages[ $page_key ]['creator']        = get_the_author_meta('login');
                $pages[ $page_key ]['creator']        = get_the_author_meta('login');
                $pages[ $page_key ]['guid']           = get_the_guid($post->ID);
                $pages[ $page_key ]['url']            = get_permalink($post);
                $pages[ $page_key ]['content']        = apply_filters('the_content_export', $post->post_content);
                $pages[ $page_key ]['excerpt']        = apply_filters('the_excerpt_export', $post->post_excerpt);
                $pages[ $page_key ]['post_date']      = $post->post_date;
                $pages[ $page_key ]['post_date_gmt']  = $post->post_date_gmt;
                $pages[ $page_key ]['comment_status'] = $post->comment_status;
                $pages[ $page_key ]['ping_status']    = $post->ping_status;
                $pages[ $page_key ]['post_name']      = $post->post_name;
                $pages[ $page_key ]['post_status']    = $post->post_status;
                $pages[ $page_key ]['post_parent']    = $post->post_parent;
                $pages[ $page_key ]['menu_order']     = $post->menu_order;
                $pages[ $page_key ]['post_type']      = $post->post_type;
                $pages[ $page_key ]['post_password']  = $post->post_password;
                $pages[ $page_key ]['is_sticky']      = $is_sticky;
                $pages[ $page_key ]['taxonomy']       = $this->get_wxr_post_taxonomy($post);
                $pages[ $page_key ]['active']         = \OngStore\Core\Helper\Post::is_active($post);

                if ($post->post_type == 'attachment') {
                    $pages[ $page_key ]['attachment_url'] = wp_get_attachment_url($post->ID);
                }

                $postmeta = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE post_id = %d", $post->ID));
                foreach ($postmeta as $meta) {
                    if (apply_filters('wxr_export_skip_postmeta', false, $meta->meta_key, $meta)) {
                        continue;
                    }
                    $pages[ $page_key ]['postmeta']['meta_key']   = $meta->meta_key;
                    $pages[ $page_key ]['postmeta']['meta_value'] = $meta->meta_value;
                }

                $_comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved <> 'spam'", $post->ID));
                $comments  = array_map('get_comment', $_comments);
                foreach ($comments as $c) {
                    $pages[ $page_key ]['comments']['comment_id']           = $c->comment_ID;
                    $pages[ $page_key ]['comments']['comment_author']       = $c->comment_author;
                    $pages[ $page_key ]['comments']['comment_author_email'] = $c->comment_author_email;
                    $pages[ $page_key ]['comments']['comment_author_url']   = esc_url_raw($c->comment_author_url);
                    $pages[ $page_key ]['comments']['comment_author_IP']    = $c->comment_author_IP;
                    $pages[ $page_key ]['comments']['comment_date']         = $c->comment_date;
                    $pages[ $page_key ]['comments']['comment_date_gmt']     = $c->comment_date_gmt;
                    $pages[ $page_key ]['comments']['comment_content']      = $c->comment_content;
                    $pages[ $page_key ]['comments']['comment_approved']     = $c->comment_approved;
                    $pages[ $page_key ]['comments']['comment_type']         = $c->comment_type;
                    $pages[ $page_key ]['comments']['comment_parent']       = $c->comment_parent;
                    $c_meta                                                 = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->commentmeta WHERE comment_id = %d", $c->comment_ID));
                    foreach ($c_meta as $meta) {
                        if (apply_filters('wxr_export_skip_commentmeta', false, $meta->meta_key, $meta)) {
                            continue;
                        }
                        $pages[ $page_key ]['comments']['commentmeta']['meta_key']   = $meta->meta_key;
                        $pages[ $page_key ]['comments']['commentmeta']['meta_value'] = $meta->meta_value;
                    }
                }
            }
        }

        return $pages;
    }

    public function getFilter()
    {
        return function ($record) {
            return ['page_id' => (key_exists('page_id', $record)) ? $record['page_id'] : -1];
        };
    }
}
