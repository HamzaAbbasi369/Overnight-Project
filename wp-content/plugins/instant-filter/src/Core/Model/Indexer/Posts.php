<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Model\Indexer;

use OngStore\Core\Helper\Post;

class Posts extends AbstractPostIndexer
{
    /**
     * {@inheritdoc}
     */
    public function getData($id = 0)
    {
        $data_for_sync = [
            'posts' => []
        ];

        $sites = $this->getSitesData();
        foreach ($sites as $site) {
            $this->switchToBlog($site['blog_id']);
            if ($id) {
                $post_ids = [$id];
            } else {
                $post_ids = $this->get_posts('post');
            }

            add_filter('wxr_export_skip_postmeta', [&$this, 'wxr_filter_postmeta'], 10, 2);

            if ($post_ids) {
                $data_for_sync['posts'] = array_merge($data_for_sync['posts'], $this->getPostData($post_ids));
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

        $is_sticky                 = is_sticky($post->ID) ? 1 : 0;
        $data['post_id']           = $post->ID;
        $data['blog_id']           = $blog_id;
        $data['post_title']        = apply_filters('the_title_rss', $post->post_title);
        $data['pub_date']          = mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false);
        $data['creator']           = get_the_author_meta('login');
        $data['guid']              = get_the_guid($post->ID);
        $data['url']               = get_permalink($post);
        $data['content']           = apply_filters('the_content', $post->post_content);
        $data['excerpt']           = apply_filters('the_excerpt', $post->post_excerpt);
        $data['post_date']         = $post->post_date;
        $data['post_date_gmt']     = $post->post_date_gmt;
        $data['post_modified']     = $post->post_modified;
        $data['post_modified_gmt'] = $post->post_modified_gmt;
        $data['comment_status']    = $post->comment_status;
        $data['ping_status']       = $post->ping_status;
        $data['post_name']         = $post->post_name;
        $data['post_status']       = $post->post_status;
        $data['post_parent']       = $post->post_parent;
        $data['menu_order']        = $post->menu_order;
        $data['post_type']         = $post->post_type;
        $data['post_password']     = $post->post_password;
        $data['is_sticky']         = $is_sticky;
        $data['taxonomy']          = $this->get_wxr_post_taxonomy($post);
        $data['active']            = Post::is_active($post);

        $data['tags'] = implode(',', wp_get_post_tags($post->ID, ['fields' => 'names']));

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
        $_comments = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->comments
                WHERE comment_post_ID = %d AND comment_approved <> 'spam'", $post->ID)
        );
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
            $c_meta                                   = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $wpdb->commentmeta WHERE comment_id = %d", $c->comment_ID)
            );
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
     * @param array $post_ids
     *
     * @return array
     */
    private function getPostData($post_ids)
    {
        /**
         * @global \WP_Query $wp_query
         */
        global $wpdb, $wp_query;

        // Fake being in the loop.
        $wp_query->in_the_loop = true;

        $posts   = [];
        $blog_id = get_current_blog_id();

        // Fetch 20 posts at a time rather than loading the entire table into memory.
        while ($next_posts = array_splice($post_ids, 0)) {
            $where = 'WHERE ID IN (' . join(',', $next_posts) . ')';
            $rows  = $wpdb->get_results("SELECT * FROM {$wpdb->posts} $where");

            // Begin Loop.
            foreach ($rows as $post) {
                $post_key = $blog_id . '_' . $post->ID;
                setup_postdata($post);
                $is_sticky                               = is_sticky($post->ID) ? 1 : 0;
                $posts[ $post_key ]['post_id']           = $post->ID;
                $posts[ $post_key ]['blog_id']           = $blog_id;
                $posts[ $post_key ]['post_title']        = apply_filters('the_title_rss', $post->post_title);
                $posts[ $post_key ]['pub_date']          = mysql2date(
                    'D, d M Y H:i:s +0000',
                    get_post_time('Y-m-d H:i:s', true),
                    false
                );
                $posts[ $post_key ]['creator']           = get_the_author_meta('login');
                $posts[ $post_key ]['guid']              = get_the_guid($post->ID);
                $posts[ $post_key ]['url']               = get_permalink($post);
                $posts[ $post_key ]['content']           = apply_filters('the_content_export', $post->post_content);
                $posts[ $post_key ]['excerpt']           = apply_filters('the_excerpt_export', $post->post_excerpt);
                $posts[ $post_key ]['post_date']         = $post->post_date;
                $posts[ $post_key ]['post_date_gmt']     = $post->post_date_gmt;
                $posts[ $post_key ]['post_modified']     = $post->post_modified;
                $posts[ $post_key ]['post_modified_gmt'] = $post->post_modified_gmt;
                $posts[ $post_key ]['comment_status']    = $post->comment_status;
                $posts[ $post_key ]['ping_status']       = $post->ping_status;
                $posts[ $post_key ]['post_name']         = $post->post_name;
                $posts[ $post_key ]['post_status']       = $post->post_status;
                $posts[ $post_key ]['post_parent']       = $post->post_parent;
                $posts[ $post_key ]['menu_order']        = $post->menu_order;
                $posts[ $post_key ]['post_type']         = $post->post_type;
                $posts[ $post_key ]['post_password']     = $post->post_password;
                $posts[ $post_key ]['is_sticky']         = $is_sticky;
                $posts[ $post_key ]['taxonomy']          = $this->get_wxr_post_taxonomy($post);
                $posts[ $post_key ]['active']            = Post::is_active($post);

                $posts[ $post_key ]['tags'] = implode(',', wp_get_post_tags($post->ID, ['fields' => 'names']));

                if ($post->post_type == 'attachment') {
                    $posts[ $post_key ]['attachment_url'] = wp_get_attachment_url($post->ID);
                }

                $postmeta = $wpdb->get_results(
                    $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE post_id = %d", $post->ID)
                );
                foreach ($postmeta as $meta) {
                    if (apply_filters('wxr_export_skip_postmeta', false, $meta->meta_key, $meta)) {
                        continue;
                    }
                    $posts[ $post_key ]['postmeta']['meta_key']   = $meta->meta_key;
                    $posts[ $post_key ]['postmeta']['meta_value'] = $meta->meta_value;
                }
                $_comments = $wpdb->get_results(
                    $wpdb->prepare("SELECT * FROM $wpdb->comments
                      WHERE comment_post_ID = %d AND comment_approved <> 'spam'", $post->ID)
                );
                $comments  = array_map('get_comment', $_comments);
                foreach ($comments as $c) {
                    $posts[ $post_key ]['comments']['comment_id']           = $c->comment_ID;
                    $posts[ $post_key ]['comments']['comment_author']       = $c->comment_author;
                    $posts[ $post_key ]['comments']['comment_author_email'] = $c->comment_author_email;
                    $posts[ $post_key ]['comments']['comment_author_url']   = esc_url_raw($c->comment_author_url);
                    $posts[ $post_key ]['comments']['comment_author_IP']    = $c->comment_author_IP;
                    $posts[ $post_key ]['comments']['comment_date']         = $c->comment_date;
                    $posts[ $post_key ]['comments']['comment_date_gmt']     = $c->comment_date_gmt;
                    $posts[ $post_key ]['comments']['comment_content']      = $c->comment_content;
                    $posts[ $post_key ]['comments']['comment_approved']     = $c->comment_approved;
                    $posts[ $post_key ]['comments']['comment_type']         = $c->comment_type;
                    $posts[ $post_key ]['comments']['comment_parent']       = $c->comment_parent;
                    $c_meta                                                 = $wpdb->get_results(
                        $wpdb->prepare("SELECT * FROM $wpdb->commentmeta WHERE comment_id = %d", $c->comment_ID)
                    );
                    foreach ($c_meta as $meta) {
                        if (apply_filters('wxr_export_skip_commentmeta', false, $meta->meta_key, $meta)) {
                            continue;
                        }
                        $posts[ $post_key ]['comments']['commentmeta']['meta_key']   = $meta->meta_key;
                        $posts[ $post_key ]['comments']['commentmeta']['meta_value'] = $meta->meta_value;
                    }
                }
            }
        }

        return $posts;
    }

    public function getFilter()
    {
        return function ($record) {
            return ['post_id' => (key_exists('post_id', $record)) ? $record['post_id'] : -1];
        };
    }
}
