<?php

namespace Irantheme_Dev;

use WP_Query;
use WP_REST_Server;
use WP_REST_Response;
use WP_Error;

if (!defined('ABSPATH')) exit;

if (!class_exists('Irantheme_Routers')) {

    /**
     * Create a base of routers (wp route)
     *
     * @package Wordpress
     * @subpackage irantheme
     * @since irantheme - routers
     * @author iranianclever
     * @see v1.0
     *
     */

    class Irantheme_Routers
    {
        public $prefix;
        public static $text_domain = IRANTHEME_TEXTDOMAIN;


        /**
         * Constructor
         * @return void
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        public function __construct()
        {
            $this->prefix = 'irantheme_';
            add_action('rest_api_init', array($this, 'init_routers'));
        }


        /**
         * Init router post form for receive data form and post creating
         * @return array
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function init_routers()
        {
            // Contact route
            register_rest_route('irantheme/v1', '/contact', array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_post'),
                'permission_callback' => '__return_true'
            ));

            // Create like - POST request
            register_rest_route('irantheme/v1', '/manageLike', array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_like'),
                'permission_callback' => '__return_true'
            ));

            // Delete like - DELETE request
            register_rest_route('irantheme/v1', '/manageLike', array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_like'),
                'permission_callback' => '__return_true'
            ));

            // Search route
            register_rest_route('irantheme/v1', '/search', array(
                'methods' => WP_REST_SERVER::READABLE,
                'callback' => array($this, 'search_results'),
                'permission_callback' => '__return_true'
            ));

            // Posts route
            register_rest_route('irantheme/v1', '/posts', array(
                'methods' => WP_REST_SERVER::READABLE,
                'callback' => array($this, 'posts_results'),
                'permission_callback' => '__return_true'
            ));
        }

        /**
         * Create like post
         */
        function create_like($request)
        {
            try {
                // Check if user is logged in
                if (!is_user_logged_in()) {
                    error_log('Like System: User not logged in - cannot create like');
                    return new WP_Error('unauthorized', 'User not logged in', array('status' => 401));
                }

                $postID = $request->get_param('postID');
                if (!$postID) {
                    return new WP_Error('missing_data', 'Post ID is required', array('status' => 400));
                }

                $user_id = get_current_user_id();

                // Check if user already liked this post
                $existingLike = new WP_Query(array(
                    'post_type' => 'like',
                    'author' => $user_id,
                    'meta_query' => array(
                        array(
                            'key' => 'irantheme_liked_meta_value_key',
                            'compare' => '=',
                            'value' => $postID
                        )
                    ),
                    'posts_per_page' => 1
                ));

                if ($existingLike->have_posts()) {
                    error_log('Like System: User already liked this post - User ID: ' . $user_id . ', Post ID: ' . $postID);
                    return new WP_Error('already_liked', 'Already liked', array('status' => 400));
                }

                // Create the like post
                $likeId = wp_insert_post(array(
                    'post_type' => 'like',
                    'post_status' => 'publish',
                    'post_title' => get_the_title($postID),
                    'post_author' => $user_id,
                    'meta_input' => array(
                        'irantheme_liked_meta_value_key' => $postID
                    )
                ), true);

                if (is_wp_error($likeId)) {
                    error_log('Like System Error: Failed to create like - ' . $likeId->get_error_message());
                    return new WP_Error('creation_failed', 'Failed to create like', array('status' => 500));
                }

                error_log('Like System: Like created successfully - Like ID: ' . $likeId . ', User ID: ' . $user_id . ', Post ID: ' . $postID);

                return new WP_REST_Response(array(
                    'message' => 'Like created successfully',
                    'likeId' => $likeId
                ), 200);
            } catch (\Exception $e) {
                error_log('Like System Error in create_like: ' . $e->getMessage());
                return new WP_Error('server_error', 'Internal server error', array('status' => 500));
            }
        }

        /**
         * Delete like post
         */
        function delete_like($request)
        {
            try {
                // Check if user is logged in
                if (!is_user_logged_in()) {
                    error_log('Like System: User not logged in - cannot delete like');
                    return new WP_Error('unauthorized', 'User not logged in', array('status' => 401));
                }

                $likeID = $request->get_param('like');
                if (!$likeID) {
                    return new WP_Error('missing_data', 'Like ID is required', array('status' => 400));
                }

                $user_id = get_current_user_id();

                // Check if like exists and user owns it
                $like_post = get_post($likeID);

                if (!$like_post) {
                    error_log('Like System: Like not found - Like ID: ' . $likeID);
                    return new WP_Error('not_found', 'Like not found', array('status' => 404));
                }

                if ($like_post->post_author != $user_id) {
                    error_log('Like System: User does not own this like - User ID: ' . $user_id . ', Like Author: ' . $like_post->post_author);
                    return new WP_Error('forbidden', 'You do not have permission to delete that', array('status' => 403));
                }

                if (get_post_type($likeID) != 'like') {
                    error_log('Like System: Invalid post type for like deletion - Post Type: ' . get_post_type($likeID));
                    return new WP_Error('invalid_type', 'Invalid like ID', array('status' => 400));
                }

                // Delete the like
                $deleted = wp_delete_post($likeID, true);

                if (!$deleted) {
                    error_log('Like System Error: Failed to delete like - Like ID: ' . $likeID);
                    return new WP_Error('deletion_failed', 'Failed to delete like', array('status' => 500));
                }

                error_log('Like System: Like deleted successfully - Like ID: ' . $likeID . ', User ID: ' . $user_id);

                return new WP_REST_Response(array(
                    'message' => 'Congrats, like deleted.'
                ), 200);
            } catch (\Exception $e) {
                error_log('Like System Error in delete_like: ' . $e->getMessage());
                return new WP_Error('server_error', 'Internal server error', array('status' => 500));
            }
        }


        /**
         * Create post form
         * @return array
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function posts_results($data)
        {
            // Getting new query for post and page type
            $mainQuery = new WP_Query(array(
                'post_type' => array('post'),
                's' => sanitize_text_field($data['term']), // Sanitize is more security for wordpress functions
                'posts_per_page' => 99999999
            ));

            // Create results for maintenance created json data
            $results = array(
                'posts' => array()
            );

            // Check of of them posts
            while ($mainQuery->have_posts()) {
                $mainQuery->the_post();

                $category_temp = array();
                if (has_category()) {
                    // Get all category of posts
                    $categories = get_the_category();
                    foreach ($categories as $category) {
                        array_push($category_temp, array($category->name => get_category_link($category->term_id)));
                    }
                }
                // Get parent id category of post
                $category = get_the_category();
                // $category_parent_list = array();
                $category_parents_id = '';
                foreach ($category as $cate) {
                    if ($cate->parent) {
                        // From your child category, grab parent ID
                        $parent = $cate->parent;

                        // Load object for parent category
                        $parent_id = get_category($parent);

                        // Grab a category name
                        $parent_id = esc_attr($parent_id->term_id);
                        $category_parents_id .= strval($parent_id) . ',';
                    } else {
                        $category_parents_id .= strval(esc_attr($cate->term_id)) . ',';
                    }
                }
                // Appending data to results array
                if (get_post_type() == 'post') {
                    array_push($results['posts'], array(
                        'id' => get_the_ID(),
                        'title' => get_the_title(),
                        'content' => wp_trim_words(strip_shortcodes(get_the_excerpt()), 43),
                        'permalink' => get_the_permalink(),
                        'imageSrc' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
                        'category' => $category_temp,
                        'date' => get_the_date('j F Y'),
                        'author' => get_the_author_posts_link(),
                        'authorNickname' => get_the_author_meta('nickname'),
                        'authorAvatar' => get_avatar_url(get_the_author_meta('ID')),
                        'dataCategory' => $category_parents_id
                    ));
                }
            }

            // $results['post'] = array_values(array_unique($results['post'], SORT_REGULAR));
            return $results;
        }


        /**
         * Create post form
         * @return array
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function create_post($data)
        {
            // Start session
            session_start();

            // Get data form fields
            $name = sanitize_text_field($data['name']);
            $email = sanitize_email($data['email']);
            $website = esc_url_raw($data['website']);
            $subject = sanitize_text_field($data['subject']);
            $description = sanitize_text_field($data['desc']);

            // Current time
            $time = time();
            // Calculation time and session for operation creating post
            $result = $time - $_SESSION['form_contact_submit'];
            // Limier session
            $limiter = 3600;
            // Check session deprecated or no
            if (!isset($_SESSION['form_contact_submit']) || $result > $limiter) {
                $_SESSION['form_contact_submit'] = $time;
                // Close session
                session_write_close();
                // Create contact post
                return wp_insert_post(array(
                    'post_type' => 'contact',
                    'post_status' => 'publish',
                    'post_title' => $subject,
                    'meta_input' => array(
                        'irantheme_custom_field_contact_name' => $name,
                        'irantheme_custom_field_contact_email' => $email,
                        'irantheme_custom_field_contact_website' => $website,
                        'irantheme_custom_field_contact_subject' => $subject,
                        'irantheme_custom_field_contact_desc' => $description
                    )
                ));
            } else {
                die('limitStatus');
            }
        }


        // Helper function for console logging
        function console_log($message)
        {
            if (WP_DEBUG === true) {
                error_log($message);
            }
        }


        /**
         * Return search of posts
         * @return array
         * @param void
         * @author iranianclever
         * @since v1.0
         * @see v1.0
         */
        function search_results($data)
        {

            // Getting new query for posts
            $mainQuery = new WP_Query(array(
                'post_type' => array('post'),
                's' => sanitize_text_field($data['term']), // Sanitize is more security for wordpress functions
                'posts_per_page' => 99999999
            ));

            // Create results for maintenance created json data
            $results = array(
                'generalInfo' => array()
            );

            // Check of of them posts
            while ($mainQuery->have_posts()) {
                $mainQuery->the_post();
                // Appending data to results array
                if (get_post_type() == 'post') {
                    array_push($results['generalInfo'], array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'content' => get_the_excerpt(),
                        'imageSrc' => get_the_post_thumbnail_url(get_the_ID(), 'large')
                    ));
                }
            }

            return $results;
        }
    } // End Class

    $irantheme_router_like = new Irantheme_Routers();
} // End if class exists statement
