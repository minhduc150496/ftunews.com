<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

    /*
      Addon Name: User limitations
      Description: Limitations for users.
      Addon URI: http://wpsto.re/addons/downloads/user-limitations/
      Author: WPStore
      Author URI: http://wpsto.re/
      Version: 1.0
      Required: 2.0
     */

/**
 * User limitations addon
 * 
 * @version 1.0.0
 * @package TotalPoll\Addons\UserLimitations
 */
Class TP_Users_Limitations_Addon {

    /**
     * Register some hooks
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        // Text domain
        load_plugin_textdomain('tp-ul-addon', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        // Settings
        add_action('tp_admin_editor_after_limitations_content', array( $this, 'settings' ));
        // Vote ability
        add_filter('tp_security_vote_ability', array( $this, 'ability' ));
    }

    /**
     * Check vote ability
     * 
     * @global object $poll
     * @param boolean $ability
     * @return boolean
     */
    public function ability($ability)
    {
        global $poll;

        if ( isset($poll->limitations->logged_users_only_vote) ):

            global $current_user;
            if (
                    isset($poll->limitations->logged_user_role) &&
                    (
                    !is_object($current_user) ||
                    !in_array($current_user->roles[0], array_keys((array) $poll->limitations->logged_user_role))
                    )
            ):
                $this->lock();
                $ability = false;
            endif;

        endif;

        return $ability;
    }

    /**
     * Lock vote & results
     * 
     * @return void
     */
    public function lock()
    {
        add_filter_to_current_poll('tp_poll_render_file', array( $this, 'render_vote' ));
        add_filter_to_current_poll('tp_template_get_part_header.php', array( $this, 'message' ));
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
     * Render vote.php
     * 
     * @since 1.0.0
     * @return string
     */
    public function render_vote($file)
    {
        return 'vote.php';
    }

    /**
     * Display restriction message
     * 
     * @since 1.0.0
     * @param string $content
     * @return string
     */
    public function message($content)
    {
        global $poll;

        unset($poll->showing_results);

        // Hide buttons
        add_filter_to_current_poll('tp_poll_display_buttons', '__return_false');

        $message = sprintf('<p class="tp-warning">%s</p>', sprintf(__("You've to be logged in as %s to vote.", 'tp-ul-addon'), implode(' or ', array_keys((array) $poll->limitations->logged_user_role))));

        return $message . $content;
    }

}

// Bootstrap
new TP_Users_Limitations_Addon();
