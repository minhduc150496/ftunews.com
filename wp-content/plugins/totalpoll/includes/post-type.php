<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

/**
 * Register post type.
 * 
 * @package TotalPoll\PostType
 * @since 2.0.0
 * @return void
 */
function tp_register_post_type()
{
    global $wp_version;
    
    $labels = array(
        'name' => __('Polls', TP_TD),
        'singular_name' => __('Poll', TP_TD),
        'add_new' => __('Add New', TP_TD),
        'add_new_item' => __('Add New Poll', TP_TD),
        'edit_item' => __('Edit Poll', TP_TD),
        'new_item' => __('New Poll', TP_TD),
        'all_items' => __('All Polls', TP_TD),
        'view_item' => __('View Poll', TP_TD),
        'search_items' => __('Search Polls', TP_TD),
        'not_found' => __('No polls found', TP_TD),
        'not_found_in_trash' => __('No polls found in Trash', TP_TD),
        'parent_item_colon' => '',
        'menu_name' => __('Polls', TP_TD)
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'poll' ),
        'capability_type' => 'post',
        'has_archive' => true,
        'menu_position' => 2,
        'hierarchical' => false,
        'menu_position' => null,
	'menu_icon' => version_compare($wp_version, '3.8', '>=') ? 'dashicons-chart-bar' : TP_ASSETS_URL . 'images/fallback-icon.png',
        'supports' => array( 'title' )
    );
    
    /**
     * Filter post type registration arguments.
     * 
     * @since 2.0.0
     * @filter tp_post_type_args
     */
    register_post_type('poll', apply_filters('tp_post_type_args', $args));
}

/**
 * Update messages.
 * 
 * @package TotalPoll\PostType
 * @global object $post Post object
 * @global int $post_ID Post ID
 * @param array $messages Messages
 * @return array Array of messages
 */
function tp_update_messages($messages)
{
    global $post, $post_ID;

    $messages['poll'] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => sprintf(__('Poll updated. <a href="%s">View poll</a>', TP_TD), esc_url(get_permalink($post_ID))),
        2 => __('Custom field updated.', TP_TD),
        3 => __('Custom field deleted.', TP_TD),
        4 => __('Poll updated.', TP_TD),
        5 => isset($_GET['revision']) ? sprintf(__('Poll restored to revision from %s', TP_TD), wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6 => sprintf(__('Poll published. <a href="%s">View poll</a>', TP_TD), esc_url(get_permalink($post_ID))),
        7 => __('Poll saved.', TP_TD),
        8 => sprintf(__('Poll submitted. <a target="_blank" href="%s">Preview poll</a>', TP_TD), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
        9 => sprintf(__('Poll scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview poll</a>', TP_TD), date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
        10 => sprintf(__('Poll draft updated. <a target="_blank" href="%s">Preview poll</a>', TP_TD), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
    );

    return $messages;
}
