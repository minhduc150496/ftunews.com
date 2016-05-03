<?php

if ( !defined('ABSPATH') )
    exit; // Shhh

    /*
      Addon Name: Maximum selection
      Description: Set a limit for multi-selection polls
      Addon URI: http://wpsto.re/addons/downloads/maximum-selection/
      Author: WPStore
      Author URI: http://wpsto.re/
      Version: 1.0
      Required: 2.0
     */

/**
 * Maximum selection addon.
 * 
 * @version 1.0.0
 * @package TotalPoll\Addons\MaximumSelection
 */
Class TP_Maximum_Selection_Addon {

    /**
     * Register some hooks
     * 
     * @since 1.0.0
     * @return void
     */
    public function __construct()
    {
        // Textdomain
        load_plugin_textdomain('tp-ms-addon', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        // Poll settings
        add_action('tp_admin_editor_after_limitations_content', array( $this, 'poll_settings' ));
        // Vote ability
        add_filter('tp_security_vote_ability', array( $this, 'ability' ));
        // Container attributes
        add_action('tp_poll_container_attributes', array( $this, 'attribute' ));
        // JS
        add_action('wp_footer', array( $this, 'code' ));
    }

    /**
     * Poll editor special settings
     * 
     * @since 1.0.0
     * @return void
     */
    public function poll_settings($options)
    {
        include_once('poll-settings.php');
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
        // Some checks before starting
        if ( isset($poll->limitations->limit_maximum_answers) && intval($poll->limitations->maximum_answers) > 1 ):

            if ( isset($_REQUEST['tp_choices']) && count($_REQUEST['tp_choices']) > $poll->limitations->maximum_answers ):
                // Render vote.php
                add_filter_to_current_poll('tp_poll_render_file', array( $this, 'render_vote' ));
                // Display restriction message
                add_filter_to_current_poll('tp_template_get_part_header.php', array( $this, 'message' ));
                // Disable voting
                $ability = false;
            endif;

        endif;
        return $ability;
    }

    /**
     * Output GA code
     * 
     * @since 1.0.0
     * @return void
     */
    public function code()
    {
        wp_enqueue_script('tp-facebook-like', TP_ADDONS_URL . basename(dirname(__FILE__)) . '/helper.min.js', array( 'jquery' ), TP_VERSION);
    }

    /**
     * Special attribute
     * 
     * @since 1.0.0
     * @return void
     */
    public function attribute($attributes)
    {
        global $poll;
        if ( isset($poll->limitations->limit_maximum_answers) && intval($poll->limitations->maximum_answers) > 1 ):
            $attributes .= " data-limit-selection=\"{$poll->limitations->maximum_answers}\"";
        endif;
        return $attributes;
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

        $message = sprintf('<p class="tp-warning">%s</p>', sprintf(__("You can vote for up to %s choices.", 'tp-ms-addon'), $poll->limitations->maximum_answers));

        return $message . $content;
    }

}

// Bootstrap it
new TP_Maximum_Selection_Addon();
