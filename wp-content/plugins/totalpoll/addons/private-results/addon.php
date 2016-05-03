<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

    /*
      Addon Name: Private results
      Description: Keep your polls results private.
      Addon URI: http://wpsto.re/addons/downloads/private-results/
      Author: WPStore
      Author URI: http://wpsto.re/
      Version: 1.0
      Required: 2.0
     */

/**
 * Private results addon.
 * 
 * @version 1.0.0
 * @package TotalPoll\Addons\PrivateResults
 */
Class TP_Private_Results_Addon {

    /**
     * Register settings and content hooks
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        // Text domain
        load_plugin_textdomain('tp-pr-addon', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        // Settings
        add_action('tp_admin_editor_after_limitations_content', array( $this, 'settings' ));
        // Force vote to see results
        add_action('tp_template_get_part_header.php', array( $this, 'force_vote_to_see_results' ));
        // Content
        add_filter('tp_poll_render', array( $this, 'content' ));
    }

    /**
     * Poll editor special settings
     * 
     * @since 1.0.0
     * @return void
     */
    public function settings($options)
    {
        include_once('poll-settings.php');
    }

    /**
     * Force "vote to see results" button.
     * @global object $poll
     * @param string $content
     * @return string
     */
    public function force_vote_to_see_results($content)
    {
        global $poll;
        if ( isset($poll->limitations->private_results) ):
            $poll->limitations->vote_for_results = true;
        endif;
        return $content;
    }

    /**
     * Content override
     * 
     * @since 1.0.0
     * @global object $poll
     * @param string $content
     * @return string
     */
    public function content($content)
    {
        global $poll;
        if ( isset($poll->limitations->private_results) && isset($poll->showing_results) ):
            if ( isset($poll->limitations->show_results_after) ):
                if ( $poll->limitations->show_results_after == 'never' ||
                        $poll->limitations->show_results_after == 'date' && !is_poll_finished() ||
                        $poll->limitations->show_results_after == 'quota' && !is_poll_quota_exceeded()
                ):
                    return do_shortcode(str_replace('[results]', $content, wpautop($poll->limitations->private_results_content)));
                endif;
            endif;
        endif;
        return $content;
    }

}

// Bootstrap
new TP_Private_Results_Addon();
